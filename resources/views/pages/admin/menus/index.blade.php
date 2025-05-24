@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Menu Management'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Menu Management'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>List Menu Permission</h6>
                            <button type="button" class="btn bg-gradient-primary ms-auto" id="for_create_role" data-bs-toggle="modal" data-bs-target="#formMenus">+ Menu</button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table align-items-center mb-0" id="menu_management" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Name
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Url
                                        </th>
                                        <th class="text-secondary opacity-7">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.menus.modal_menu')
@endsection
@push('js')
    @include('pages.admin.menus.menu_js')
@endpush