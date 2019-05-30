<?php

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-08-01 00:38:31
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-09-16 20:05:46
 */

namespace Harristech\ACL\Controllers;

use Harristech\ACL\Model\Permission;
use Harristech\ACL\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;

class RolesController extends Controller 
{
	/**
	 * Controller constructor
	 *
	 * @param Request $request 
	 * @param Role $roles 
	 * @param Permissions $permissions
	 * @return void 
	 */
	public function __construct( Request $request, Role $roles, Permission $permissions )
	{
		$this->middleware(['auth']);
		
		$this->request = $request;
		$this->roles = $roles;
		$this->permissions = $permissions;
	}

	/**
	 * Display the page listing the available roles in the system with the form to create new ones 
	 *
	 * @access public
	 * @return Response 
	 */
	public function getIndex()
	{
		// get the roles in the system
		$roles = $this->roles->getForManage( $this->request->all() );

		$edit = false;
		$role = null;
		$saveURL = route('acl.roles.add');

		if ( $this->request->has('r_id') ) {
			// get the role
			$role = $this->roles->with('permissions')->find( $this->request->r_id );
			if ( ! $role ) {
				// we do not have the role
				return redirect()->route('acl.roles')->with('error', 'That role does not exist!');
			}

			$edit = true;
			$saveURL = route('acl.roles.edit',['r_id' => $role->id]);
		}

		$roles_view = config('acl.roles_view');

		return view($roles_view, compact('roles', 'role', 'edit', 'saveURL'));
	}

	/**
	 * Save a new role
	 *
	 * @access public
	 * @return Response 
	 */
	public function postAdd()
	{
		$this->validate( $this->request, [
			'name' => 'required|unique:roles'
		]);

		$created = $this->roles->create( $this->request->only('name') );

		if ( $created ) {
			// role created
			return back()->with('success', 'Role successfully added!');
		} else return back()->with('error', 'Failed to save the role')->withInput();
	}

	/**
	 * Update a role
	 *
	 * @access public
	 * @param integer $role_id 
	 * @return Response 
	 */
	public function postEdit( $role_id = null )
	{
		if ( empty( $role_id ) ) return back()->with('error', 'Incomplete Request')->withInput();

		// get the role
		$role = $this->roles->find( $role_id );

		if ( ! $role ) return back()->with('error', 'Role not found!')->withInput();

		// validation
		$this->validate( $this->request, [
			'name' => 'required|unique:roles,name,'.$role_id
		]);

		if ( $role->update( $this->request->only('name') ) ) {
			// role updated
			return redirect()->route('acl.roles')->with('success', 'Role updated!');
		} else {
			return back()->with('error', 'Failed to update the role. Make sure you actually changed something!')->withInput();
		}
	}

	/**
	 * Delete a role
	 *
	 * @access public
	 * @param integer $role_id 
	 * @return Response 
	 */
	public function getDelete( $role_id = null )
	{
		if ( empty( $role_id ) ) return back()->with('error', 'Incomplete Request')->withInput();

		// get the role
		$role = $this->roles->find( $role_id );

		if ( ! $role ) return back()->with('error', 'Role not found!')->withInput();

		if ( $role->delete() ) {
			// role deleted
			return back()->with('success', 'Role deleted!');
		} else return back()->with('error', 'Failed to delete role!');
	}

	/**
	 * Display the permissions page for a role
	 *
	 * @access public
	 * @param integer $role_id 
	 * @return Response 
	 */
	public function getPermissions( $role_id = null )
	{
		if ( empty( $role_id ) ) return back()->with('error', 'Incomplete Request')->withInput();

		// get the role
		$role = $this->roles->with('permissions')->find( $role_id );

		if ( ! $role ) return back()->with('error', 'Role not found!')->withInput();

		// get all available permissions
		$permissions = $this->permissions->all(['id', 'name']);

		$indexed_perms = [];
		// group the permissions into some sort of group
		// foreach ( $permissions as $p ) {
		// 	$exp = explode( '.', $p->name );
		// 	$indexed_perms[ $exp[0] ][] = [ $p ];
		// }

		// dd($indexed_perms);

		$roles_permission_view = config('acl.roles_permission_view');

		return view($roles_permission_view, compact('role', 'permissions', 'indexed_perms'));
	}

	/**
	 * Save the selected permissions for a role 
	 *
	 * @access public
	 * @param integer $role_id 
	 * @return Response 
	 */
	public function postPermissions( $role_id = null )
	{
		if ( empty( $role_id ) ) return back()->with('error', 'Incomplete Request')->withInput();

		// get the role
		$role = $this->roles->with('permissions')->find( $role_id );

		if ( ! $role ) return back()->with('error', 'Role not found!')->withInput();

		$this->validate( $this->request, [
			'permissions' => 'required|array'
		]);

		$permissions = $this->request->permissions;

		// loop through the permissions and grant them to the role
		app('db')->beginTransaction();

		try {
			// first revoke all permissions associated with this role
			$all_p = $this->permissions->all();
			foreach ( $all_p as $perm ) {
				$role->revokePermissionTo( $perm );
			}

			$count = 0;
			foreach ( $permissions as $perm ) {
				// get the permission from db
				$permission = $this->permissions->find( $perm );
				
				if ( $permission ) {
					$role->givePermissionTo( $permission );
					++$count;
				}
			}

			// all done
			app('db')->commit();

			return back()->with('success', "{$count} permission(s) added to role!");
		} catch ( Exception $e ) {
			info( $e );
			app('db')->rollback();

			return back()->with('error', 'There was an error while processing your submission!')->withInput();
		}
	}
}