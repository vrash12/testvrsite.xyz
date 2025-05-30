<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\GuestLayout;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('guest-layout', GuestLayout::class);
    }
}