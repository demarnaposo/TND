<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Tanda Tangan Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Tanda Tangan Surat </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">

                    <table class="table datatable table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JENIS SURAT</th>
                                <th>TANGGAL</th>
                                <th>NAMA PEMBUAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($tandatangan as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->nama_jabatan; ?></td>
                                <?php if(!empty($h->lampiran_lain)){?>
                                 <td align="center">                    
                                   <button type="button" 
                                        class="btn btn-xs btn-info" 
                                        data-penandatangan-id="<?php echo $h->penandatangan_id; ?>" 
                                        data-surat-id="<?php echo $h->surat_id; ?>" 
                                        data-action="signature" 
                                        data-preview="<?php echo site_url('export/lampiran/'.$h->lampiran_lain)?>"
                                    >
                                      <i class="fa fa-fw fa-edit"></i> TANDA TANGANI
                                   </button>           
                                </td>
                                <?php }else{?>
                                <td align="center">                    
                                   <button type="button" 
                                        class="btn btn-xs btn-info" 
                                        data-penandatangan-id="<?php echo $h->penandatangan_id; ?>" 
                                        data-surat-id="<?php echo $h->surat_id; ?>" 
                                        data-action="signature" 
                                        data-preview="<?php echo lihatsuratlink($h->surat_id); ?>"
                                    >
                                       <i class="fa fa-fw fa-edit"></i> TANDA TANGANI
                                   </button>           
                                </td>
                                <?php }?>
                            </tr>
                            <?php
                                $no++; 
                                } 
                            ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-fw fa-edit"></i> TANDA TANGANI BERKAS</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer text-left">
                <div id="form">
                    <form class="form-inline" action="<?php echo site_url('signature'); ?>" method="POST" id="signatureForm">
                        <input type="text" name="pdf_path">
                        <input type="text" name="penandatangan_id">
                        <input type="text" name="surat_id">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" name="passphrase" class="form-control form-control-lg" placeholder="# Masukan passphrase">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">TANDA TANGANI</button>
                    </form>
                </div>
                <div id="progress" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-warning active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            <span class="sr-only">45% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">

    let previewModal = $('#signatureModal'); 

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
                $('#form').hide(); 
                $('#progress').show(); 
            }
        })
        .done(function(res) {
            $('#progress').hide(); 
            $('#form').show(); 

            Swal.fire({
                title: 'Berhasil !',
                text: 'Dokumen berhasil ditandatangani',
                icon: 'success',
                confirmButtonText: 'Tutup'
            })
            .then((res) => {
                document.location.href=""
            })
        })
        .fail(function(res, xhr, status) {
            Swal.fire({
                title: 'Error!',
                text: res.responseJSON.error,
                icon: 'error',
                confirmButtonText: 'Tutup'
            })

            $('#progress').hide(); 
            $('#form').show(); 
        }); 
    }); 

    $('button[data-action="signature"]').on('click', function(e) {
        e.preventDefault()
        element = $(this)
        preview = $(this).data('preview');
        previewModal.find('.modal-body')
            .promise()
            .done(function() {
                $('input[name="pdf_path"]').val(preview)
                $('input[name="penandatangan_id"]').val(element.data('penandatangan-id'))
                $('input[name="surat_id"]').val(element.data('surat-id'))
                $('#signatureModal').modal('show')
            })
    }); 
</script>
