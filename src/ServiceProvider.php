<?php
namespace Maitiigor\rc;


use Maitiigor\RC\Providers\RevenueCollectionEventServiceProvider;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/rc.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'rc-module');

        // Publish view files
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/rc-module'),
        ], 'views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('rc-module'),
        ], 'assets');

        // Publish view components
        $this->publishes([
            __DIR__ . '/../src/View/Components/' => app_path('View/Components'),
            __DIR__ . '/../resources/views/components/' => resource_path('views/components'),
        ], 'view-components');

        /*    $this->publishes([
               __DIR__ . '/../database/seeders/BASSeeder.php' => database_path('seeders/BASSeeder.php'),
           ], 'seeders'); */


        Blade::componentNamespace('Maitiigor\RC\\View\\Components', 'rc-module');

        // Register the available commands if we are using the application via the CLI
        if ($this->app->runningInConsole()) {

        }
    }

    /**
     * Make config publishing optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/rc.php';
        $this->mergeConfigFrom($configPath, 'rc');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->bind('rc', function ($app) {
            return new rc();
        });

        $this->app->register(RevenueCollectionEventServiceProvider::class);
        $this->registerFactories();
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('rc.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('rc.php')], 'config');
    }

    /**
     * Register a Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }

    /**
     * Register the model factories
     *
     * @param  null
     * @return void
     */
    protected function registerFactories(): void
    {
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
        Factory::guessFactoryNamesUsing(function (string $modelClass) {
            return 'Maitiigor\RC\\Database\\Factories\\' . class_basename($modelClass) . 'Factory';
        });
    }
}