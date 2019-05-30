<?php 
	$pageTitle = "Permissions For Role '{$role->name}'";
?>

@extends( config('acl.layout') )

@section( config('acl.layout_content') )
	<div class="row">
		<div class="col-lg-5">
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
			    		<div class="col-lg-8">
			    			<i class="fa fa-briefcase"></i> {{ $pageTitle }}
			    		</div>
			    	</div>
			    </header>
			    <div class="panel-body">
			    	@if( $role->permissions->count() )
			    		<h4>{{ $role->permissions->count() }} permissions assigned to this role</h4>
			    		<hr />
			    		<div>
			    			{{-- @foreach( $role->permissions->chunk( 3 ) as $chunk )
			    				<div class="row" style="margin-bottom: 10px;">
			    					@foreach( $chunk as $perm )
			    						<div class="col-lg-4">
			    							<button type="button" class="btn btn-info btn-block">
			    								<span class="text-truncate">
				    								{{ $perm->name }}
			    								</span>
			    							</button>		
			    						</div>
			    					@endforeach
			    				</div> 
			    			@endforeach --}}
			    			@foreach($role->permissions as $perm)
		    					@if(strlen($perm->name) >= 15)
				    				<h4 style="display: inline-block;">
			    						{{ $perm->name }}, 
				    				</h4>
			    					@else
			    					<h3 style="display: inline-block;">
			    						{{ $perm->name }}, 
			    					</h3>
		    					@endif
			    			@endforeach
			    		</div>
			    	@else
			    		<div class="text-center">
			    			<h3 class="text-danger">No permission assigned to this role</h3>
			    		</div>
			    	@endif
			    </div>
			</section>
		</div>

		<div class="col-lg-7">
			<section class="panel">
			    <header class="panel-heading">
			        Manage Assigned Permissions
			    </header>
		       <form action="{{ route('acl.roles.permissions', ['role_id' => $role->id]) }}" method="POST" role="form" id="permsForm">
			       	{{ csrf_field() }}
				    <div class="panel-body scroll">
				       	<div class="form-group">
				       		<div class="row">
				       			<div class="col-lg-6">
				       				<label for="permissions">
				       					Permissions
				       				</label>
				       			</div>
				       			<div class="col-lg-6">
				       				<span class="pull-right">
				       					{{-- <input type="checkbox" name="checkall" onclick="toggle(this)" /> Select All --}}
				       				</span>
				       			</div>
				       		</div>

				       		{{-- <select name="permissions[]" id="permissions" class="form-control multiple" required="required" multiple>
				       			<option value="">:: Select Permission ::</option>
				       			@foreach( $permissions as $permission )
				       				<option @if( $role->permissions->contains( $permission ) ) selected="selected" @endif value="{{ $permission->id }}">{{ $permission->name }}</option>
				       			@endforeach
				       		</select> --}}

				       		@foreach( $permissions->chunk( 3 ) as $chunk )
				       			<div class="row">
				       				@foreach( $chunk as $permission )
				       					<div class="col-lg-4">
				       						<div class="checkbox">
				       							<label>
				       								<input 
				       									name="permissions[]" 
				       									id="permissions" 
				       									type="checkbox" 
				       									value="{{ $permission->id }}" 
				       									@if( $role->permissions->contains( $permission ) ) 
				       									checked @endif
				       									class="_permission_checkbox normal" 
				       								>
				       								@if(strlen($permission->name) >= 15)
				       									<small>{{ $permission->name }}</small>
				       								@else
				       									{{ $permission->name }}
				       								@endif
				       							</label>
				       						</div>
				       					</div>
				       				@endforeach
				       			</div>
				       		@endforeach
				       		<div class="text-danger">{{ $errors->first('permissions') }}</div>
				       	</div>

				       	<div>
				       		<h5 class="text-danger">
				       			Select new permissions and remove unwanted permissions.
				       		</h5>
				       	</div>
				    </div>

				    <div class="panel-footer">
				    	<div class="form-group">
				    		<button type="submit" class="btn btn-primary btn-block confirm-form" data-message="Save permissions for this role?" data-form="permsForm">
				    			<i class="fa fa-save"></i> Submit
				    		</button>
				    	</div>
				    </div>
		       </form>
			</section>
		</div>
	</div>
@endsection

@push( config('acl.layout_scripts') )
	<script type="text/javascript">
		function toggle(source) {
		    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
		    for (var i = 0; i < checkboxes.length; i++) {
		    	console.log(checkboxes[i])
		        if (checkboxes[i] != source)
		            checkboxes[i].checked = source.checked;
		    }
		}
	</script>
@endpush