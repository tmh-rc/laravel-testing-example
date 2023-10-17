# Laravel Testing Example

## Installation

Git clone:
```
git clone -b test-pest https://github.com/tmh-rc/laravel-testing-example.git
````

Install dependencies
```
npm install
npm run build
composer install
```

Generate key:
```
php artisan key:generate
```

Setup env
```
cp .env.example .env
cp .env.testing.example .env.testing
```

Create two databases, one for the application and another for testing.
eg. 
```.env
#.env
DB_DATABASE=laravel
```

```.env
#.env.testing
DB_DATABASE=laravel_testing
```

Run test
```
php artisan test
```
Run test specific files
```
php artisan test tests/Feature/Post/PostCreate.php
```
Run test specific function
```
php artisan test 'authenticated user can see a post create form'
```

### See Source Code

- [Post CRUD and File uploading](https://github.com/tmh-rc/laravel-testing-example/tree/post-crud)
- [Testing Pest](https://github.com/tmh-rc/laravel-testing-example/tree/test-pest)