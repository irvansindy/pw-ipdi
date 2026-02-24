{{-- modal --}}
<div class="modal fade" id="approval_iuran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="approval_iuranLabel" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_approval_iuran">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="IuranLabel">Create New Iuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive p-0 px-md-2">
                        <input type="hidden" name="master_iuran_id" id="master_iuran_id">
                        <table class="table align-items-center mb-0 data_tables" id="list_user_iuran" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        NAMA
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        NIRA IPDI
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        CENTER DIALISIS
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        TAHUN
                                    </th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody id="data_list_user_iuran">

                            </tbody>
                        </table>
                    </div>
                    <div id="bukti_transaksi_iuran"></div>
                </div>
                <div class="modal-footer" id="button_approval_iuran">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="approve_data_iuran">Approved</button>
                </div>
            </form>
        </div>
    </div>
</div>
