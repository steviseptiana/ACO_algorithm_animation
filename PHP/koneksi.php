<?php
	class koneksi
	{
		function konek()
		{
			$user="root";
			$pass="";
			$host="localhost";
			$db="animasi";

			return $konek=mysqli_connect($host, $user, $pass, $db);
		}
	}

?>