<?php
	require_once("php/koneksi.php");
	require_once("php/jp/panggiljtpacs.php");

	class titikpelanggan
	{
		private $koneksi;
		private $graphjarak=array();
		private $graphtsp=array();

		function __construct()
		{
			$objkoneksi=new koneksi;
			$this->koneksi=$objkoneksi->konek();
		}

		function graphjarak()
		{			
			$query=mysqli_query($this->koneksi,'SELECT *from graphsementara');
			while ($rows=mysqli_fetch_array($query))
			{
				$simpul_awal=$rows['simpul_awal'];
				$simpul_tujuan=$rows['simpul_tujuan'];
				$jarak=$rows['bobot_jarak'];
				$array[$simpul_awal][$simpul_tujuan]=$jarak;
				//$array[$simpul_awal][$simpul_tujuan]=$jarak;
			}

			return $array;
		}

		function graphtsp()
		{		
			$array=array();	
			$arraytsp=array();
			$query=mysqli_query($this->koneksi,'SELECT *from lokasi');
			while ($rows=mysqli_fetch_array($query))
			{
				$simpul=$rows['simpul'];
				//$array[$simpul]=null;
				array_push($array, $simpul);
			}

			foreach ($array as $key => $value1) 
			{
				//$arraytsp[$key][$key]=null;
				foreach ($array as $key => $value2) 
				{
					if($value1==$value2)
						continue;
					else
						$arraytsp[$value1][$value2]=0;
				}
			}

			return $arraytsp;
		}

		function main()
		{

			echo '<pre>'; print_r($this->graphtsp());
			// $hasil=$objpanggiljtpacs->main(0, $value,$graph);
			// $hasilgraph[$i++][$value]=$hasil;
			
			// echo '<pre>'; print_r($hasilgraph);
 
			// $akhir = microtime(true);
		 
			// echo '<br >Waktu : ', ($akhir - $mulai), '.';

		}
	}


	$obj=new titikpelanggan;
	$obj->main();
?>

