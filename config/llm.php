<?php


//system prompt
$agent = <<< TEXT
TEXT;

return [
    'key' => env('LLM_KEY'),
    'url' => env('LLM_HOST', 'http://localhost:1234'),
    'url_embed' => env('LLM_EMBED_HOST', 'http://host.docker.internal:8081/embeddings'),
    'model' => env('LLM_MODEL'),
    'options' => [
        //'max_tokens' => (int) env('LLM_MAX_TOKENS', 500),
        'temperature' => (float)env('LLM_TEMPERATURE', 1.0),
        'top_p' => 0.9
    ],
    'stream' => [
        'max_seconds' => (int) env('LLM_STREAM_MAX_SECONDS', 60),
        'poll_ms' => (int) env('LLM_STREAM_POLL_MS', 500),
    ],
    'agent' => [
        'text' => $agent,
    ],
];
