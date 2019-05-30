<?php

namespace Harristech\ACL\Model;

use Spatie\Permission\Contracts\Permission as PermissionContract;

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-08-01 00:38:31
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-10-23 01:17:44
 */

class Permission extends \Spatie\Permission\Models\Permission implements PermissionContract 
{
	/**
	 * Return some default permissions
	 * @return array [description]
	 */
	public static function defaultPermissions()
	{
		return config('acl.default_permissions', []);
	}

	/**
	 * Get the permissions for use on the permission management page
	 *
	 * @access public
	 * @param array $filters 
	 * @return array 
	 */
	public function getForManage( $filters = [] )
	{
		$query = self::withCount('roles')->orderBy('created_at', 'DESC');

		if ( ! empty( $filters['name'] ) ) $query->where('name', 'LIKE', "%{$filters['name']}%");

		return $query->paginate( 20 );
	}
}