<?php
	require_once("koneksi.php");

	class lokasi
	{
		private $koneksidb;

		function __construct()
		{
			$db=new koneksi;
			$this->koneksidb=$db->konek();
		}

		function marker()
		{

			$lokasi=array();
			$i=0;

			$query=mysqli_query($this->koneksidb,"SELECT *FROM lokasi") or die(mysqli_error($this->koneksidb));
			while ($rows=mysqli_fetch_array($query))
			{
				$lat=$rows['latitude'];
				$lng=$rows['longitude'];
				$lokasi[$i]='['.$lat.','.$lng.']';
				$i++;
			}

			
			echo json_encode($lokasi);
			//echo"<pre>"; print_r($hasilkoor);
		}

	}

	$tes=new lokasi;
	$tes->marker();
?>