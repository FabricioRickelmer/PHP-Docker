<?php

namespace App\Providers;

use App\DataAccessors\Contracts\StudentDataAccessorInterface;
use App\DataAccessors\StudentDataAccessor;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Eloquent\StudentRepository;
use App\Services\Contracts\StudentServiceInterface;
use App\Services\StudentService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(StudentDataAccessorInterface::class, StudentDataAccessor::class);
        $this->app->bind(StudentServiceInterface::class, StudentService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
