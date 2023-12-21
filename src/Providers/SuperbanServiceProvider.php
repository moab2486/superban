<?php
    namespace Abdulkadir\Superban;

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Contracts\Http\Kernel;
    
    class SuperbanServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            $kernel = $this->app->make(Kernel::class);
    
            $enabledRoutes = config('superban.routes.enabled', []);
    
            foreach ($enabledRoutes as $route => $middleware) {
                $kernel->prependMiddlewareToRoute($middleware, $route);
            }

            if ($this->app->runningInConsole()) {
                $this->commands([
                    SuperbanInstallCommand::class,
                ]);
            }

            $this->publishes([
                __DIR__.'/../config/superban.php' => config_path('superban.php'),
            ], 'superban-config');
        }
    }