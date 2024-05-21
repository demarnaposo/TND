<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Tanda Tangan Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">
    <h2><span class="fa fa-envelope"></span> Tanda Tangan Surat</h2>
</div>
<!-- END PAGE TITLE -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <iframe src="<?= $linksurat ?>" width="100%" height="600">
                            </iframe>
                        </div>
                        <div class="col-md-4">
                            <!-- <form> -->
                            <form action="<?php echo site_url('suratkeluar/draft/tteSigniature'); ?>" method="post" id="signatureForm">
                                <input type="hidden" name="pdf_path" value="<?= $linksurat ?>">
                                <input type="hidden" name="penandatangan_id" value="<?= $penandatangan_id ?>">
                                <input type="hidden" name="surat_id" value="<?= $surat_id ?>">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="password" name="passphrase" class="disabled form-control" placeholder="Masukan Passpress anda..." required/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="spinner" id="spinner"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-top: 10px;">
                                            <button class="btn btn-warning">Tanda Tangani</button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                            <div class="my-3">
                                <p id="info" class="text-danger"></p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>
<style>
    .spinner {
        width: 2em;
        height: 2em;
        border: 0.5em solid rgba(0, 0, 0, 0.1);
        border-left-color: #fe970a;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $("#spinner").hide();
    $('#signatureForm').on('submit', function(e) {
        e.preventDefault();
        var formMethod = $(this).attr('method');
        var formAction = $(this).attr('action');
        var formData = $(this).serialize();
        $.ajax({
                method: formMethod,
                url: formAction,
                data: formData,
                beforeSend: function() {
                    $(".disabled").prop('disabled', true);
                    $("#spinner").show();
                }
            })
            .done(function(res) {
                // console.log(res)
                $("#spinner").hide();
                Swal.fire({
                        title: 'Berhasil !',
                        text: 'Dokumen berhasil ditandatangani',
                        icon: 'success',
                        confirmButtonText: 'Tutup'
                    })
                    .then((res) => {
                        document.location.href = "<?= site_url('suratkeluar/draft/signature') ?>"
                    })
            })
            .fail(function(res, xhr, status) {
                $("#spinner").hide();
                $(".disabled").prop('disabled', false);
                $('#info').text(res.responseJSON.error);
            });
    });
</script>