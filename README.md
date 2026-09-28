<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
#RAG simple

- pgvecor PGSQL
- Mysql (not used but you can use ;) )
- embed with model used use https://huggingface.co/LiquidAI/LFM2.5-Embedding-350M

# Using This Project

## Setup

1. Run `make build`  and `make install` to install dependencies and configure the project.

2. Ensure environment variables are set (copy `.env.example` to `.env`).

3. Start the development server with `make start` or use Docker as described in the Makefile.

4. Need create embed server with llm-server on local 8081, suggest lfm models for this

5. Up lm studio local or another machine for llm chat, best for use +8B models, lfm2.5, qwen, gpt openai oss and more

## Routes for Testing

- **/test** – Creates an embed using the Boost library. Access this route after the app is running to verify embed generation.
- **/search** – Triggers a test LLM search with RAG (Retrieval‑Augmented Generation). Visiting this route demonstrates the integration of the language model and retrieval backend.

Feel free to explore other routes defined in `routes/web.php`.

### Create *Embed*

- To create an *embed*, first, need put in storage/app/public your text file with content for test, is a source of knowledge, need edit TestController.php right, is simply follow the steps below:
- Access /test route and see messagem OK is good for save in database saved embed, need put .env pgsql credentials first.

### Using *Search*
- Access /search route and enjoy, but need up llm emebd model first and need up llm model in your lm studio server for chat
  
## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an email to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
