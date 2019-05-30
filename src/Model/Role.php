<?php

namespace Harristech\ACL\Model;

use Spatie\Permission\Contracts\Role as RoleContract;

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-08-01 00:38:31
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-10-22 23:54:21
 */

class Role extends \Spatie\Permission\Models\Role implements RoleContract 
{
	/**
	 * Return some default roles
	 * @return array 
	 */
	public static function defaultRoles()
	{
		return config('acl.default_roles', []);
	}

	/**
	 * Get the roles for use on the role management page
	 *
	 * @access public
	 * @param array $filters 
	 * @return array 
	 */
	public function getForManage( $filters = [] )
	{
		$query = self::orderBy('created_at', 'DESC');

		if ( ! empty( $filters['name'] ) ) $query->where('name', 'LIKE', "%{$filters['name']}%");

		return $query->paginate( 20 );
	}
}