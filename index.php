<?php 
	require_once("php/koneksi.php"); 
	require_once("php/jtpacs.php"); 
	$objkoneksi=new koneksi;
	$koneksi=$objkoneksi->konek(); 

	$ruteakhir=array();
	$perubahanphe=array();

	//JALUR TERPENDEK
	function graphjarak()
	{			
		global $koneksi;
		$query=mysqli_query($koneksi,'SELECT *from graphsementara');
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

	function graphkoordinat()
	{			
		global $koneksi;
		$query=mysqli_query($koneksi,'SELECT *from graphsementara');
		while ($rows=mysqli_fetch_array($query))
		{
			$simpul_awal=$rows['simpul_awal'];
			$simpul_tujuan=$rows['simpul_tujuan'];
			$koordinat=$rows['koordinat'];
			$array[$simpul_awal][$simpul_tujuan]=$koordinat;
				//$array[$simpul_awal][$simpul_tujuan]=$jarak;
		}

		return $array;
	}

	
	function inversjarak($graph)
	{
		$inversjarak=array();

		foreach ($graph as $key1 => $arr) 
		{
			foreach ($arr as $key2 => $value) 
			{
				$inversjarak[$key1][$key2]=1/$value;
			}
		}
		return $inversjarak;
			//echo "<pre>"; print_r($inversjarak);
	}

	function pheromoneawal($graph)
	{
		$pheromoneawal=array();

		foreach ($graph as $key1 => $arr) 
		{
			foreach ($arr as $key2 => $value) 
			{
				$pheromoneawal[$key1][$key2]=0.0001;
			}
		}

		return $pheromoneawal;
			//echo "<pre>"; print_r($pheromoneawal);

	}

	
		//echo "<pre>"; print_r($graph);
?>


<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta charset ="utf-8">
	<link rel="stylesheet" type="text/css" href="css/animasi.css">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZfY-JkAcnh_Oip-_6-MA6aecRwU_CMsw"></script>	
	<script type="text/javascript" src="js/jquery-1.6.1.js"></script>
	<script type="text/javascript">var koordinatfixsemut=[];</script>
	<script src="js/infobox.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/animasi.js"></script>
 
</head>

<!-- onload="onloadhalaman()"-->
<body onload="onloadhalaman()">

	<div id="kotak1" class="kotak1">
		<center>ANIMASI ALGORITMA ANT COLONY OPTIMIZATION (ACO)</center>
		<a href="php/resetgraph.php">Reset Database</a>
	</div>
	
	<div id="kotak2" class="kotak2">
		
	</div>

	<div class="kotak3">
		<div class="kotak8">
			<table>
				<tr>
					<td colspan="2"><center>Lokasi dan Simpul</center></td>
				</tr>
				<tr>
					<td>
						<button id="lihatsimpul" onclick="tombolsimpul()">Aktifkan Lihat Nomor Simpul</button>
					</td>
					<td>
						<button id="lokasi" onclick="tombollokasii()">Aktifkan Penginputan Lokasi</button>
					</td>
				</tr>
			</table>
			<br>
		
			<table >
				<form action="index.php" method="POST">
				<tr>
					<td><center>Pilih Titik Awal dan Titik Tujuan</center></td>
				</tr>
				<tr>
					<td><select name="awal" style="width:100%; padding:5px; height:30px;  color:#c0bebe">
						<option style="display:none; color:Gainsboro; width:100%;  padding:5px;" selected value="kosong">Pilih Titik Awal</option>
							<?php
								$query=mysqli_query($koneksi,"SELECT * FROM lokasi");
								while ( $rows=mysqli_fetch_array($query))
								{
									$value1=$rows['latitude'];
									$value2=$rows['longitude'];
									$value3=$rows['simpul'];
												
										?>
						<option style="color:DimGray;" value="<?php echo $value3; ?>"> <?php echo $value1.'-'.$value2; ?> </option><?php }?></select>
					</td>
				</tr>
				<tr>
					<td><select name="tujuan" style="width:100%; height:30px;  padding:5px; color:#c0bebe">
						<option style="display:none; color:Gainsboro; width:100%;  padding:5px;" selected value="kosong">Pilih Titik Tujuan</option>
							<?php
								$query=mysqli_query($koneksi,"SELECT * FROM lokasi");
								while ( $rows=mysqli_fetch_array($query))
								{
									$value1=$rows['latitude'];
									$value2=$rows['longitude'];
									$value3=$rows['simpul'];
												
										?>
						<option style="color:DimGray;" value="<?php echo $value3; ?>"> <?php echo $value1.'-'.$value2; ?> </option><?php }?></select>
					</td>
				</tr>

				<tr>
					<td>
						<?php 
						if (!empty($_GET['error'])) 
						{
							if ($_GET['error'] == 1) 
							{
								echo '<p >Form belum terisi semua, silahkan inputkan titik awal dan titik tujuan kembali</p>';
							}
							else if ($_GET['error'] == 2) 
							{
								echo '<p >Titik awal dan titik tujuan sama, silahkan inputkan titik awal dan titik tujuan kembali</p>';
							}
						}
						?>
						<center>
							<input type="submit" value="Proses Algoritma" name="proses" class="algo" style="cursor:pointer;"/>
						</center>
					</td>
				</tr>
				</form>
			</table>

			
		</div>

		<div class="kotak9">

			<?php 
				if (!empty($_POST['proses'])) 
				{
					if(isset($_POST['proses']))
					{
						if ($_POST['awal']=='kosong' or $_POST['tujuan']=='kosong')
						{
							header('location:index.php?error=1');
						}
						if ($_POST['awal']==$_POST['tujuan'])
						{
							header('location:index.php?error=2');
						}
						else
						{
							// $awal=$_POST['awal'];
							// $tujuan=$_POST['tujuan'];
							// $arrrayanimasi=array();
							// $arrrayanimasi=mainjp($awal,$tujuan);
							$obj=new jalurterpendekacs;
							$obj->parameter();
							$arrayinfo=array();
							$arrayinfo[0]=$obj->jumlahsemut;
							$arrayinfo[1]=$obj->beta;
							$arrayinfo[2]=$obj->qo;
							$arrayinfo[3]=$obj->gamma;
							$arrayinfo[4]=$obj->rho;
							$arrayinfo[5]=$obj->alpha;
							$arrayinfo[6]=0.001;
							$arrayinfo[7]=$obj->iterasi;

							//$x=graphkoordinat();
						
						
							$awal=$_POST['awal'];
							$tujuan=$_POST['tujuan'];
							$arrrayanimasi=array();
							$arrrayanimasi=mainjp($awal,$tujuan);

							
							?>
							
							<script type="text/javascript">info=<?Php echo json_encode($arrayinfo); ?>;</script>
							<script type="text/javascript">arrayanimasi=<?Php echo json_encode($arrrayanimasi); ?>;</script>
							<script type="text/javascript">pheromone=<?Php echo json_encode($perubahanphe); ?>;</script>
							<script type="text/javascript">jalurterpendek=<?Php echo json_encode($ruteakhir); ?>; a=1;</script>
							<!--<script type="text/javascript">arrayanimasi=<?Php //echo json_encode($arrrayanimasi); ?>; a=1;</script>-->
							<!--<script type="text/javascript">tes[1]=<?Php //echo json_encode($x[7][10]); ?>; a=1;</script>
							<script type="text/javascript">tes[0]=<?Php //echo json_encode($x[13][7]); ?>; a=1;</script>
							<script type="text/javascript">console.log(arrayanimasi)</script>-->
							
							<?php
						}
					}
				}
			?>

			<?php
				//u/ animasi pheromone
				$phe=array();

				function jp($titikawal,$titiktujuan,$siklus)
				{
					$objj=new jalurterpendekacs;
					$perjalanansemut=array();
					$semutterbaik=array();
					$graph=graphjarak();
					$objj->graph=$graph;
					$objj->inversjarak=inversjarak($graph);
					$objj->pheromone=pheromoneawal($graph);
					$objj->parameter();
					$koordx=array();
					$koordx=graphkoordinat();


						//echo "<pre>"; print_r($graph);
						//melakukan transisistatus dan pembaruan pheromone
				
					//untuk animasi
					$v=0;
					$w=0;
					$objj->rutesemut['iterasi']=$siklus;

					for ($i=1; $i <=$objj->jumlahsemut ; $i++) 
					{ 

						$objj->tabulist=array();
						array_push($objj->tabulist, $titikawal);
						$titikpencarian=$titikawal;

						//untuk animasi
						$objj->rutesemut['proses'][$i]['semut']=$i;
						$objj->rutesemut['proses'][$i]['jalur'][$v]=$titikawal;

						for ($j=0; $j <1; $j++) 
						{ 
							//$titikanimasi1=$titikpencarian;

							$pilihtitik=$objj->transisistatus($titikpencarian);
							$titikpencarian=$pilihtitik;


							if($pilihtitik==-1) //jika titik terpilih adalah -1 (sbg parameter saja) mksudnya jika titik trsebut tdak trhubung kmanapun atau sdah tidak ada titik yg bisa dipilih(masuk dalam tabulist smua)
							{
								break;
							}

							if(!array_key_exists($pilihtitik,$objj->graph))//learn
							{
								array_push($objj->tabulist, $pilihtitik);

								//untuk animasi
								$v=$v+1;
								$fix1=$objj->tabulist[count($objj->tabulist)-2];
								$fix2=$objj->tabulist[count($objj->tabulist)-1];
								$objj->rutesemut['proses'][$i]['jalur'][$v]=$pilihtitik;
								$objj->rutesemut['proses'][$i]['koordinat'][$w]="[".$koordx[$fix1][$fix2]."]";
								$w=$w+1;

								//untuk animasi
								//untuk animasi
								$x=$fix1."-".$fix2."=".exp2dec($objj->pheromone[$fix1][$fix2]);
								array_push($GLOBALS['perubahanphe'],$x);
									
								break;
							}

							if ($pilihtitik==$titiktujuan)
							{
								array_push($objj->tabulist, $pilihtitik);


								//untuk animasi
								$v=$v+1;
								$fix1=$objj->tabulist[count($objj->tabulist)-2];
								$fix2=$objj->tabulist[count($objj->tabulist)-1];
								$koordx=graphkoordinat();
								$objj->rutesemut['proses'][$i]['jalur'][$v]=$pilihtitik;
								$objj->rutesemut['proses'][$i]['koordinat'][$w]="[".$koordx[$fix1][$fix2]."]";
								$w=$w+1;

								//untuk animasi
								$x=$fix1."-".$fix2."=".exp2dec($objj->pheromone[$fix1][$fix2]);
								array_push($GLOBALS['perubahanphe'],$x);

								break;
							}
							else
							{
								--$j; 
							}

							array_push($objj->tabulist, $pilihtitik);

							//untuk animasi
							$v=$v+1;
							$fix1=$objj->tabulist[count($objj->tabulist)-2];
							$fix2=$objj->tabulist[count($objj->tabulist)-1];
							$koordx=graphkoordinat();
							$objj->rutesemut['proses'][$i]['jalur'][$v]=$pilihtitik;
							$objj->rutesemut['proses'][$i]['koordinat'][$w]="[".$koordx[$fix1][$fix2]."]";
							$w=$w+1;

							//untuk animasi
							$x=$fix1."-".$fix2."=".exp2dec($objj->pheromone[$fix1][$fix2]);
							array_push($GLOBALS['perubahanphe'],$x);
								

						}

						$v=0;
						$w=0;

						$perjalanansemut[$i]=array('Semut'=>$i,'Perjalanan'=>$objj->tabulist);
					
					}

					//menghitung jarak masing2 perjalanan semut
						
					foreach ($perjalanansemut as $key => $value) 
					{
						
						$jarak=0;
						foreach ($perjalanansemut[$key]['Perjalanan'] as $keyx => $value) 
						{
							if($keyx<(count($perjalanansemut[$key]['Perjalanan'])-1)) //jika indexnya kurang dari count perjalanan semut
							{
								$index=$perjalanansemut[$key]['Perjalanan'][$keyx+1];
								$jarak+=$objj->graph[$value][$index];
							}

						}
						$perjalanansemut[$key]['Jarak']=$jarak;
								//echo "<pre>"; print_r($perjalanansemut[$key]);
						$objj->rutesemut['proses'][$key]['jarak']=$jarak;	
					}	

					$jarakminimal=9999999999999;
					$keyminimal;
					$param=false;

					for ($i=1; $i <=$objj->jumlahsemut ; $i++) 
					{ 
						if(in_array($titiktujuan,$perjalanansemut[$i]['Perjalanan']))
						{
							foreach ($perjalanansemut[$i] as $key => $value) 
							{
								$jarak=$perjalanansemut[$i]['Jarak'];
								if($jarakminimal>$jarak)
								{
									$jarakminimal=$jarak;
									$keyminimal=$i;
								}
							}
							$param=true;
						}
					}	

					if($param==true)
					{
						$semutterbaik=$perjalanansemut[$keyminimal];
						//unset($perjalanansemut[$keyminimal]);
						$objj->updatepheromoneglobal($semutterbaik);
							//$this->updatepheromoneglobal($perjalanansemut,"Bukanterbaik");
					}
					else
					{
						$semutterbaik='Tidak menemukan tujuan';
					}
						
					echo "<pre>"; print_r($perjalanansemut);
					echo "<hr width='100%' align='center'><hr width='100%' align='center'>";
						
					echo "JARAK TERBAIK ITERASI ".$siklus;
					// 	//return $perjalanansemut[$keyminimal];
					echo "<pre>"; print_r($semutterbaik);
						// echo "<pre>"; print_r($perjalanansemut);
					
					// $rute=$objj->rutesemut;
					// echo"<pre>";print_r($rute);
					$objj->rutesemut['semutterbaik']=$semutterbaik;
	
					for ($i=0; $i <count($semutterbaik['Perjalanan']) ; $i++) 
					{ 
						if($i<count($semutterbaik['Perjalanan'])-1)
						{
							$fix1=$semutterbaik['Perjalanan'][$i];
							$fix2=$semutterbaik['Perjalanan'][$i+1];
							$objj->rutesemut['semutterbaik']['koordinat'][$i]=$koordx[$fix1][$fix2];
						}
					}

					//untuk animasi
					//$GLOBALS['perubahanphe']=$objj->perubahanphe;;
					//echo"<pre>";print_r($GLOBALS['perubahanphe']);
					//animasi pheromone
					$GLOBALS['phe']=$objj->pheromone;

					return array($semutterbaik,$objj->rutesemut);
				}


				function mainjp($titikawal,$titiktujuan)
				{
					$mulai = microtime(true);

					echo "<center> HASIL PERHITUNGAN ACS JALUR TERPENDEK DARI TITIK ".$titikawal. " KE TITIK ".$titiktujuan." </center><br>";
					$objj=new jalurterpendekacs;
					$objj->parameter();

					//echo "<pre>"; print_r($graph);

					$iterasiterbaik=array();
					$hasiliterasix=array();
					$hasiliterasi=array();
					$hasiliterasianimasi=array();
					$koordx=array();
					$koordx=graphkoordinat();

					for ($i=1; $i <=$objj->iterasi ; $i++) 
					{ 
						echo "<p style='font-size:0.9em'> ITERASI (SIKLUS SEMUT) KE-".$i."</p>";
						$hasiliterasix=jp($titikawal,$titiktujuan,$i);
						$hasiliterasi[$i]=$hasiliterasix[0];
						$hasiliterasianimasi[$i]=$hasiliterasix[1];
						echo "<hr width='100%' align='center'><hr width='65%' align='center'><hr width='35%' align='center'> ";
					}

						
					$pembanding=999999999999;
					$param=false;
					foreach ($hasiliterasi as $key => $value) 
					{
						if ($hasiliterasi[$key]=='Tidak menemukan tujuan')
							continue;
						else
						{
							if($pembanding>$hasiliterasi[$key]['Jarak'])
							{
								$pembanding=$hasiliterasi[$key]['Jarak'];
								$keyminimal=$key;
							}
							$param=true;
						}
					}

						//echo "<pre>"; print_r($hasiliterasi);
					if($param==true)
					{
						$iterasiterbaik[$keyminimal]=$hasiliterasi[$keyminimal];
					}
					else
					{
						$iterasiterbaik='Tidak menemukan titik tujuan yang dicari';
					}
					

					$i=1;
					$ruteterpendek=array();
						// echo "JARAK TERBAIK";
						// echo "<pre>"; print_r($iterasiterbaik);
					if($iterasiterbaik<>'Tidak menemukan titik tujuan yang dicari')
					{
						foreach ($iterasiterbaik as $key1 => $value) 
						{
							$ruteterpendek['Iterasi']=$key1;
							$ruteterpendek['Semut']=$iterasiterbaik[$key1]['Semut'];
							
							foreach ($iterasiterbaik[$key1]['Perjalanan'] as $key => $value) 
							{
								$ruteterpendek['Ruteterpendek'][$i++]=$value;
							}
							
							$ruteterpendek['Bobot']=$iterasiterbaik[$key1]['Jarak'];
						}
					}
					else
					{
						$ruteterpendek='Tidak menemukan titik tujuan yang dicari';
					}

					$akhir = microtime(true);
					
					echo "<br><br>HASIL RUTE TERPENDEK";
					echo "<pre>"; print_r($ruteterpendek);
					echo '<br >Waktu : ', ($akhir - $mulai)/60, '.';


					//untuk animasi
					$phe=$GLOBALS['phe'];
					$objj=new jalurterpendekacs;
					$koordx=array();
					$koordx=graphkoordinat();
					foreach ($ruteterpendek['Ruteterpendek'] as $key => $value) 
					{
						if($key<count($ruteterpendek['Ruteterpendek']))
						{
							//koordinat
							$ruteterpendek['Koordinat'][]="[".$koordx[$value][$ruteterpendek['Ruteterpendek'][$key+1]]."]";
							
							//pheromone
							
							$x=$value."-".$ruteterpendek['Ruteterpendek'][$key+1]."=".$phe[$value][$ruteterpendek['Ruteterpendek'][$key+1]];
							array_push($GLOBALS['perubahanphe'],$x);
						}
					}

					//echo "<pre>"; print_r($phe);
					
					$GLOBALS['ruteakhir']=$ruteterpendek;


					// $hasiliterasianimasi['ruteterpendek']=$ruteterpendek;

					// echo count($ruteterpendek['Ruteterpendek']);
					// for ($i=1; $i <count($ruteterpendek['Ruteterpendek']) ; $i++) 
					// { 
					// 	$fix1=$ruteterpendek['Ruteterpendek'][$i];
					// 	$fix2=$ruteterpendek['Ruteterpendek'][$i+1];
					// 	$hasiliterasianimasi['ruteterpendek']['Koordinat'][$i]=$koordx[$fix1][$fix2];
					// 	//echo $koordx[$fix1][$fix2];
					// }

					//echo "<pre>"; print_r($hasiliterasianimasi);
					return $hasiliterasianimasi;
						
					//return $ruteterpendek;
						
				}

				//$arrrayanimasi=array();

				//$arrrayanimasi=mainjp(13,15);
				//echo "<pre>"; print_r($arrrayanimasi);
				//echo "<pre>"; print_r($ruteakhir);
				function exp2dec($number) 
				{
				    preg_match('/(.*)E-(.*)/', str_replace(".", "", $number), $matches);
				    $num = "0.";
				    while ($matches[2] > 0) 
				    {
				        $num .= "0";
				        $matches[2]--;
				    }
				    return $num . $matches[1];
				}



			?>


			
		</div>
	</div>


	
	
</body>
</html>

<?php

?>