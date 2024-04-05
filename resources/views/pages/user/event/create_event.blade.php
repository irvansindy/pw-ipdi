{{-- modal --}}
<div class="modal fade" id="form_create_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="form_create_event_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_create_role">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Daftar Acara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-auto">
                            <p>Nama : <span id="name_event"></span></p>
                            <p>Judul : <span id="title_event"></span></p>
                            <p>Harga : <span id="price_event"></span></p>
                            <p>Kuota : <span id="quota_event"></span></p>
                            <p>Tanggal : <span id="date_event"></span></p>
                            <p>Terakhir Registrasi : <span id="last_register"></span></p>
                            {{-- <p>Description : <span id="desc_event"></span></p> --}}
                            <p>Rekening : <span id="bank_account_number"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-gradient-primary" id="create_transaction_event">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>