{{-- modal --}}
<div class="modal fade" id="detailAttendance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="detailAttendanceLabel" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg" role="document"> --}}
    <div class="modal-dialog modal-lg mt-5" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_update_transaction">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="detailAttendanceLabel">Detail Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive p-0 px-md-2">
                        <table class="table align-items-center mb-0 data_tables" id="list_user_attendance">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Transaction Number
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Event
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        User
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status
                                    </th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="update_transaction">Approve</button>
                </div> --}}
            </form>
        </div>
    </div>
</div>
