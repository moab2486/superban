<?php

namespace Abdulkadir\Superban\Commands;

use Illuminate\Console\Command;

class SuperbanInstallCommand extends Command
{
    protected $signature = 'superban:install {--driver=}';

    protected $description = 'Install and configure Superban package';

    public function handle()
    {
        $cacheDriver = $this->option('driver');

        if (!empty($cacheDriver)) {
            Config::set('superban.cache_driver', $cacheDriver);
            $this->info('Cache driver set to: ' . $cacheDriver);
        } else {
            $this->info('No cache driver specified. Using default.');
        }

        $this->call('vendor:publish', [
            '--tag' => 'superban-config',
        ]);

        $this->info('Superban package installed successfully.');
    }
}
