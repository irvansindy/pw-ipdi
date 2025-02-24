@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Event Management'])

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Checkout Tiket Event</h2>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Informasi Event -->
        <div class="mb-4">
            {{-- <h3 class="text-xl font-semibold">{{ $event->name != null ? 'ada' : 'gada' }}</h3>
            <p class="text-gray-600">Tanggal: {{ $event->date != null ? 'ada' : 'gada' }}</p>
            <p class="text-gray-600">Lokasi: {{ $event->location != null ? 'ada' : 'gada' }}</p>
            <p class="text-gray-600">Harga: Rp{{ number_format($event->price, 0, ',', '.') }}</p> --}}
        </div>
        
        <!-- Formulir Checkout -->
        <form action="" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Tiket</label>
                <input type="number" name="quantity" min="1" class="w-full p-2 border rounded-lg" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Bayar Sekarang</button>
        </form>
    </div>
</div>
@endsection
