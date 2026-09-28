<?php

namespace App\Http\Controllers;

use App\Models\DocumentChunk;
use App\Services\LLMService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\AI\Platform\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

class TestController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function index(Request $request)
    {
        $filename = storage_path('app/public/your-file.txt');
        $teste = LLMService::init(
            $filename,
            'title of file',
            'file_name.txt',
            'text/plain'
        );

        return view('test.index', compact('teste'));
    }

    /**
     * Ask the indexed documents a question through the retrieval pipeline.
     *
     * Without a question the form renders on its own, so that opening
     * /test/search never reaches the embedding or completion endpoints.
     * jQuery calls this same URL with a JSON Accept header.
     */
    public function search(Request $request): View|JsonResponse
    {
        $ask = trim((string) $request->query('ask', ''));

        if ($ask === '') {
            return $this->respond($request, $ask, null, null);
        }

        try {
            return $this->respond($request, $ask, $this->answerFor($ask), null);
        } catch (Throwable $exception) {
            report($exception);

            return $this->respond($request, $ask, null, 'Não foi possível consultar o modelo. Tente novamente.');
        }
    }

    /**
     * Render the answer as JSON for jQuery, or as the full page otherwise.
     */
    private function respond(Request $request, string $ask, ?string $answer, ?string $error): View|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json(
                ['ask' => $ask, 'answer' => $answer, 'error' => $error],
                $error === null ? 200 : 502
            );
        }

        return view('test.search', compact('ask', 'answer', 'error'));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
     */
    private function answerFor(string $ask): string
    {
        $embed = LLMService::embed($ask);
        $chunks = DocumentChunk::searchSimilar($embed);

        $context = $chunks
            ->pluck('content')
            ->implode("\n\n---\n\n");

        $prompt = <<<PROMPT
            You are a specialized assistant.

            Answer the question using the provided context.

            If the information is not in context, say that you did not find enough information.

            CONTEXT:

            {$context}

            QUESTION:

            {$ask}

        PROMPT;

        return $this->contentOf(LLMService::chat($prompt));
    }

    /**
     * LLMService::chat() answers with the JSON envelope its schema declares,
     * shaped as {"content": "..."}. Fall back to the raw text if that changes.
     */
    private function contentOf(string $completion): string
    {
        $decoded = json_decode($completion, true);

        if (is_array($decoded) && isset($decoded['content']) && is_string($decoded['content'])) {
            return $decoded['content'];
        }

        return $completion;
    }
}
