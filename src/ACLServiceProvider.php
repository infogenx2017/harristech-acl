<?php

namespace Harristech\ACL;

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-08-01 00:38:31
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-09-16 23:35:59
 */

use Illuminate\Support\ServiceProvider;

class ACLServiceProvider extends ServiceProvider 
{
	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(
		    \Spatie\Permission\PermissionServiceProvider::class
		);

	    $this->mergeConfigFrom(
	        __DIR__.'/../config/acl.php', 'acl'
	    );
	}

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
	    // load custom route overrides
	    $this->loadRoutesFrom( __DIR__.'/../routes.php' );

	    // load migrations
	    $this->loadMigrationsFrom( __DIR__.'/../migrations' );

	    // run this to load laravel-permission migration files
	    // php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

	    // load the views
	    $this->loadViewsFrom( __DIR__.'/../views', 'acl' );

	    // make translation files publishable
	    $this->publishes([
	        __DIR__.'/../config/acl.php' => config_path('acl.php'),
	        __DIR__.'/../views' => resource_path('views/vendor/acl'),
	    ]);

	    // run this to load the laravel-permission config file
	    // php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

	    // import the Spatie\Permission\Traits\HasRoles trait into the User model
	    // Add the role and permission middlewares
	}
}