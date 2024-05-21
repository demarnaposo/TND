<?php
//session_start();
class Captcha
{

	private $bil1;

	private $bil2;

	private $operator;

	function initial()
	{
		$listoperator = array('+', '-');

		$this->bil1 = rand(1, 11);

		$this->bil2 = rand(1, 5);

		$this->operator = $listoperator[rand(0, 1)];
	}

	function generatekode()
	{

		$this->initial();
		if ($this->operator == '+') :
			$hasil = $this->bil1 + $this->bil2;
		elseif ($this->operator == '-') :
			$hasil = $this->bil1 - $this->bil2;
		elseif ($this->operator == 'x') :
			$hasil = $this->bil1 * $this->bil2;
		endif;
		$_SESSION['kode'] = $hasil;
	}

	function showcaptcha()
	{

		echo "Berapa hasil dari <b>" . $this->bil1 . "</b> " . $this->operator . " <b>" . $this->bil2 . "</b> ?" . '';
		//echo $_SESSION['kode'];
	}

	function resultcaptcha()
	{

		return $_SESSION['kode'];
	}
}
