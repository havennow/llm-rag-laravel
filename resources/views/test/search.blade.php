<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 min-h-screen p-6 lg:p-8">
    <main class="mx-auto w-full max-w-2xl flex flex-col gap-6">
        <header>
            <h1 class="text-2xl font-semibold text-gray-900">Perguntar aos documentos</h1>
            <p class="mt-1 text-sm text-gray-500">A resposta é gerada a partir dos documentos indexados.</p>
        </header>

        <form id="ask-form" method="GET" action="{{ route('test.search') }}" class="flex gap-2">
            <input
                type="text"
                id="ask"
                name="ask"
                value="{{ $ask }}"
                placeholder="Digite sua pergunta"
                autocomplete="off"
                autofocus
                required
                class="flex-1 rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-gray-900 focus:outline-none"
            >
            <button
                type="submit"
                id="ask-submit"
                class="rounded-md bg-gray-900 px-4 py-2 font-medium text-white hover:bg-gray-700 disabled:opacity-50"
            >
                Perguntar
            </button>
        </form>

        <p id="ask-loading" class="hidden text-sm text-gray-500">Consultando o modelo…</p>

        <div
            id="ask-error"
            class="{{ $error ? '' : 'hidden' }} rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
        >{{ $error }}</div>

        <article
            id="ask-answer"
            class="{{ $answer ? '' : 'hidden' }} rounded-md border border-gray-200 bg-white px-4 py-3 whitespace-pre-wrap break-words text-gray-800"
        >{{ $answer }}</article>
    </main>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        $(function () {
            var $form = $('#ask-form');
            var $submit = $('#ask-submit');
            var $loading = $('#ask-loading');
            var $error = $('#ask-error');
            var $answer = $('#ask-answer');

            $form.on('submit', function (event) {
                var ask = $.trim($('#ask').val());

                if (ask === '') {
                    return;
                }

                // Plain GET submit stays the no-JavaScript fallback.
                event.preventDefault();

                $submit.prop('disabled', true);
                $loading.removeClass('hidden');
                $error.addClass('hidden').text('');
                $answer.addClass('hidden').text('');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'GET',
                    data: { ask: ask },
                    headers: { Accept: 'application/json' }
                }).done(function (response) {
                    $answer.text(response.answer).removeClass('hidden');
                    history.replaceState(null, '', $form.attr('action') + '?ask=' + encodeURIComponent(ask));
                }).fail(function (xhr) {
                    var message = (xhr.responseJSON && xhr.responseJSON.error)
                        || 'Não foi possível consultar o modelo. Tente novamente.';

                    $error.text(message).removeClass('hidden');
                }).always(function () {
                    $submit.prop('disabled', false);
                    $loading.addClass('hidden');
                });
            });
        });
    </script>
</body>
</html>
