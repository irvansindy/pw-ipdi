@extends('layouts.users.app', ['title' => $event->title])
@section('content')
    @include('layouts.users.navbar')
    <div class="container mx-auto px-4">
        <h2 class="d-flex justify-content-between align-items-center my-4">
            <span class="text-muted">Registrasi Acara/Event</span>
        </h2>
        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted" id="data_event_id" data-event_id="{{ $event->id }}"
                        data-event_hotel="{{ $event->hotel != null ? $event->hotel->id : null }}">{{ $event->title }}</span>
                    <span class="badge badge-secondary badge-pill"></span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">{{ $event->title }}</h6>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">{{ $event->date }}WIB</h6>
                        </div>
                    </li>
                    @if ($event->eventDetail != null)
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div class="row">
                                @foreach ($event->eventDetail as $event_detail)
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-check">
                                            <input class="form-check-input event-detail-radio" type="radio"
                                                value="{{ $event_detail->id }}" name="event_detail"
                                                id="detail_event_{{ $event_detail->id }}"
                                                data-price="{{ $event_detail->price }}">
                                            <label class="form-check-label text-muted"
                                                for="detail_event_{{ $event_detail->id }}">({{ $event_detail->name . ' - Rp.' . number_format($event_detail->price, 0, ',', '.') }})
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if ($event->hotel != null)
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="event_hotel"
                                        data-price="{{ $event->hotel->price }}">
                                    <label class="form-check-label text-muted" for="event_hotel">
                                        Include Hotel
                                        <br />({{ $event->hotel->name . ' - Rp.' . number_format($event->hotel->price, 0, ',', '.') }})
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 my-1">
                                        <input type="number" class="form-control" name="create_room_hotel"
                                            id="create_room_hotel" placeholder="jumlah kamar" style="width: 50% !important;"
                                            required />
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 my-1">
                                        <input type="number" class="form-control" name="create_total_night"
                                            id="create_total_night" placeholder="jumlah Malam"
                                            style="width: 50% !important;" required />
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Harga</h6>
                            <small class="text-muted">peranggota</small>
                        </div>
                        {{-- <span class="text-muted" id="data_event_price" data-price="{{ $event->price }}">Rp.{{ number_format($event->price, 0, ',', '.') }}</span> --}}
                        <span class="text-muted" id="data_event_price" data-price="{{ (int) $event->price }}">
                            Rp.{{ number_format((float) $event->price, 0, ',', '.') }}
                        </span>

                    </li>
                </ul>
                <h4 class="d-flex justify-content-between align-items-center mb-3">Payment</h4>
                <div class="mb-4">
                    <h6 class="text-gray-600">No. Rekening:
                        {{ $bank->name . ' : ' . $bank->account_number . ' - ' . $bank->account_name }}</h6>
                </div>
                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" id="submit_regis_event" type="button">Registrasi</button>
            </div>
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Data Anggota</h4>
                <form class="needs-validation" novalidate="">
                    <div class="mb-3">
                        <label for="username">Nama Lengkap</label>
                        <div class="input-group">
                            <input type="hidden" id="user_id_for_event" value="{{ auth()->user()->id }}">
                            <input type="text" class="form-control" id="username" placeholder="Username" required=""
                                value="{{ auth()->user()->username }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com"
                            value="{{ auth()->user()->email }}">
                    </div>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="1234 Main St" required=""
                            value="{{ auth()->user()->address }}">
                    </div>

                    <div class="mb-3">
                        <label for="nira_ppni">NIRA PPNI</label>
                        <input type="text" class="form-control" id="nira_ppni" placeholder="" required=""
                            value="{{ auth()->user()->nira_ppni }}">
                    </div>

                    <div class="mb-3">
                        <label for="nira_ipdi">NIRA IPDI</label>
                        <input type="text" class="form-control" id="nira_ipdi" placeholder="" required=""
                            value="{{ auth()->user()->nira_ipdi }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    @include('pages.user.event.detail_event_js')
@endpush
