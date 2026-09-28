<?php

it('renders the ask form without querying the language model', function () {
    // A closed port proves the page never reaches the embedding endpoint.
    config()->set('llm.url_embed', 'http://127.0.0.1:9');

    $response = $this->get(route('test.search'));

    $response->assertOk()
        ->assertSee('id="ask-form"', false)
        ->assertSee('method="GET"', false)
        ->assertSee('name="ask"', false);
});

it('reports an unreachable model instead of failing', function () {
    config()->set('llm.url_embed', 'http://127.0.0.1:9');

    $response = $this->get(route('test.search', ['ask' => 'a question']));

    $response->assertOk()
        ->assertSee('Não foi possível consultar o modelo. Tente novamente.')
        ->assertSee('value="a question"', false);
});

it('answers jQuery with json when the model is unreachable', function () {
    config()->set('llm.url_embed', 'http://127.0.0.1:9');

    $response = $this->getJson(route('test.search', ['ask' => 'a question']));

    $response->assertStatus(502)
        ->assertJson([
            'ask' => 'a question',
            'answer' => null,
        ])
        ->assertJsonPath('error', 'Não foi possível consultar o modelo. Tente novamente.');
});
