<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sudoku\Service\SudokuService;

class SudokuProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SudokuService::class, function ($app) {
            return new SudokuService();
        });
    }
}
