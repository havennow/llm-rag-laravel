<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel é um framework web que oferece sintaxe expressiva e elegante. Ele facilita tarefas comuns em projetos PHP, como:

- [Roteamento simples e rápido](https://laravel.com/docs/routing).
- [Container de injeção de dependência robusto](https://laravel.com/docs/container).
- Vários back‑ends para *session* e *cache*.
- ORM intuitivo (*Eloquent*).
- Migrações independentes de banco de dados.
- Processamento assíncrono de filas.
- Broadcast em tempo real.

Essas características tornam o Laravel uma escolha sólida para aplicações complexas.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Desenvolvimento Assistido por IA

A estrutura previsível do Laravel facilita o uso de agentes de codificação (Claude, Cursor, Copilot). Instale o **Laravel Boost** para potencializar seu fluxo de trabalho com inteligência artificial.

```bash
composer require laravel/boost --dev

php artisan boost:install
```

O Boost disponibiliza mais de 15 ferramentas e habilidades que ajudam agentes a construir aplicações Laravel respeitando as melhores práticas.

### Criar *Embed*
Para criar um *embed* (por exemplo, para usar no Discord ou em outra plataforma), basta seguir os passos abaixo:

1. Crie o conteúdo que será embutido usando a sintaxe Markdown ou HTML.
2. Utilize o método `Embed::make()` (exemplo fictício) fornecido pelo Boost para gerar o objeto de embed.
3. Envie o embed via API da plataforma desejada.

### Usar *Search*
O Boost oferece uma ferramenta de busca avançada que permite localizar recursos, rotas e componentes dentro do projeto. Para usar:

1. Execute `php artisan boost:search "palavra‑chave"` no terminal.
2. O resultado será exibido em formato legível com links para os arquivos correspondentes.
3. Você pode refinar a busca adicionando filtros de tipo (`--type=route`, `--type=model`) ou escopo (`--path=app/`).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

# Using This Project

## Setup

1. Run `make setup` to install dependencies and configure the project.
2. Ensure environment variables are set (copy `.env.example` to `.env`).
3. Start the development server with `php artisan serve` or use Docker as described in the Makefile.

## Routes for Testing

- **/test** – Creates an embed using the Boost library. Access this route after the app is running to verify embed generation.
- **/search** – Triggers a test LLM search with RAG (Retrieval‑Augmented Generation). Visiting this route demonstrates the integration of the language model and retrieval backend.

Feel free to explore other routes defined in `routes/web.php`.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
