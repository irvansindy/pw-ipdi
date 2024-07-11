{{-- modal --}}
<div class="modal fade" id="detailTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="detailTransactionLabel" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg" role="document"> --}}
    <div class="modal-dialog mt-5" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_update_transaction">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="detailTransactionLabel">Detail Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input class="form-control" type="hidden" name="update_transaction_id" id="update_transaction_id" value="">
                        <dl class="row">
                            <dt class="col-sm-3">Event</dt>
                            <dd class="col-sm-9" id="update_transaction_event"></dd>

                            <dt class="col-sm-3">User</dt>
                            <dd class="col-sm-9" id="update_transaction_user"></dd>

                            <dt class="col-sm-3">Date</dt>
                            <dd class="col-sm-9" id="update_transaction_date"></dd>

                            <dt class="col-sm-3 text-truncate">Status</dt>
                            <dd class="col-sm-9" id="update_transaction_status"></dd>
                            
                            <dt class="col-sm-3 text-truncate">Bukti Transaksi</dt>
                            <dd class="col-sm-9" id="update_transaction_proof_of_payment"></dd>
                            
                        </dl>
                        <table class="table table-striped">
                            <thead>
                                <th>No</th>
                                <th>Nama Peserta</th>
                            </thead>
                            <tbody id="list_data_pererta">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="update_transaction">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
