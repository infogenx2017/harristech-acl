<?php

/**
 * @author: Peter Harris
 * @email: peteharris401@gmail.com
 * @Date:   2017-08-01 00:38:31
 * @Last Modified by:   Farayola Oladele
 * @Last Modified time: 2017-09-16 17:56:39
 */

namespace Harristech\ACL\Controllers;

use Harristech\ACL\Model\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller 
{
	/**
	 * Controller constructor
	 *
	 * @param Request $request 
	 * @param Permission $permissions
	 * @return void 
	 */
	public function __construct( Request $request, Permission $permissions )
	{
		$this->middleware(['auth']);

		$this->request = $request;
		$this->permissions = $permissions;
	}

	/**
	 * Display the page listing the available permissions in the system with the form to create new ones 
	 *
	 * @access public
	 * @return Response 
	 */
	public function getIndex()
	{
		// get the permissions in the system
		$permissions = $this->permissions->getForManage( $this->request->all() );

		$edit = false;
		$permission = null;
		$saveURL = route('acl.permissions.add');

		if ( $this->request->has('r_id') ) {
			// get the permission
			$permission = $this->permissions->find( $this->request->r_id );
			if ( ! $permission ) {
				// we do not have the permission
				return redirect()->route('acl.permissions')->with('error', 'That permission does not exist!');
			}

			$edit = true;
			$saveURL = route('acl.permissions.edit',['r_id' => $permission->id]);
		}

		$permissions_view = config('acl.permissions_view');

		return view($permissions_view, compact('permissions', 'permission', 'edit', 'saveURL'));
	}

	/**
	 * Save a new permission
	 *
	 * @access public
	 * @return Response 
	 */
	public function postAdd()
	{
		$this->validate( $this->request, [
			'name' => 'required|unique:permissions'
		]);

		$created = $this->permissions->create( $this->request->only('name') );

		if ( $created ) {
			// permission created
			return back()->with('success', 'Permission successfully added!');
		} else return back()->with('error', 'Failed to save the permission')->withInput();
	}

	/**
	 * Update a permission
	 *
	 * @access public
	 * @param integer $permission_id 
	 * @return Response 
	 */
	public function postEdit( $permission_id = null )
	{
		if ( empty( $permission_id ) ) return back()->with('error', 'Incomplete Request')->withInput();

		// get the permission
		$permission = $this->permissions->find( $permission_id );

		if ( ! $permission ) return back()->with('error', 'Permission not found!')->withInput();

		// validation
		$this->validate( $this->request, [
			'name' => 'required|unique:permissions,name,'.$permission_id
		]);

		if ( $permission->update( $this->request->only('name') ) ) {
			// permission updated
			return redirect()->route('acl.permissions')->with('success', 'Permission updated!');
		} else {
			return back()->with('error', 'Failed to update the permission. Make sure you actually changed something!')->withInput();
		}
	}

	/**
	 * Delete a permission
	 *
	 * @access public
	 * @param integer $permission_id 
	 * @return Response 
	 */
	public function getDelete( $permission_id = null )
	{
		if ( empty( $permission_id ) ) return back()->with('error', 'Incomplete Request')->withInput();

		// get the permission
		$permission = $this->permissions->find( $permission_id );

		if ( ! $permission ) return back()->with('error', 'Permission not found!')->withInput();

		if ( $permission->delete() ) {
			// permission deleted
			return back()->with('success', 'Permission deleted!');
		} else return back()->with('error', 'Failed to delete permission!');
	}
}