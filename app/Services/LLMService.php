<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentChunk;
use RuntimeException;
use Symfony\AI\Platform\Bridge\Generic\CompletionsModel;
use Symfony\AI\Platform\Bridge\LmStudio\Factory;
use Symfony\AI\Platform\Capability;
use Symfony\AI\Platform\Exception\ExceptionInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class LLMService
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public static function embed(string $message)
    {
        $config = config('llm');
        $apiKey = $config['key'] ?? null;
        $url = $config['url_embed'] ?? null;
        $headers['Content-Type'] = 'application/json';

        if ($apiKey !== null) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $httpClient = HttpClient::create(['headers' => $headers]);
        $response = $httpClient->request(
            'POST',
            $url,
            [
                'json' => [
                    'content' => $message,
                ],
            ]
        );

        $data = $response->toArray();

        return $data[0]['embedding'][0] ?? null;
    }

    public static function init(string $fileName, string $title, string $source, string $type): bool
    {
        try {
            $document = self::saveDocument($title, $source, $type);

            self::saveChunks($fileName, $document);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            throw new RuntimeException($e->getMessage());
        }

        return true;
    }

    public static function saveChunks(string $fileName, Document $document)
    {
        $content = file_get_contents($fileName);

        $chunks = (new ChunkerService())->split($content, 1800, 300);

        foreach ($chunks as $index => $chunk) {

            $embedding = self::embed($chunk);

            DocumentChunk::create([
                'document_id' => $document->id,
                'chunk_index' => $index,
                'content' => $chunk,
                'embedding' => '[' . implode(',', $embedding) . ']',
            ]);

        }
    }

    public static function saveDocument(string $title, string $source, string $type): Document
    {
        return Document::create([
            'title' => $title,
            'source' => $source,
            'type' => $type,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public static function chat(string $message): string
    {
        $config = config('llm');

        $model = $config['model'] ?? null;
        if (!is_string($model) || '' === trim($model)) {
            throw new RuntimeException('Configure the LLM_DESC_MODEL environment variable before calling the LLM service.');
        }

        $baseUrl = $config['url'] ?? null;
        if (!is_string($baseUrl) || '' === trim($baseUrl)) {
            throw new RuntimeException('Configure the LMSTUDIO_HOST_URL environment variable before calling the LLM service.');
        }

        $apiKey = $config['key'] ?? null;
        $headers['Content-Type'] = 'application/json';

        if ($apiKey !== null) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $httpClient = HttpClient::create(['headers' => $headers]);

        $platform = Factory::createPlatform(
            rtrim($baseUrl, '/'),
            $httpClient
        );

        $schema = [
            'type' => 'object',
            'properties' => [
                'content' => [
                    'type' => 'string',
                ],
            ],
            'required' => [
                'content',
            ],
            'additionalProperties' => false,
        ];


        $systemPrompt = $config['agent']['text'] ?? '';

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser($message),
        );

        // LM Studio can expose any locally loaded model. Passing a model instance
        // avoids the bridge's small built-in catalog rejecting its custom name.

        $options = [
            ...($config['options'] ?? []),
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'marketplace_output',
                    'strict' => true,
                    'schema' => $schema,
                ],
            ],
        ];
        $result = $platform->invoke(
            new CompletionsModel($model, [
                Capability::INPUT_MESSAGES,
                Capability::OUTPUT_TEXT,
            ]),
            $messages,
            $options,
        );

        return $result->asText();
    }
}
