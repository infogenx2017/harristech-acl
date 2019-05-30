<?php 
	$pageTitle = "Roles Management";
?>

@extends( config('acl.layout') )

@push('crumbs')
    <li><a href="{{ route('acl.roles') }}">Roles</a></li>
    <li>Manage</li>
@endpush

@section( config('acl.layout_content') )
	<div class="row">
		<div class="col-lg-7">
			<div class="panel panel-blue">
				<div class="panel-body">
					<div class="btn-group">
						<a href="{{ route('acl.permissions') }}" class="btn btn-primary btn-xs">
							<i class="fa fa-th-list"></i> Manage Available Permissions
						</a> 
					</div>
				</div>
			</div>

			<section class="panel">
			    <header class="panel-heading">
			    	<div class="row">
			    		<div class="col-lg-6"><i class="icon-briefcase"></i> Available Roles</div>
			    	</div>
			    </header>
			    @if( ! empty( $roles ) && $roles->count() )
			    	<div class="table-responsive">
			    		<table class="table table-hover">
			    			<thead>
			    				<tr>
			    					<th>Name</th>
			    					<th class="twenty">Permissions</th>
			    					<th class="forty">Action</th>
			    				</tr>
			    			</thead>
			    			<tbody>
			    				@foreach( $roles as $r )
			    					<tr>
			    						<th>{{ $r->name }}</th>
			    						<td>
			    							{{ $r->permissions->count() }} 
			    							{{ $r->permissions->count() > 1 ? 'Permissions' : 'Permission' }}
			    						</td>
			    						<td>
			    							<a href="?r_id={{ $r->id }}" class="btn btn-primary btn-xs" title="Edit Role '{{ $r->name }}'">
			    								<i class="fa fa-edit"></i> Edit
			    							</a>
			    							<a href="{{ route('acl.roles.permissions', ['role_id' => $r->id]) }}" class="btn btn-info btn-xs" title="Manage Permission For Role '{{ $r->name }}'">
			    								<i class="fa fa-briefcase"></i> Edit Permissions
			    							</a>
			    							<a href="{{ route('acl.roles.delete', ['id' => $r->id]) }}" class="btn btn-danger btn-xs confirm" title="Delete Role '{{ $r->name }}'">
			    								<i class="fa fa-trash-o"></i>
			    							</a>
			    						</td>
			    					</tr>
			    				@endforeach
			    			</tbody>
			    		</table>

			    	</div>
		    		<div class="text-center">{{ $roles->links() }}</div>
			    @else
			    	<div class="panel-body">
			    		<div class="text-center">
			    			<h3 class="text-danger">No role to display</h3>
			    		</div>
			    	</div>
			    @endif
			    
			</section>
		</div>

    	<div class="col-lg-4">
    		<section class="panel">
    		    <header class="panel-heading">
    		        <i class="fa fa-plus-circle"></i> {{ $edit ? 'Edit Role' : 'Add A Role' }}
    		    </header>
    		    <div class="panel-body">
    		        <form action="{{ $saveURL }}" method="POST" role="form" id="addRole">
	    		        {{ csrf_field() }}
    		        	<div class="form-group">
    		        		<label for="name">Name</label>
    		        		<input type="text" name="name" id="name" class="form-control" placeholder="Enter Name e.g. Editor" value="{{ $role->name or old('name') }}" required="required" onkeyup="this.value = this.value.toUpperCase()" />
    		        		<div class="text-danger">{{ $errors->first('name') }}</div>
    		        	</div>
    		        
    		        	<div class="form-group">
    		        		<button type="submit" class="btn btn-primary btn-block confirm-form" data-message="{{ $edit ? 'Update Role' : 'Add Role' }}" data-form="addRole">
    		        			<i class="fa fa-save"></i> {{ $edit ? 'Update Role' : 'Add Role' }}
    		        		</button>
    		        	</div>
    		        </form>
    		    </div>
    		</section>
    	</div>
	</div>
@endsection