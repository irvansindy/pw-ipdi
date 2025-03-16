<script>
    $(document).ready(function() {
        var role;
        let currentYear = new Date().getFullYear(); // Tahun saat ini

        // Fungsi untuk mengambil transaksi berdasarkan ID tabel dan tahun
        function fetchTransactions(tableId, year) {
            $.ajax({
                url: '{{ route('transaction-fetch') }}',
                method: 'GET',
                data: {
                    year: year
                }, // Kirim parameter tahun ke API
                success: function(response) {
                    let transactions = response.data[tableId]; // Ambil data transaksi sesuai tabel
                    let role = response.data.role;

                    let $table = $('#' + tableId);
                    $table.DataTable().clear().destroy();
                    $table.find('tbody').empty();

                    transactions.forEach((transaction, index) => {
                        let actions =
                            `<button type="button" data-transaction_id="${transaction.id}" class="btn btn-outline-info btn-sm detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button>`;

                        if (role === 'admin') {
                            actions +=
                                `<button type="button" data-transaction_id="${transaction.id}" data-transaction_number="${transaction.number}" class="btn btn-outline-danger btn-sm delete_transaction" data-bs-toggle="modal" data-bs-target="#form_delete_transaction">Delete</button>`;
                        }
                        if (transaction.status === 'success') {
                            actions +=
                                `<button type="button" data-transaction_id="${transaction.id}" data-transaction_number="${transaction.number}" class="btn btn-outline-success btn-sm check_attendance_transaction" data-bs-toggle="modal" data-bs-target="#detailAttendance">Check Attendance</button>`;
                        }

                        $table.find('tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${transaction.number ?? 'not set'}</td>
                            <td>${transaction.event?.name ?? 'not set'}</td>
                            <td>${transaction.user?.username ?? 'not set'}</td>
                            <td>${transaction.status ?? 'not set'}</td>
                            <td>${actions}</td>
                        </tr>
                    `);
                    });

                    $table.DataTable();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }

        // Daftar ID tabel
        let tableIds = [
            'transaction_jnns_management',
            'transaction_resertifikasi_management',
            'transaction_seminar_management',
            'transaction_wkd_management',
            'transaction_kongres_wilayah_management'
        ];

        // Tambahkan dropdown filter tahun di setiap tabel dan ambil data awal
        tableIds.forEach(tableId => {
            let tabName = tableId.replace('transaction_', '').replace('_management', '');
            let $filterContainer = $('#' + tabName + '_management_filter_container'); 
            let $yearFilter = $('<select class="form-select year-filter w-40" id="'+tableId+'_year_filter"></select>');

            for (let year = currentYear; year >= currentYear - 10; year--) {
                $yearFilter.append(`<option value="${year}">${year}</option>`);
            }

            $filterContainer.append($yearFilter);

            // Panggil fetch pertama kali dengan tahun sekarang
            fetchTransactions(tableId, currentYear);

            // Event listener untuk filter tahun pada tabel masing-masing
            $yearFilter.change(function() {
                let selectedYear = $(this).val();
                fetchTransactions(tableId, selectedYear);
            });
        });

        // Event listener untuk tombol Refresh
        $('.refresh-data').click(function () {
            let tabName = $(this).data('table');
            let selectedYear = $('#transaction_' + tabName + '_management_year_filter').val();
            fetchTransactions('transaction_' + tabName + '_management', selectedYear);
        });

        // Event listener untuk tombol Export Excel
        $('.export-excel').click(function (e) {
            e.preventDefault();
            let tabName = $(this).data('table');
            let selectedYear = $('#transaction_' + tabName + '_management_year_filter').val();

            // Redirect ke route export dengan parameter tahun dan tab aktif
            let exportUrl = "{{ route('transaction-export-excel') }}?year=" + selectedYear + "&category=" + tabName;
            window.location.href = exportUrl;
        });

        $(document).on('click', '.detail_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            // alert(transaction_id)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('transaction-detail') }}',
                type: 'get',
                data: {
                    id: transaction_id
                },
                dataType: 'json',
                success: function(res) {
                    $('#form_update_transaction')[0].reset()
                    $('#update_transaction_id').val(res.data.id)
                    $('#detailTransactionLabel').html('Detail Transaction ' + res.data
                        .number)
                    $('#update_transaction_event').html(res.data.event.name)
                    $('#update_transaction_user').html(res.data.user.username)
                    $('#update_transaction_date').html(res.data.date)
                    $('#update_transaction_status').html(res.data.status)
                    $('#update_transaction_proof_of_payment').empty()
                    $('#update_transaction_proof_of_payment').append(`
                        <a href="{{ asset('uploads/bukti_transaksi/${res.data.proof_of_payment}') }}" target="_blank">Bukti Transaksi</a>
                    `)

                    let numbering_user = 1
                    $('#list_data_pererta').empty()
                    $.each(res.data.transaction_detail, function(i, peserta) {
                        $('#list_data_pererta').append(`
                            <tr>
                                <td>${numbering_user++}</td>
                                <td>${peserta.user.username}</td>
                            </tr>
                        `)
                    })
                },
                error: function(xhr, status, error) {
                    $('#detailTransaction').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '#update_transaction', function(e) {
            e.preventDefault()
            let update_transaction_id = $('#update_transaction_id').val()
            // alert(update_transaction_id)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('transaction-approval') }}',
                type: 'POST',
                data: {
                    update_transaction_id: update_transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detailTransaction').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal memuat data, silahkan hubungi admin.',
                    })
                }
            })
        })

        $(document).on('click', '.delete_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            let transaction_number = $(this).data('transaction_number')
            $('#delete_transaction_id').empty()
            $('#delete_transaction_number').empty()
            $('#delete_transaction_id').val(transaction_id)
            $('#delete_transaction_number').html('Yakin ingin menghapus transaksi dengan nomor ' +
                transaction_number)
            $('#confirm_delete_transaction').attr('data-transaction_id', transaction_id)
            $('#confirm_delete_transaction').attr('data-transaction_number', transaction_number)
        })

        $(document).on('click', '#confirm_delete_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            let delete_transaction_id = $('#delete_transaction_id').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('delete-transaction') }}',
                type: 'POST',
                data: {
                    transaction_id: transaction_id,
                    delete_transaction_id: delete_transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#form_delete_transaction').modal('hide')
                    $('#transaction_management').DataTable().ajax.reload();
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    console.log(response_error)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal menghapus data, silahkan hubungi admin.',
                    })
                }
            })
        })

        $(document).on('keyup', '#myInput', function(e) {
            if (tex !== "" && e.keyCode === 13) {
                $('#myInput').val(tex).focus();
            }
        })

        $(document).on('click', '.check_attendance_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('list-audience') }}',
                type: 'get',
                data: {
                    transaction_id: transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_user_attendance').DataTable().clear().destroy();
                    $('#list_user_attendance tbody').empty();
                    let button_confirm = ''
                    res.data.forEach((audience, index) => {
                        // alert(typeof(audience.is_attendanced))
                        button_confirm = audience.is_attendanced != 0 ? '' :
                            '<button type="button" data-userid="' + audience.user
                            .id + '" data-transid="' + audience.id +
                            '" class="btn btn-outline-info btn-sm mt-2 confirm_attendance">Confirm</button>'
                        $('#list_user_attendance tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${audience.user.username != null ? audience.user.username : 'not set'}</td>
                                <td>${audience.transactions != null ? audience.transactions.number : 'not set'}</td>
                                <td>${audience.user.nira_ipdi != null ? audience.user.nira_ipdi : 'not set'}</td>
                                <td>${audience.is_attendanced != 0 ? 'Hadir' : 'Belum/Tidak Hadir'}</td>
                                <td>
                                    ${button_confirm}
                                </td>
                            </tr>
                        `)
                    })
                    $('#list_user_attendance').DataTable()
                },
                error: function(xhr, status, error) {
                    $('#detailAttendance').modal('toggle')
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '.confirm_attendance', function() {
            let transid = $(this).data('transid')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update-audience') }}',
                type: 'post',
                data: {
                    transid: transid
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detailAttendance').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal konfirmasi kehadiran, silahkan hubungi admin.',
                    })
                }
            })
        })
    })
    $('.form-select, .year-filter').removeClass('w-auto')
        $('.form-select, .year-filter').addClass('w-10')
        $('.form-select, .year-filter').select2()
</script>
