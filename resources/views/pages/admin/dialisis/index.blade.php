@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Dialisis Management'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dialisis Management'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Daftar Dialisis</h6>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#dialisisModal">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dialisis as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info text-white me-2" data-bs-toggle="modal"
                                                    data-bs-target="#dialisisModal"
                                                    onclick="editDialisis({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('dialisis.destroy', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">Belum ada data dialisis.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dialisisModal" tabindex="-1" aria-labelledby="dialisisModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="dialisisForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="dialisisMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dialisisModalLabel">Tambah Dialisis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="dialisisName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="dialisisName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function editDialisis(id, name) {
            const form = document.getElementById('dialisisForm');
            form.action = `{{ url('/dialisis') }}/${id}`;
            document.getElementById('dialisisMethod').value = 'PUT';
            document.getElementById('dialisisModalLabel').textContent = 'Ubah Dialisis';
            document.getElementById('dialisisName').value = name;
        }

        document.getElementById('dialisisModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('dialisisForm');
            form.action = `{{ route('dialisis.store') }}`;
            form.reset();
            document.getElementById('dialisisMethod').value = 'POST';
            document.getElementById('dialisisModalLabel').textContent = 'Tambah Dialisis';
        });
    </script>
@endpush
