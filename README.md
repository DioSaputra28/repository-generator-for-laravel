# Repository Generator for Laravel

[![Latest Stable Version](https://poser.pugx.org/diosaputra/repository-generator-for-laravel/v/stable)](https://packagist.org/packages/diosaputra/repository-generator-for-laravel)
[![Total Downloads](https://poser.pugx.org/diosaputra/repository-generator-for-laravel/downloads)](https://packagist.org/packages/diosaputra/repository-generator-for-laravel)
[![License](https://poser.pugx.org/diosaputra/repository-generator-for-laravel/license)](https://packagist.org/packages/diosaputra/repository-generator-for-laravel)

A Laravel package that provides an artisan command to easily generate repositories following the repository pattern, with support for multiple implementation types.

## Features

- Generate repositories with a simple artisan command
- Support for multiple repository types (Eloquent, Query Builder, API)
- Create repositories with or without interfaces
- Generate multiple repository implementations at once
- File existence validation to prevent overwriting
- Option to force overwrite existing files

## Installation

You can install the package via composer:

```bash
composer require diosaputra/repository-generator-for-laravel
```

The package will automatically register its service provider.

## Usage

### Basic Usage

Generate a basic repository:

```bash
php artisan make:repository User
```

This will create a basic repository at `app/Repositories/UserRepository.php`.

### Generating with Type

Generate a repository with a specific type:

```bash
php artisan make:repository User --type=eloquent
```

This will create:
- An interface at `app/Repositories/Interface/UserRepositoryInterface.php`
- An implementation at `app/Repositories/Eloquent/UserRepositoryEloquent.php`

### Multiple Types

Generate multiple repository implementations at once:

```bash
php artisan make:repository User --type=eloquent,query,api
```

This will create:
- An interface at `app/Repositories/Interface/UserRepositoryInterface.php`
- An eloquent implementation at `app/Repositories/Eloquent/UserRepositoryEloquent.php`
- A query builder implementation at `app/Repositories/Query/UserRepositoryQuery.php`
- An API implementation at `app/Repositories/Api/UserRepositoryApi.php`

### Force Overwrite

Use the `--force` flag to overwrite existing files:

```bash
php artisan make:repository User --type=eloquent --force
```

## Repository Structure

The generated repositories will have the following structure:

### Basic Repository

```php
<?php

namespace App\Repositories;

class UserRepository
{
    public function getAll()
    {
        // Implement getAll()
    }

    public function findById($id)
    {
        // Implement findById()
    }
}
```

### Interface

```php
<?php

namespace App\Repositories\Interface;

interface UserRepositoryInterface
{
    public function getAll();
    
    public function findById($id);
}
```

### Typed Repository

```php
<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interface\UserRepositoryInterface;

class UserRepositoryEloquent implements UserRepositoryInterface
{
    public function getAll()
    {
        // Implement getAll()
    }

    public function findById($id)
    {
        // Implement findById()
    }
}
```

## Using Repositories in Your Application

After generating repositories, you'll need to bind them to your service container. Add the following to your `AppServiceProvider`:

```php
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepositoryEloquent;

public function register()
{
    $this->app->bind(UserRepositoryInterface::class, UserRepositoryEloquent::class);
}
```

Then you can use dependency injection in your controllers:

```php
use App\Repositories\Interface\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function index()
    {
        $users = $this->userRepository->getAll();
        return view('users.index', compact('users'));
    }
}
```

## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
