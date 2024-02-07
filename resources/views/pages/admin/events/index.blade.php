@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Event Management'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>Event Management</h6>
                            <button type="button" class="btn bg-gradient-primary ms-auto" data-bs-toggle="modal" data-bs-target="#formCreateEvent">+ Event</button>

                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-2">
                            <table class="table align-items-center mb-0" id="event_management">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Name 
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Title
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Date
                                        </th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($events as $key => $event)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $no++ }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-primary text-xs font-weight-bold">{{ $event->name }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-primary text-xs font-weight-bold">{{ $event->title }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-primary text-xs font-weight-bold">{{ $event->date }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript::" class="text-info font-weight-bold text-xs mr-3"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                    {{-- @forelse ($events as $key => $event)
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <span class="text-primary text-center text-xs font-weight-bold">
                                                    No Data
                                                </span>
                                            </td>
                                        </tr>
                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.events.create')
@endsection
@push('js')
    @include('pages.admin.events.event_js')
@endpush