<?php

namespace CalvilloComMx\Providers;

use CalvilloComMx\Core\Category;
use CalvilloComMx\Core\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'CalvilloComMx\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return<void
     */
    public function boot()
    {
        //

        parent::boot();

        Passport::routes();
        Route::bind('user', function ($value) {
            if ($value == 'me') {
                return \Auth::user();
            } else {
                return User::whereKey($value)->firstOrFail();
            }
        });

        Route::bind('category_link', function ($value) {
            return Category::whereLink($value)->firstOrFail();
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
