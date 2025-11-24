@extends('admin.layouts.master')
@section('title', 'Admin Users')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

		<!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Add Admin User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form data-href="{{ route('users.store') }}" class="ajax-form in_page_ajax_form">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label" for="first_name">First Name<b class="text-danger">*</b></label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter first name" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="last_name">Last Name<b class="text-danger">*</b></label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter last name" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="email">Email<b class="text-danger">*</b></label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="role">Role<b class="text-danger">*</b></label>
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="password">Password<b class="text-danger">*</b></label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="password_confirmation">Password Confirmation<b class="text-danger">*</b></label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Enter password confirmation" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

		{{-- Update Modal --}}
		<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Update Admin User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="ajax-form in_page_ajax_form" id="updateForm">
                        @csrf
						@method('PUT')
                       	<div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label" for="edit_first_name">First Name<b class="text-danger">*</b></label>
                                    <input type="text" id="edit_first_name" name="first_name" class="form-control" placeholder="Enter first name" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="edit_last_name">Last Name<b class="text-danger">*</b></label>
                                    <input type="text" id="edit_last_name" name="last_name" class="form-control" placeholder="Enter last name" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="edit_email">Email<b class="text-danger">*</b></label>
                                    <input type="email" id="edit_email" name="email" class="form-control" placeholder="Enter email" required />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="edit_role">Role<b class="text-danger">*</b></label>
                                    <select name="role" id="edit_role" class="form-select" required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="edit_password">Password</label>
                                    <input type="password" id="edit_password" name="password" class="form-control" placeholder="Enter password" />
                                </div>
                                <div class="col-6">
                                    <label class="form-label" for="edit_password_confirmation">Password Confirmation</label>
                                    <input type="password" id="edit_password_confirmation" name="password_confirmation" class="form-control" placeholder="Enter password confirmation" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page-js')
    <script>
        $(document).ready(function() {
            const usersTable = new GenericDataTable({
                tableId: '#dataTable',
                ajaxUrl: "{{ route('users.index') }}",
                title: 'Admin Users',
                createModal: "#addModal",
                columns: [
                    { data: 'id' },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'first_name' },
                    { data: 'last_name' },
                    { data: 'email' },
                    { 
                        data: 'role',
                        render: function(data, type, full, meta) {
                            return `<span class="badge bg-label-info">${data}</span>`;
                        }
                    },
                    { data: 'action' }
                ],
                actionRenderer: function(data, type, full, meta) {
                    let updateUrl = "{{ route('users.update', ':id') }}".replace(':id', full.id);
                    let deleteUrl = "{{ route('users.destroy', ':id') }}".replace(':id', full.id);
                    
                    let btn = `
                        <li><a href="javascript:;" class="dropdown-item edit-record" data-href="${updateUrl}" data-first-name="${full.first_name}" data-last-name="${full.last_name}" data-email="${full.email}" data-role="${full.role}" data-bs-target="#updateModal" data-bs-toggle="modal">Edit</a></li>
                        <li><a href="javascript:;" class="dropdown-item text-danger deleteRecord" data-href="${deleteUrl}">Delete</a></li>
                    `;
                    
                    return `
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                ${btn}
                            </ul>
                        </div>
                    `;
                },
                searchPlaceholder: 'Search admin users...',
            });
        });

		$(document).on('click' , '.edit-record' , function (){
			$('#edit_first_name').val($(this).attr('data-first-name'));
			$('#edit_last_name').val($(this).attr('data-last-name'));
			$('#edit_email').val($(this).attr('data-email'));
			$('#edit_role').val($(this).attr('data-role')).trigger('change');

			$('#updateForm').attr('data-href', $(this).attr('data-href'));
			$('#updateModal').modal('show');
		});
    </script>
@endsection