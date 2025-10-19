<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class IconServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $icons = [
            'create',
            'dashboard',
            'delete',
            'inventory',
            'logout',
            'profile',
            'read',
            'reports',
            'requests',
            'schedule',
            'settings',
            'update',

            'box',
            'burger',
            'time',
            'file',
        ];

        foreach ($icons as $icon) {
            Blade::component("admin.components.icons.$icon", "icon_$icon");
        }
    }
}
