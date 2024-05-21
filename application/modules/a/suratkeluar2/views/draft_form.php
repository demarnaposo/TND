<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/draft') ?>">Draft Surat</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Penomoran dan Pengarsipan Surat</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT FORM -->
            <form class="form-horizontal" action="<?php echo site_url('suratkeluar/draft/disposisi') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>" required>
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                </div>
                <div class="panel-body">

                    <div class="row">
                        
                        <?php if (empty($nomor)) { ?>
                            
                            <div class="col-md-12">
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Nomor Urut Surat</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" class="form-control" name="no_urut">   
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Kode Bidang</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" class="form-control" name="kode_bidang">   
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Penandatangan</label>
                                    <div class="col-md-10">                                           
                                        <select name="jabatan_id" class="form-control select" data-live-search="true">
                                            <?php foreach ($penandatangan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select>  
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <br><br> <center> <button class="btn btn-info" name="simpan" type="submit">Simpan</button> </center>
                            </div>

                        <?php }else{ ?>

                            <div class="col-md-12">
                                <div class="form-group">                                        
                                    <label> Nomor Surat : <?php echo $nomor ?> </label>
                                </div>
                                <div class="form-group">                                        
                                    <label> Penandatangan Surat : <?php echo $ttd ?> </label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">      
                                    <label class="col-md-12 control-label" style="font-size: 20px;"><center>Pengarsipan</center></label>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">No Rak</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" name="no_rak" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">No Sampul</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" name="no_sampul" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">No Book</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" name="no_book" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <br> <center> <button class="btn btn-primary" name="arsipkan" type="submit">Arsipkan</button> </center>
                            </div>

                        <?php } ?>

                    </div>
                
                </div>
                <div class="panel-footer">
                    <button class="btn btn-danger" type="reset">Bersihkan Form</button>
                </div>
            </div>
            </form>
            <!-- END DEFAULT FORM -->

        </div>
    </div>
</div>