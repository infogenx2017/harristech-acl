<?php

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-08-01 00:38:31
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-10-23 00:58:14
 */

return [
	// the layout to wrap our view in to make it blend with the application layout
	'layout' => 'layouts.modern.admin',

	// the name of the content block in the layout
	'layout_content' => 'content',

	// the name of the scripts section
	'layout_scripts' => 'scripts',

	// the middleware to use for the routes
	'middleware' => 'role:USER_MANAGER',

	// the view for the roles manage page 
	'roles_view' => 'acl::roles.index',

	// the view page for managing the permissions in a role
	'roles_permission_view' => 'acl::roles.permissions',

	// the view for the permissions page
	'permissions_view' => 'acl::permissions.index',

	// the default roles we want to make available to the role and permission seeder 
	'default_roles' => [
		'ROOT', 
		'ADMIN', 
		'USER', 
		'USER_MANAGER', 
		'SETTINGS_MANAGER',
	],

	// the default permissions we want to make available for 
	'default_permissions' => [],
];