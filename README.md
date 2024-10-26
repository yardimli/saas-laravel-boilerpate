
## About SaaS Laravel Boilerplate

Playground is a application that uses different AI models to write books. You can use the application to generate books, stories, and other content. You can use OpenRouter or OpenAI or Anthropic to generate content currently.

## Contributing

Thank you for considering contributing to the Playground framework! The contribution guide can be found in the [Laravel documentation](https://mindful-enlightenment.com/contributions).

## Code of Conduct

In order to ensure that the Playground community is welcoming to all, please review and abide by the [Code of Conduct](https://mindful-enlightenment.com/docs/contributions#code-of-conduct).

## License

Playground is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

#
### Playground SETUP

run `composer install`

run `php artisan key:generate`

edit `the .env file to match your database credentials for boty mysql and postgres`

run `php artisan migrate`

run `php artisan storage:link`

run `npm install`

run `npm run build`

run `php artisan vendor:publish --provider="BinshopsBlog\BinshopsBlogServiceProvider"`

run `php artisan serve`

edit `.env file to include the various AI api keys`

go to `http://localhost/register and add yourself as a user`

mysql `open users table and change the role of the user to admin ( member_type = 1)`

go to `http://localhost/blog_admin/setup and setup the blog and add a hello world artice`
