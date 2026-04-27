<?php

namespace App\Providers;

use Log;
use Native\Desktop\Facades\AutoUpdater;
use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->afterOpen(function () {
                Log::info("Buscando actualizaciones...");
                AutoUpdater::checkForUpdates();
            })
            ->height(768)
            ->width(1366)
            ->minHeight(768)
            ->minWidth(1366)
            ->hideMenu();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
