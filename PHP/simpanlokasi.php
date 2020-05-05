<?php
	
	// require_once("simpulbaru/koneksi.php");
	require_once("simpulbaru/simpulbaru.php");

	class simpandanpotonggraph
	{
		private $koneksi;
		function __construct()
		{
			$objkoneksi=new koneksi;
			$this->koneksi=$objkoneksi->konek();
		}

		function main($lat,$lng)
		{
			
			$query1=mysqli_query($this->koneksi,"SELECT MAX( simpul_awal ) AS max FROM graphsementara");
			$row = mysqli_fetch_array($query1);
			$simpulbaru=$row['max']+1;

			$query2=mysqli_query($this->koneksi,"INSERT INTO lokasi values('','$lat','$lng','$simpulbaru')");

			$obj=new simpulbaru;
			$obj->main($lat,$lng,$simpulbaru);
			header('location:../index.php');
		}
	}


	if(!empty($_GET['lat']) and !empty($_GET['lng']))
	{
		$obj=new simpandanpotonggraph;
		$obj->main($_GET['lat'],$_GET['lng']);
	}
?>