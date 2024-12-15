<script>
    $(document).ready(function() {
        // <button type="button" class="btn btn-primary btn-sm ms-auto">Save</button>
        $('#button_action_payment_iuran').empty()
        $('#button_action_payment_iuran').append(`
        <div class="ms-auto">
                <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm ms-auto mx-1">Cancel</a>
                <button type="button" class="btn bg-gradient-dark btn-sm ms-auto" id="generate_payment_iuran" data-iuran_id="">Payment</button>
            </div>
        `)
        
        // $(document).on('click', '#button_append_iuran', function() {
        //     $('.array-list-peserta-iuran').append(`
        //         <div class="row cls_data_array_iuran">
        //             <div class="col-md-6">
        //                 <div class="form-group">
        //                     <label for="example-text-input" class="form-control-label">Nama - Central Dialisis</label>
        //                     <select class="form-control nama_peserta_iuran_central_dialisis" name="nama_peserta_iuran_central_dialisis" id="" placeholder="Central Dialisis" style="width: 100%">
        //                         <option value="Irvan">Irvan</option>
        //                     </select>

        //                 </div>
        //             </div>
        //             <div class="col-md-auto">
        //                 <div class="form-group">
        //                     <label for="">Uang Iuran 20K</label>
        //                     <div class="form-check">
        //                         <input class="form-check-input iuran_20k" type="checkbox" value="20" name="iuran_20k[]" id="">
        //                         <label class="form-check-label label_iuran_20k" for="">
        //                             Uang Iuran 20K
        //                         </label>
        //                     </div>
        //                 </div>
        //             </div>
        //             <div class="col-md-auto">
        //                 <div class="form-group">
        //                     <label for="">Uang Iuran 50K</label>
        //                     <div class="form-check">
        //                         <input class="form-check-input iuran_50k" type="checkbox" value="20" name="iuran_50k[]" id="">
        //                         <label class="form-check-label label_iuran_50k" for="">
        //                             Uang Iuran 50K
        //                         </label>
        //                     </div>
        //                 </div>
        //             </div>
        //             <div class="col-md-auto">
        //                 <div class="form-group">
        //                     <button type="button" class="btn btn-danger mt-4 button_pop_iuran" id=""><i class="fa-solid fa-minus"></i></button>
        //                 </div>
        //             </div>
        //         </div>
        //     `)
        //     $.ajax({
        //         success:function(res) {
        //             $('.nama_peserta_iuran_central_dialisis')
        //         }
        //     })
        //     $('.central_dialisis_iuran').select2()
        //     updateIdIuran()
        // })

        // $(document).on('click', '.button_pop_iuran', function() {
        //     $(this).closest('.cls_data_array_iuran').remove()
        //     updateIdIuran()
        // })

        function updateIdIuran() {
            $('.cls_data_array_iuran').each(function(i) {
                const index = i + 1
                const id_select_nama_iuran = 'nama_peserta_iuran_central_dialisis_'+ index
                const id_iuran_20k = 'iuran_20k_'+ index
                const id_iuran_50k = 'iuran_50k_'+ index

                $(this).find('select[name="nama_peserta_iuran_central_dialisis"]').attr('id', id_select_nama_iuran);
                $(this).find('input[name="iuran_20k[]"]').attr('id', id_iuran_20k);
                $(this).find('input[name="iuran_50k[]"]').attr('id', id_iuran_50k);
                
                // Update label 'for' attributes to match the input IDs
                $(this).find('label.label_iuran_20k').attr('for', id_iuran_20k);
                $(this).find('label.label_iuran_50k').attr('for', id_iuran_50k);
            })
        }

        $(document).on('change', '#jumlah_peserta_iuran', function() {
            let jumlah_peserta_iuran = $('#jumlah_peserta_iuran').val()
            if (jumlah_peserta_iuran <= 0) {
                $('.array-list-peserta-iuran').empty()
                $('.array-list-peserta-iuran').append(`<p class="justify-content-center">Belum ada data</p>`)
                
            } else {
                // alert(jumlah_peserta_iuran)
                $('.array-list-peserta-iuran').empty()
                $.ajax({
                    // fetch-peserta
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: '{{ route("fetch-peserta") }}',
                    success: function(res) {
                        let peserta_iuran = res.data
                        for (let i = 0; i < jumlah_peserta_iuran; i++) {
                            $('.array-list-peserta-iuran').append(`
                                <div class="row cls_data_array_iuran">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_peserta_iuran_central_dialisis_${i}" class="form-control-label">Nama - Central Dialisis</label>
                                            <select class="form-control nama_peserta_iuran_central_dialisis" name="nama_peserta_iuran_central_dialisis" id="nama_peserta_iuran_central_dialisis_${i}" placeholder="Central Dialisis" style="width: 100%">
                                                <option value="Irvan">Irvan</option>
                                            </select>
    
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <label for="iuran_20k_${i}">Registrasi Anggota Baru</label>
                                            <div class="form-check">
                                                <input class="form-check-input iuran_20k" type="checkbox" value="20" name="iuran_20k[]" id="iuran_20k_${i}">
                                                <label class="form-check-label label_iuran_20k" for="">
                                                    20K
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <label for="">Iuran Anggota Pertahun</label>
                                            <div class="form-check">
                                                <input class="form-check-input iuran_50k" type="checkbox" value="20" name="iuran_50k[]" id="iuran_50k_${i}">
                                                <label class="form-check-label label_iuran_50k" for="">
                                                    50K
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <label for="tahun_pembayaran_iuran_${i}">Tahun Pembayaran</label>
                                            <input type="number" class="form-control" name="tahun_pembayaran_iuran[]" id="tahun_pembayaran_iuran_${i}">
                                        </div>
                                    </div>
                                </div>
                            `)
                            
                            $('#nama_peserta_iuran_central_dialisis_'+i).select2()
                        }
                        $('.nama_peserta_iuran_central_dialisis').empty()
                        $('.nama_peserta_iuran_central_dialisis').append(`<option value="">Pilih Anggota</option>`)
                        $.each(peserta_iuran, (i, peserta) => {
                            $('.nama_peserta_iuran_central_dialisis').append(`<option value="${peserta.id}">${peserta.username}</option>`)
                        })
                    }
                })
            }
        })
        // <button type="button" class="btn btn-danger mt-4 button_pop_iuran" id=""><i class="fa-solid fa-minus"></i></button>
        
        $(document).on('click', '#generate_payment_iuran', function(e) {
            e.preventDefault()
            // $('#form_create_data_iuran')[0].reset()
            let form_data = new FormData($('#form_create_data_iuran')[0])

            let nama_peserta_iuran_central_dialisis_values = [];
            let iuran_20_values = [];
            let iuran_50_values = [];

            form_data.delete('peserta[]');
            form_data.delete('iuran_20k[]');
            form_data.delete('iuran_50k[]');

            // Iterate through each select option with the class `nama_peserta_iuran_central_dialisis_values`
            $('.nama_peserta_iuran_central_dialisis').each(function(i) {
                let value = $(this).val()
                nama_peserta_iuran_central_dialisis_values[i] = value
                form_data.append('peserta[]', nama_peserta_iuran_central_dialisis_values[i]);
            })
            // Iterate through each checkbox with the class `iuran_20k`
            $('.iuran_20k').each(function (i) {
                let value = $(this).is(':checked') ? 20000 : 0;
                iuran_20_values[i] = value;
                form_data.append('iuran_20k[]', iuran_20_values[i]);
            });
            // Iterate through each checkbox with the class `iuran_50k`
            $('.iuran_50k').each(function (i) {
                let value = $(this).is(':checked') ? 50000 : 0;
                iuran_50_values[i] = value;
                form_data.append('iuran_50k[]', iuran_50_values[i]);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("generate-payment-iuran") }}',
                data: form_data,
                processData: false,   // Prevent jQuery from processing the data
                contentType: false,   // Prevent jQuery from setting content type
                cache: false,
                success: function(res) {
                    // $('#form_create_event').modal('hide')
                    $('.for-grand-total-payment-iuran').empty()
                    let grand_total = res.data != null ? res.data : 0
                    $('.for-grand-total-payment-iuran').append(`
                        <div class="d-flex ms-auto">RP.${grand_total},.</div>
                    `)
                    $('#val_grand_total_payment_iuran').val(grand_total)
                    $('#button_action_payment_iuran').empty()
                    // <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm ms-auto mx-1">Cancel</a>
                    $('#button_action_payment_iuran').append(`
                        <div class="ms-auto">
                            <button type="button" class="btn btn btn-danger btn-sm ms-auto" id="reset_payment_iuran" data-iuran_id="">Reset</button>
                            <button type="button" class="btn bg-gradient-primary btn-sm ms-auto" id="create_transaction_iuran" data-iuran_id="">Submit</button>
                        </div>
                        `)
                        
                },
            })
        })

        $(document).on('click', '#reset_payment_iuran', function() {
            $('.for-grand-total-payment-iuran').empty()
            $('#button_action_payment_iuran').empty()
            $('#button_action_payment_iuran').append(`
            <div class="ms-auto">
                    <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm ms-auto mx-1">Cancel</a>
                    <button type="button" class="btn bg-gradient-dark btn-sm ms-auto" id="generate_payment_iuran" data-iuran_id="">Payment</button>
                </div>
            `)
        })

        $(document).on('click', '#create_transaction_iuran', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            let iuran_type = $("input[name=iuran_type]").val();
            let form_data = new FormData($('#form_create_data_iuran')[0])

            let nama_peserta_iuran_central_dialisis_values = [];
            let iuran_20_values = [];
            let iuran_50_values = [];

            // form_data.delete('iuran_type');
            form_data.delete('peserta[]');
            form_data.delete('iuran_20k[]');
            form_data.delete('iuran_50k[]');
            
            // form_data.append('iuran_type', iuran_type);
            // Iterate through each select option with the class `nama_peserta_iuran_central_dialisis_values`
            $('.nama_peserta_iuran_central_dialisis').each(function(i) {
                let value = $(this).val()
                nama_peserta_iuran_central_dialisis_values[i] = value
                form_data.append('peserta[]', nama_peserta_iuran_central_dialisis_values[i]);
            })
            // Iterate through each checkbox with the class `iuran_20k`
            $('.iuran_20k').each(function (i) {
                let value = $(this).is(':checked') ? 20000 : 0;
                iuran_20_values[i] = value;
                form_data.append('iuran_20k[]', iuran_20_values[i]);
            });
            // Iterate through each checkbox with the class `iuran_50k`
            $('.iuran_50k').each(function (i) {
                let value = $(this).is(':checked') ? 50000 : 0;
                iuran_50_values[i] = value;
                form_data.append('iuran_50k[]', iuran_50_values[i]);
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-iuran-peserta") }}',
                type: 'POST',
                // data: {
                //     id: iuran_id,
                //     iuran_type: iuran_type
                // },
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                // dataType: 'json',
                // async: true,
                success: function(res) {
                    $('#form_create_event').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                        footer: '<a href="{{ route('histori-transaksi') }}" class="text-primary">Riwayat Transaksi</a>'
                    })
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        })

        $('.nama_peserta_iuran_central_dialisis').select2()
    })
</script>