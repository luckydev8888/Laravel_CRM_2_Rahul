@extends('layouts.app')

@section('custom_styles')
<style>
    .table th {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    <!--begin::Index-->
    <div class="card card-page">
        <!--begin::Card body-->
        <div class="card-body">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Add</button>
            <div class="row">
                <table class="table table-bordered table-striped table-hover datatable datatable-User">
                    <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Country
                            </th>
                            <th>
                                Role
                            </th>
                            <th>
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                        <th>{{ $user->id }}</th>
                        <th><input type="text" id="name{{$user->id}}" class="form-control" value="{{ $user->name }}"></th>
                        <th><input type="text" id="email{{$user->id}}" class="form-control" value="{{ $user->email }}"></th>
                        <th>
                            <select name="country" id="country{{$user->id}}" class="form-control">
                                <option value=""></option>
                                <option value="SPAIN" {{$user->country == 'SPAIN' ? 'selected' : ''}}>SPAIN</option>
                                <option value="PORTUGAL" {{$user->country == 'PORTUGAL' ? 'selected' : ''}}>PORTUGAL</option>
                                <option value="USA" {{$user->country == 'USA' ? 'selected' : ''}}>USA</option>
                                <option value="CANARIAS" {{$user->country == 'CANARIAS' ? 'selected' : ''}}>CANARIAS</option>
                            </select>
                        </th>
                        <th>
                            <select name="is_admin" id="is_admin{{$user->id}}" class="form-control" value="{{$user->is_admin}}">
                                <option value="0" {{$user->is_admin ? '' : 'selected'}}>User</option>
                                <option value="1" {{$user->is_admin ? 'selected' : ''}}>Admin</option>
                            </select>
                        </th>
                        <th>
                            <button class="btn p-2" onclick="onSave({{$user->id}})"><i class="fa fa-save"></i></button>
                            <button class="btn p-2" onclick="onDelete({{$user->id}})"><i class="fa fa-trash"></i></button></th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Index-->
</div>
<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="name_new">Name: </label>
            <input type="text" id="name_new" class="form-control">
        </div>
        <div class="form-group">
            <label for="email_new">Email: </label>
            <input type="text" id="email_new" class="form-control">
        </div>
        <div class="form-group">
            <label for="password_new">Password: </label>
            <input type="password" id="password_new" class="form-control">
        </div>
        <div class="form-group">
            <label for="country_new">Country: </label>
            <select name="country" id="country_new" class="form-control">
                <option value=""></option>
                <option value="SPAIN">SPAIN</option>
                <option value="PORTUGAL">PORTUGAL</option>
                <option value="USA">USA</option>
                <option value="CANARIAS">CANARIAS</option>
            </select>
        </div>
        <div class="form-group">
            <label for="is_admin_new">Role: </label>
            <select name="is_admin" id="is_admin_new" class="form-control">
                <option value="0">User</option>
                <option value="1">Admin</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="onAdd()">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_scripts')
<script>
    function onSave(id) {
        $.ajax({
            url: '{{url('/users/save')}}',
            type: 'post',
            data: {
                _token: '{{csrf_token()}}',
                id: id,
                data: {
                    name: $('#name' + id).val(),
                    email: $('#email' + id).val(),
                    country: $('#country' + id).val(),
                    is_admin: $('#is_admin' + id).val()
                }
            }
        })
    }
    function onDelete(id) {
        $.ajax({
            url: '{{url('/users/delete')}}',
            type: 'post',
            data: {
                _token: '{{csrf_token()}}',
                id: id
            },
            success: function() {
                window.location = window.location;
            }
        })
    }
    function onAdd() {
        $.ajax({
            url: '{{url('/users/add')}}',
            type: 'post',
            data: {
                _token: '{{csrf_token()}}',
                data: {
                    name: $('#name_new').val(),
                    email: $('#email_new').val(),
                    password: $('#password_new').val(),
                    country: $('#country_new').val(),
                    is_admin: $('#is_admin_new').val()
                }
            },
            success: function() {
                window.location = window.location;
            }
        })
    }
</script>
@endsection
