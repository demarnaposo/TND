<!DOCTYPE html>
<html lang="en">

<head>
  <!-- META SECTION -->
  <title>Tata Naskah Dinas Pemerintah Kota Bogor</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />


  <link rel="icon" href="<?php echo base_url('assets/img/icon.png') ?>" type="image/x-icon" />
  <!-- END META SECTION -->
  <link href="<?php echo base_url('assets/css/summernote/summernote.css') ?>" rel="stylesheet">
  <!-- CSS INCLUDE -->

  <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url('assets/css/theme-default.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/custom.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/csswhatsapp.css'); ?>">


  <!-- EOF CSS INCLUDE -->

  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery/jquery.min.js') ?>"></script>

</head>

<body>
  <!--<link href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' rel='stylesheet' type='text/css'/>-->
  <div id='whatsapp-chat' class='hide'>
    <div class='header-chat'>
      <div class='head-home'><b>Hai!</b></h3>
        <p>Pilih Customer Service kami di bawah ini untuk mengobrol di WhatsApp</p>
      </div>
      <div class='get-new hide'>
        <div id='get-label'></div>
        <div id='get-nama'></div>
      </div>
    </div>
    <div class='home-chat'>
      <!-- Info Contact Start -->
      <a class='informasi' href='javascript:void' title='Chat Whatsapp'>
        <div class='info-avatar'><img src='https://2.bp.blogspot.com/-y6xNA_8TpFo/XXWzkdYk0MI/AAAAAAAAA5s/RCzTBJ_FbMwVt5AEZKekwQqiDNqdNQJjgCLcBGAs/s70/supportmale.png' /></div>
        <div class='info-chat'>
          <span class='chat-label'>Customer Service</span>
          <span class='chat-nama'>Via Whatsapp</span>
        <!-- </div><span class='my-number'>681517677340</span> -->
        </div><span class='my-number'>6287789819311</span>
      </a>
      <!-- Info Contact End -->
      <div class='blanter-msg'>Hubungi Kami <b>087789819311</b></div>
    </div>
    <div class='start-chat hide'>
      <div class='first-msg'><span>Hallo, ada yang bisa saya bantu?</span></div>
      <div class='blanter-msg'><textarea id='chat-input' placeholder='Pesan teks' maxlength='120' row='1'></textarea>
        <a href='javascript:void;' id='send-it'>Kirim</a>
      </div>
    </div>
    <div id='get-number'></div><a class='close-chat' href='javascript:void'>x</a>
  </div>
  <!-- <a class='blantershow-chat' href='javascript:void' title='Show Chat'><i class='fa fa-envelope'></i>Chat Whatsapps</a> -->
  <!-- </body> -->
  <script type='text/javascript'>
    //<![CDATA[
    /* Whatsapp Chat Widget by Maks Miliyan*/
    $(document).on("click", "#send-it", function() {
      var a = document.getElementById("chat-input");
      if ("" != a.value) {
        var b = $("#get-number").text(),
          c = document.getElementById("chat-input").value,
          d = "https://web.whatsapp.com/send",
          e = b,
          f = "&text=" + c;
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) var d = "whatsapp://send";
        var g = d + "?phone=" + e + f;
        window.open(g, '_blank')
      }
    }), $(document).on("click", ".informasi", function() {
      document.getElementById("get-number").innerHTML = $(this).children(".my-number").text(), $(".start-chat,.get-new").addClass("show").removeClass("hide"), $(".home-chat,.head-home").addClass("hide").removeClass("show"), document.getElementById("get-nama").innerHTML = $(this).children(".info-chat").children(".chat-nama").text(), document.getElementById("get-label").innerHTML = $(this).children(".info-chat").children(".chat-label").text()
    }), $(document).on("click", ".close-chat", function() {
      $("#whatsapp-chat").addClass("hide").removeClass("show")
    }), $(document).on("click", ".blantershow-chat", function() {
      $("#whatsapp-chat").addClass("show").removeClass("hide")
    });
    //]]>
  </script>
  <script>
    function hai() {
      $("#whatsapp-chat").addClass("show").removeClass("hide")
    }
  </script>
  <!-- START PAGE CONTAINER -->
  <div class="page-container">

    <?php $this->load->view('sidebar'); ?>


    <!-- PAGE CONTENT -->
    <div class="page-content">

      <?php $this->load->view('header'); ?>

      <?php $this->load->view($content); ?>

    </div>
    <!-- END PAGE CONTENT -->

  </div>
  <!-- END PAGE CONTAINER -->

  <!-- MESSAGE BOX-->
  <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
    <div class="mb-container">
      <div class="mb-middle">
        <!-- <div class="mb-title"><span class="fa fa-sign-out"></span> Kel <strong>luar</strong> ?</div> -->
        <div class="mb-title"><span class="fa fa-sign-out"></span> Keluar ?</div>
        <div class="mb-content">
          <p> Apakah Anda yakin ingin keluar??</p>
          <p>Tekan Tidak jika Anda ingin melanjutkan Aplikasi. Tekan Ya untuk keluar dari pengguna saat ini.</p>
        </div>
        <div class="mb-footer">
          <div class="pull-right">
            <a href="<?php echo site_url('login/logout') ?>" class="btn btn-success btn-lg" style="background-color: black; border: none;">Ya</a>
            <button class="btn btn-default btn-lg mb-control-close" style="border: none;">Tidak</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END MESSAGE BOX-->

  <!-- START PRELOADS -->
  <audio id="audio-alert" src="<?php echo base_url('assets/audio/alert.mp3') ?>" preload="auto"></audio>
  <audio id="audio-fail" src="<?php echo base_url('assets/audio/fail.mp3') ?>" preload="auto"></audio>
  <!-- END PRELOADS -->

  <!-- START SCRIPTS -->
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery/jquery.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery/jquery-ui.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/bootstrap/bootstrap.min.js') ?>"></script>

  <!-- Update @Mpik Egov 27/06/2022 -->
  <script src="<?php echo base_url() . 'responsive-filemanager/tinymce/tinymce.min.js'; ?>" type="text/javascript"></script>
  <script type="text/javascript">
    tinymce.init({
      selector: '#textarea1,#textarea2,#textarea3,#textarea4,#textarea5,#textarea6,#textarea7',
      relative_urls: false,
      theme: "modern",
      width: "100%",
      height: 500,
      plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks code visualchars  insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons paste textcolor responsivefilemanager colorpicker qrcode youtube"
      ],
      toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
      toolbar2: "| responsivefilemanager | link unlink anchor | image media | youtube | qrcode | colorpicker forecolor backcolor  | print preview code ",
      image_advtab: true,
      utosave_ask_before_unload: false,
      filemanager_access_key: "a0809c76ed47d7bb56c0988e7becf90876508cdc",
      max_height: 200,
      min_height: 160,
      external_filemanager_path: "<?php echo base_url() . 'responsive-filemanager/filemanager/'; ?>",
      filemanager_title: "Responsive Filemanager",
      external_plugins: {
        "filemanager": "<?php echo base_url() . 'responsive-filemanager/tinymce/plugins/responsivefilemanager/plugin.min.js'; ?>"
      }
    });
  </script>
  <script type="text/javascript">
    tinymce.init({
      selector: '#catatan',
      relative_urls: false,
      theme: "modern",
      width: "100%",
      height: 100,
      toolbar1: "undo redo | bold italic underline",
      image_advtab: true,
      utosave_ask_before_unload: false,
      filemanager_access_key: "a0809c76ed47d7bb56c0988e7becf90876508cdc",
      max_height: 200,
      min_height: 160,
    });
  </script>
  <!-- Update @Mpik Egov 27/06/2022 -->

  <!-- CKEditor 4 -->
  <!-- Update @Mpik Egov 22/06/2022 -->
  <script src="<?php echo base_url() ?>assets/js/plugins/ckeditor/ckeditor.js"></script>

  <script language="javascript">
    CKEDITOR.replace('editor1', {
      filebrowserBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html?type=Images',
      filebrowserFlashBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html?type=Flash',
      filebrowserUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
      filebrowserFlashUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
      filebrowserWindowWidth: '700',
      filebrowserWindowHeight: '400'
    });

    CKEDITOR.replace('editor2', {
      filebrowserBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html?type=Images',
      filebrowserFlashBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html?type=Flash',
      filebrowserUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
      filebrowserFlashUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
      filebrowserWindowWidth: '700',
      filebrowserWindowHeight: '400'
    });

    CKEDITOR.replace('editor3', {
      filebrowserBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html?type=Images',
      filebrowserFlashBrowseUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/ckfinder.html?type=Flash',
      filebrowserUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
      filebrowserFlashUploadUrl: '<?php echo base_url() ?>assets/js/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
      filebrowserWindowWidth: '700',
      filebrowserWindowHeight: '400'
    });
  </script>

  <!-- END -->
  <!-- Update @Mpik Egov 22/06/2022 -->

  <script src="<?php echo base_url('assets/js/plugins/summernote/summernote.js') ?>"></script>
  <script>
    $('#summernote').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote1').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote2').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote3').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote4').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote5').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote6').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <script>
    $('#summernote7').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
  <!-- END PLUGINS -->

  <!-- START ALL PAGE PLUGINS-->
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/icheck/icheck.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') ?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/highlight/jquery.highlight-4.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/faq.js') ?>"></script>

  <!-- FORM -->
  <script type='text/javascript' src='<?php echo base_url('assets/js/plugins/bootstrap/bootstrap-datepicker.js') ?>'></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/bootstrap/bootstrap-timepicker.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/bootstrap/bootstrap-colorpicker.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/bootstrap/bootstrap-file-input.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/bootstrap/bootstrap-select.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/tagsinput/jquery.tagsinput.min.js') ?>"></script>
  <!-- END FORM -->

  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/rickshaw/d3.v3.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/rickshaw/rickshaw.min.js') ?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/moment.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/datatables/jquery.dataTables.min.js') ?>"></script>

  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins/codemirror/codemirror.js') ?>"></script>
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/codemirror/mode/htmlmixed/htmlmixed.js') ?>"></script>
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/codemirror/mode/xml/xml.js') ?>"></script>
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/codemirror/mode/javascript/javascript.js') ?>"></script>
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/codemirror/mode/css/css.js') ?>"></script>
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/codemirror/mode/clike/clike.js') ?>"></script>
  <script type='text/javascript' src="<?php echo base_url('assets/js/plugins/codemirror/mode/php/php.js') ?>"></script>

  <!-- Tiny.Cloud -->
  <!-- <script src="https://cdn.tiny.cloud/1/8p2zc34b30rjd2se5sk783e96me9slc3ty3gnwwf2bllx43f/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
            <script>
                tinymce.init({
                    selector: '#isi, #isi2, #isi3, #isi4, #isi5, #isi6, #isi7',
                    height: 300,
                    menubar: true,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks advcode fullscreen',
                        'insertdatetime media table powerpaste hr code',
                        'code image '
                    ],
                    toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code image',
                    powerpaste_allow_local_images: true,
                    powerpaste_word_import: 'prompt',
                    powerpaste_html_import: 'prompt',
                    images_upload_url: '<?= base_url('PostAcceptor.php') ?>',
                    images_upload_base_path: '/assets/imageupload',
                    images_upload_credentials: true,
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                    // override default upload handler to simulate successful upload
                    images_upload_handler: function (blobInfo, success, failure) {
                        var xhr, formData;
                    
                        xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', '<?= base_url('PostAcceptor.php') ?>');
                    
                        xhr.onload = function() {
                            var json;
                        
                            if (xhr.status != 200) {
                                failure('HTTP Error: ' + xhr.status);
                                return;
                            }
                        
                            json = JSON.parse(xhr.responseText);
                        
                            if (!json || typeof json.location != 'string') {
                                failure('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                        
                            success(json.location);
                        };
                    
                        formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                        xhr.send(formData);
                    },
            });
            </script> -->
  <!-- Tiny.Cloud -->
  <script type='text/javascript' src='<?php echo base_url('assets/js/plugins/noty/jquery.noty.js') ?>'></script>
  <script type='text/javascript' src='<?php echo base_url('assets/js/plugins/noty/layouts/topCenter.js') ?>'></script>
  <script type='text/javascript' src='<?php echo base_url('assets/js/plugins/noty/layouts/topLeft.js') ?>'></script>
  <script type='text/javascript' src='<?php echo base_url('assets/js/plugins/noty/layouts/topRight.js') ?>'></script>

  <script type='text/javascript' src='<?php echo base_url('assets/js/plugins/noty/themes/default.js') ?>'></script>
  <?php if ($this->session->flashdata('error')) { ?>
    <!-- error -->
    <div class="message-box message-box-danger animated fadeIn" id="message-error">
      <div class="mb-container">
        <div class="mb-middle">
          <div class="mb-title"><span class="fa fa-times"></span> <?php echo $this->session->flashdata('error') ?></div>
          <div class="mb-footer">
            <button class="btn btn-default btn-lg pull-right mb-control-close" id="close-message-error">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- error -->

    <script type="text/javascript">
      $('#message-error').show();
      $('#close-message-error').click(function() {
        $('#message-error').hide();
      });
    </script>
  <?php } elseif ($this->session->flashdata('success')) { ?>
    <!-- success -->
    <div class="message-box message-box-warning animated fadeIn" id="message-box-warning">
      <div class="mb-container">
        <div class="mb-middle">
          <div class="mb-title"><span class="fa fa-check"></span> <?php echo $this->session->flashdata('success'); ?></div>
          <div class="mb-footer">
            <button class="btn btn-default btn-lg pull-right mb-control-close" id="close-message-box-warning">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- success -->

    <script type="text/javascript">
      $('#message-box-warning').show();
      $('#close-message-box-warning').click(function() {
        $('#message-box-warning').hide();
      });
    </script>
  <?php } ?>
  <!-- END ALL PAGE PLUGINS-->

  <!-- START TEMPLATE -->
  <script type="text/javascript" src="<?php echo base_url('assets/js/settings.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/plugins.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/actions.js') ?>"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
  <!-- END TEMPLATE -->

  <!-- END SCRIPTS -->

</body>

</html>