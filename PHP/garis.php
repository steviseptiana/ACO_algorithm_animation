<?php
	require_once("koneksi.php");

	class maps
	{
		private $koneksidb;

		function __construct()
		{
			$db=new koneksi;
			$this->koneksidb=$db->konek();
		}

		function linemarker()
		{

			$koor=array();

			$query=mysqli_query($this->koneksidb,"SELECT *FROM graphsementara") or die(mysqli_error($this->koneksidb));
			while ($rows=mysqli_fetch_array($query))
			{
				$koordinat=$rows['koordinat'];
				$simpul_awal=$rows['simpul_awal'];
				$simpul_tujuan=$rows['simpul_tujuan'];
				$koor[$simpul_awal][$simpul_tujuan]='['.$koordinat.']';
				$koor[$simpul_tujuan][$simpul_awal]='['.$koordinat.']';
			}

			echo json_encode($koor);
			//echo"<pre>"; print_r($koor);
		}


	}

	$tes=new maps;
	$tes->linemarker();
?>