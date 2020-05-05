<?php
	require_once("koneksi.php");
	class reset
	{
		private $koneksi;
		function __construct()
		{
			$objkoneksi=new koneksi;
			$this->koneksi=$objkoneksi->konek();
		}

		function main()
		{
			$query1=mysqli_query($this->koneksi,"DELETE FROM lokasi");
			$query2=mysqli_query($this->koneksi,"DELETE FROM graphsementara");
			$query3=mysqli_query($this->koneksi,"DELETE FROM koordinatawalsementara");
			$query4=mysqli_query($this->koneksi,"INSERT graphsementara SELECT*FROM graph");
			$query5=mysqli_query($this->koneksi,"INSERT koordinatawalsementara SELECT*FROM koordinatawal");

			header('location:../index.php');

		}
	}

	$obj=new reset;
	$obj->main();
?>