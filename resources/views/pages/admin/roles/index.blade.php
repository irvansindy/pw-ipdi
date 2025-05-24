@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'User Role'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'User Role'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>Roles</h6>
                            <button type="button" class="btn bg-gradient-primary ms-auto" id="for_create_role" data-bs-toggle="modal" data-bs-target="#formCreateRole">+ Role</button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table align-items-center mb-0" id="role_management" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Role Name
                                        </th>
                                        {{-- <th class="text-secondary opacity-7"></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($roles as $key => $role)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $no++ }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-primary text-xs font-weight-bold">{{ $role->name }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="javascript::" class="text-info font-weight-bold text-xs mr-3"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit
                                            </a>
                                            <a href="javascript::" class="text-danger font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.roles.create')
@endsection
@push('js')
    @include('pages.admin.roles.roles_js')
@endpush