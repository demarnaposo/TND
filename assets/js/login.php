<!DOCTYPE html>
<html lang="en">
<head>
	<title>TND Pemerintah Kota Bogor</title>
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
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/css/util.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/login/css/main.css') ?>">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?php echo base_url('assets/login/images/logologin.png') ?>" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="<?php echo site_url('login/cek') ?>" method="post">
					<span class="login100-form-title">
						Silahkan Login
					</span>

					<?php if ($this->session->flashdata('access')) { ?>
					    <div class="alert alert-danger" role="alert">
					        <center><?php echo $this->session->flashdata('access'); ?></center>
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
						<input class="input100" type="password" name="password" placeholder="Kata Sandi">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

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
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Masuk
						</button>
					</div>

					<div class="text-center p-t-80"> </div>
				</form>
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
<!--===============================================================================================-->
	<script src="<?php echo base_url('assets/login/js/main.js') ?>"></script> 

</body>
</html>


<!--------------------------------------------------
                    [0,0]
                    |)__)  <(Moo)
                   _,,_,,_
---------------------------------------------------->