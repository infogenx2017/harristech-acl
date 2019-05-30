<?php

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-09-16 13:04:50
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-11-15 11:21:05
 * @path: /var/www/html/leonleslie/mowt/application/packages/harristech/acl/routes.php
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// everything should be in the web middleware so that we can have session and auth initialised for us
$routeData = [ 'middleware' => ['web'], 'prefix' => 'acl', 'namespace' => 'Harristech\ACL\Controllers' ];
// add the middleware if we have one
if ( ! empty( config('acl.middleware') ) ) {
	// we have the middleware
	$routeData['middleware'][] = config('acl.middleware');
}

Route::group( $routeData, function () {

	// display the roles management pages
	Route::group(['prefix' => '/roles'], function() {

	    // display the roles index page
	    Route::get('/', 'RolesController@getIndex')->name('acl.roles');

	    // save a new role into the system
	    Route::post('/add', 'RolesController@postAdd')->name('acl.roles.add');

	    // update a role
	    Route::post('/{role_id}/edit', 'RolesController@postEdit')->name('acl.roles.edit');
	   	
	   	// delete a role
	   	Route::get('/{role_id}/delete', 'RolesController@getDelete')->name('acl.roles.delete');

	   	// manage the permissions available for a role
	   	Route::get('/{role_id}/permissions', 'RolesController@getPermissions')->name('acl.roles.permissions');

	   	// save the permissions assigned to a role
	   	Route::post('/{role_id}/permissions', 'RolesController@postPermissions');
	});

	// display the permission management pages
	Route::group(['prefix' => '/permissions'], function() {

	    // display the permissions index page
	    Route::get('/', 'PermissionsController@getIndex')->name('acl.permissions');

	    // save a new role into the system
	    Route::post('/add', 'PermissionsController@postAdd')->name('acl.permissions.add');

	    // update a role
	    Route::post('/{role_id}/edit', 'PermissionsController@postEdit')->name('acl.permissions.edit');
	   	
	   	// delete a role
	   	Route::get('/{role_id}/delete', 'PermissionsController@getDelete')->name('acl.permissions.delete');
	});
});