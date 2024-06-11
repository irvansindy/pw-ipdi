{{-- modal --}}
<style>
    #ticket {
        background-image: url('img/logos/bg/bg_ticket.png');
        height: 640px;
        /* width: 900px; */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        margin: auto;
        object-fit: cover;
    }

    #logo {
        margin-top: 20px;
        margin-left: 20px;
        width: 100px;
    }

    .content {
        text-align: center;
        color: white;
        width: 100%;
    }

    .name_event {
        color: white;
    }

    #title_theme {
        font-size: 18px !important;
        margin-bottom: 20px;
    }

    #tagline {
        background-color: #B4B4B8;
        padding: 6px 12px 6px 12px;
        font-size: x-large;
        border-radius: 4px;
        margin-top: 10px;
    }

    .row {
        display: flex;
        padding-left: 50px;
        padding-right: 50px;
        margin-top: 10px;
    }

    .column {
        flex: 50%;
        padding: 10px;
        /* height: 30px; */
        /* Should be removed. Only for demonstration */
    }

    #ticket_event {
        margin-left: 200px !important;
    }

    .identitas {
        color: white;
        text-align: center;
    }

    #name {
        font-size: 26px;
        font-weight: bold;
    }
</style>
<div class="modal fade" id="QrCodeTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="QrCodeTransaction_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
        {{-- <img src="{{ asset('img/logos/bg/background_ticket.png') }}" alt=""> --}}
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_upload_file">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Qr Code transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container-fluid">
                    <div id="ticket">
                        <div class="content">
                            <h1 class="name_event"></h1>
                            <h4 style="color: white !important;"></h4><br>
                            <p style="margin-top: 20px;">
                            </p>
                        </div>
                        <div class="row" style="margin-top: 300px">
                            <div class="column" style="display: flex;">
                                <div id="ticket_event" style=""></div>
                            </div>
                            <div class="column" style="display: flex;">
                                <div style="margin-right: 100px;">
                                    <p class="identitas" id="name">Irvan Muhammad Sindy <br> 100132</p>
                                    <!-- <h2 class="identitas">100132</h2> -->
                                    <p class="identitas">This e-ticket is valid for one person only
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="preview_image"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="preview_qrcode">Proses</button>
                    {{-- <button type="button" class="btn bg-gradient-primary" id="download_qrcode">Download</button> --}}
                    <a href="#" class="btn btn-primary" id="download_qrcode">Download</a>
                </div>
            </form>
        </div>
    </div>
</div>