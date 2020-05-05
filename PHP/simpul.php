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

			$query=mysqli_query($this->koneksidb,"SELECT *FROM koordinatawalsementara") or die(mysqli_error($this->koneksidb));
			while ($rows=mysqli_fetch_array($query))
			{
				$simpul=$rows['simpul'];
				$lat=$rows['lat'];
				$lng=$rows['lng'];
				$hasilsimpul[$simpul]='['.$lat.','.$lng.']';
			}

			echo json_encode($hasilsimpul);
			//echo"<pre>"; print_r($koor);
		}


	}

	$tes=new maps;
	$tes->linemarker();
?>