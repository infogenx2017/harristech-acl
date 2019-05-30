<?php 
	$pageTitle = "Permission Management";
?>

@extends( config('acl.layout') )

@section( config('acl.layout_content') )
	<div class="row">
		<div class="col-lg-7">
			<div class="panel panel-blue">
				<div class="panel-body">
					<div class="btn-group">
						<a href="{{ route('acl.roles') }}" class="btn btn-primary btn-xs">
							<i class="fa fa-arrow-left"></i> Back To Roles
						</a>
					</div>
				</div>
			</div>

			<section class="panel">
			    <header class="panel-heading">
			    	<div class="row">
			    		<div class="col-lg-6"><i class="icon-key"></i> Available Permissions</div>
			    	</div>
			    </header>
			    @if( ! empty( $permissions ) && $permissions->count() )
			    	<div class="table-responsive">
			    		<table class="table table-hover">
			    			<thead>
			    				<tr>
			    					<th>Name</th>
			    					<th class="twenty">Roles In Use</th>
			    					<th class="thirty">Action</th>
			    				</tr>
			    			</thead>
			    			<tbody>
			    				@foreach( $permissions as $p )
			    					<tr>
			    						<td>{{ $p->name }}</td>
			    						<td>{{ $p->roles_count }}</td>
			    						<td>
			    							<a href="?r_id={{ $p->id }}" class="btn btn-primary btn-xs" title="Edit Role '{{ $p->name }}'">
			    								<i class="fa fa-edit"></i> Edit
			    							</a>
			    							<a href="{{ route('acl.permissions.delete', ['id' => $p->id]) }}" class="btn btn-danger btn-xs confirm" title="Delete Permission '{{ $p->name }}'">
			    								<i class="fa fa-trash-o"></i>
			    							</a>
			    						</td>
			    					</tr>
			    				@endforeach
			    			</tbody>
			    		</table>

			    	</div>
		    		<div class="text-center">{{ $permissions->links() }}</div>
			    @else
			    	<div class="panel-body">
			    		<div class="text-center">
			    			<h3 class="text-danger">No permission to display</h3>
			    		</div>
			    	</div>
			    @endif
			    
			</section>
		</div>

    	<div class="col-lg-4">
    		<section class="panel">
    		    <header class="panel-heading">
    		        <i class="fa fa-plus-circle"></i> {{ $edit ? 'Edit Permission' : 'Add A Permission' }}
    		    </header>
    		    <div class="panel-body">
    		        <form action="{{ $saveURL }}" method="POST" role="form" id="addPermission">
	    		        {{ csrf_field() }}
    		        	<div class="form-group">
    		        		<label for="name">Name</label>
    		        		<input type="text" name="name" id="name" class="form-control" placeholder="Enter Name e.g. location.add" value="{{ $permission->name or old('name') }}" required="required" onkeyup="this.value = this.value.toLowerCase()" />
    		        		<div class="text-danger">{{ $errors->first('name') }}</div>
    		        	</div>
    		        
    		        	<div class="form-group">
    		        		<button type="submit" class="btn btn-primary btn-block confirm-form" data-message="{{ $edit ? 'Update Permission' : 'Add Permission' }}" data-form="addPermission">
    		        			<i class="fa fa-save"></i> {{ $edit ? 'Update Permission' : 'Add Permission' }}
    		        		</button>
    		        	</div>
    		        </form>
    		    </div>
    		</section>
    	</div>
	</div>
@endsection