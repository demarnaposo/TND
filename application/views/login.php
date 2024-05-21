<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Tata Naskah Dinas Pemerintah Kota Bogor
        </title>

        <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png') ?>"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/bootstrap/css/bootstrap.min.css') ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/animate/animate.css') ?>">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/css-hamburgers/hamburgers.min.css') ?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/vendor/select2/select2.min.css') ?>">
<!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/css/main3.css') ?>">
    <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('main3.css') ?>">-->
<!--===============================================================================================-->
       
    </head>

    <body>
        <div class="limiter100">
            
            <div class="container-login1000">

                <div class="row row-2">
                  
                    <div class=" con-login-3000 col-sm-7 d-none d-lg-block"  >
                                                        
                                <div class="row" >
                                    <div class="col-md-2 col-lg-4">
                                        <!-- <img src="assets/login/images/logo.png"  width="300"   alt="IMG"> -->
                                    </div>
                            
                                </div>
                               

                                <div class="login100-form-title2 d-none d-lg-block" align="center">
                                    <!-- TNDE merupakan aplikasi Layanan <br> Persuratan yang digunakan oleh Pemerintah Kota Bogor -->
                                </div>                                                                               
                            
                    </div>
                    
                    <div class=" con-login-3000 col-sm-5"  >
                        <!-- di bawah <div>.con-login-2000 -->
                            <br>
                        <div class="con-login-2000">  
                            <div class="wrap-login1000">
                                                            
                    
                            <form class="login100-form validate-form" action="<?php echo site_url('login/cek') ?>" method="post">
                        
                                    <div class="title2">
                                        Tata Naskah Dinas <P> Pemerintah Kota Bogor
                                    </div>
                            
                                    <div class="title3">
                                        Masuk Pengguna
                                    </div>
                       
                                    <?php if ($this->session->flashdata('access')) { ?>
					                    <div class="alert alert-danger" role="alert">
					                        <center><?php echo $this->session->flashdata('access'); ?></center>
					                    </div>
					                <?php }elseif($this->session->flashdata('cek_aritmatika')){ ?>
						                <div class="alert alert-danger" role="alert">
					                        <center><?php echo $this->session->flashdata('cek_aritmatika'); ?></center>
					                    </div>
					                <?php } ?>
                        

                                    <div class="wrap-input100 validate-input" data-validate="Username is required">
                                        <input class="input100" type="text" name="username" placeholder="Nama Pengguna">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                                        <input class="input100" type="password" name="password" id="pass_log_id" placeholder="Kata Sandi">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <!-- Start icon fitur show/hide password [@dam | 09-05-2022] -->
                                    <p align="center">
                                    <span toggle="#password-field" class="fa fa-eye-slash toggle-password"></span> Tampilkan / Sembunyikan Kata Sandi
                                    </p>
                                    <!-- End icon fitur show/hide password -->

                                    <?php 
						                $tahun = date('Y');
						                $tahunAwal = date('Y')-2;  
					                ?>
                        
                                    <div class="wrap-input100 validate-input" data-validate="Tahun is required">
                                        <select class="input100" name="tahun" required>
							                <?php for ($thn=$tahunAwal; $thn <= $tahun; $thn++) { ?>
								                <option value="<?php echo $thn; ?>" <?php if ($thn == $tahun){ echo "selected"; }?>><?php echo "Tahun ".$thn; ?></option>
							                <?php } ?>
						                </select>
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </span>
                                    </div>

                                    <?php echo $this->captcha->generatekode(); ?>
					                    <?php
					                        $h['hasil'] =  $_SESSION['kode'];
					                        $this->session->set_userdata($h);
					                        $hasil = $this->session->userdata('hasil');
					                    ?>

                                    <p class="text-center"><?php echo $this->captcha->showcaptcha(); ?></p>

                                    <!-- <div class="login100-form-title-text"> </div> -->

                                    <div class="wrap-input100 validate-input">
                                        <input class="input100" type="text" name="kode" placeholder="Hasil">
                                        <span class="focus-input100"></span>
                                        <span class="symbol-input100">
                                            <i class="fa fas fa-calculator" aria-hidden="true"></i>
                                        </span>
                                    </div>
                        
                                    <div class="container-login100-form-btn">
                                        <button class="login100-form-btn" type="submit">
                                            Masuk
                                        </button>

                                        <a href="<?= base_url('login/login') ?>" class="login100-form-btn btn my-2" style="background: #5b7fff !important;" type="submit">
                                            login menggunakan sso
                                        </a>
                                    </div>

                                </form>

                            </div>
               
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>
            
        

<!--===============================================================================================-->	
	<script src="<?php echo base_url('assets/login/vendor/jquery/jquery-3.2.1.min.js') ?>"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('assets/login/vendor/bootstrap/js/popper.js') ?>"></script>
	<script src="<?php echo base_url('assets/login/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('assets/login/vendor/select2/select2.min.js') ?>"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url('assets/login/vendor/tilt/tilt.jquery.min.js') ?>"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
    <!-- Start fitur show/hide password [@dam | 09-05-2022] -->
	<script>
	    $("body").on('click', '.toggle-password', function() {
          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $("#pass_log_id");
          if (input.attr("type") === "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        
        });
	</script>
	<!-- End fitur show/hide password -->
<!--===============================================================================================-->
	<script src="<?php echo base_url('assets/login/js/main.js') ?>"></script> 


    </body>
</html>
