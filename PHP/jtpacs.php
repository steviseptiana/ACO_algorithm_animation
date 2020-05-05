<?php
	ini_set('max_execution_time', 0);

	class jalurterpendekacs
	{
		//graph
		public $graph;

		//parameter
		public $jumlahsemut;
		public $beta;
		public $qo;
		public $gamma;
		public $rho;
		public $alpha;
		public $iterasi;

		//inversjarak dan pheromone
		public $inversjarak;
		public $pheromone;

		//titik awal dan tujuan
		public $titikawal;
		public $titiktujuan;

		//tabulist
		public $tabulist=array();

		public $perubahanphe=array();


		function random($min, $max, $desimal = '0')
		{
		    $desimal = +('1'.$desimal);
		    $min = floor($min*$desimal);
		    $max = floor($max*$desimal);
		    $rand = mt_rand($min, $max) / $desimal;
		    return $rand;
		}

		function parameter()
		{
			$this->jumlahsemut=10;
			$this->beta=2;
			$this->qo=0.6;
			$this->gamma=0.1;
			$this->rho=0.1;
			$this->alpha=0.1;
			$this->iterasi=2;
		}

		function transisistatus($titik)
		{
			 //panjang array ttik terhubung
			$q=$this->random(0,1,'00'); //random q
			$pembanding1=-999999999999999;
			$pembanding2=9999999999999999;
			
			$titikterpilih=-1; //sebagai parameter saja, kalau titiknya tidak terhubung k manapun
			
			//pemilihan persamaan transisi status dan update pheromone lokal
			if ($q<=$this->qo)
			{

				foreach ($this->graph[$titik] as $key => $value) 
				{
					$transisistatus1=$this->pheromone[$titik][$key]*pow($this->inversjarak[$titik][$key],$this->beta);
				
					if(round($pembanding1,1000)<round($transisistatus1,1000) and round($pembanding1,1000)!=0 and !(in_array($key, $this->tabulist)) ) //pembanding lebih kecil dari hasil transisi status dan titik nya tidak ada dalam tabu list
					{
						$pembanding1=$transisistatus1;
						$titikterpilih=$key;
					}
					
				}

				//$titikterpilih=array_search(max($simpannilaits1),$simpannilaits1);
			}
			else
			{
				
				$transisistatuspembagi=array();
				$sigmatransisistatus=0;

				$randommaxmin=array('Max','Min');
				$random=array_rand($randommaxmin,1);

				foreach ($this->graph[$titik] as $key => $value) 
				{
					$sigmatransisistatus+=$this->pheromone[$titik][$key]*pow($this->inversjarak[$titik][$key],$this->beta);
					$transisistatuspembagi[$key]=$this->pheromone[$titik][$key]*pow($this->inversjarak[$titik][$key],$this->beta);
					
				}


				if (round($sigmatransisistatus,1000)<=0)
				{
					$titikterpilih=-1;
					
				// 	//sebenarnya jumlah pheromone tidak 0 tapi php tidak bisa handle 0 koma yang terlalu besar jadi nilai pheromonenya di baca 0 oleh php
				}
				else
				{
					//echo "hai<br>";
					foreach ($this->graph[$titik] as $key => $value) 
					{
						$transisistatus2=round($transisistatuspembagi[$key],1000)/round($sigmatransisistatus,1000);
						if ($random==0) //mengambil nilai max
						{
							if(round($pembanding1,1000)<round($transisistatus2,1000) and !(in_array($key, $this->tabulist)) ) //pembanding lebih kecil dari hasil transisi status dan titik nya tidak ada dalam tabu list
							{
								$pembanding1=$transisistatus2;
								$titikterpilih=$key;
							}
						}
						else
						{
							if(round($pembanding2,1000)>round($transisistatus2,1000) and !(in_array($key, $this->tabulist)) ) //pembanding lebih kecil dari hasil transisi status dan titik nya tidak ada dalam tabu list
							{
								$pembanding2=$transisistatus2;
								$titikterpilih=$key;
							}
						}
					}

				}
			}

			if ($titikterpilih<>-1)
			{
				$this->updatepheromonelokal($titik,$titikterpilih);
			}
			return $titikterpilih;
		}

		function updatepheromonelokal($titik1,$titik2)
		{
			//echo $titik1." ".$titik2;
			$pheromoneterhubung=array();
			foreach ($this->pheromone[$titik1] as $key => $value) 
			{
				$pheromoneterhubung[$key]=$value;
			}

			$maxpheromone=max($pheromoneterhubung); //max pheromone yg terhubung dengan titik awal
			
			if(array_key_exists($titik1,$this->graph))
			{
				if (array_key_exists($titik2, $this->graph[$titik1])) 
				{
					$this->pheromone[$titik1][$titik2]=((1-$this->rho)*$this->pheromone[$titik1][$titik2]) + ($this->rho*($this->gamma*$maxpheromone));
					//$this->pheromone[$titik1][$titik2]=((1-$this->rho)*$this->pheromone[$titik1][$titik2]) + ($this->rho*(1/(count($this->graph)*$this->graph[$titik1][$titik2])));
					//ganti rumus
					
				}
			}

			if(array_key_exists($titik2,$this->graph))
			{
				if (array_key_exists($titik1, $this->graph[$titik2])) 
				{
					$this->pheromone[$titik2][$titik1]=((1-$this->rho)*$this->pheromone[$titik2][$titik1]) + ($this->rho*($this->gamma*$maxpheromone));
					//$this->pheromone[$titik2][$titik1]=((1-$this->rho)*$this->pheromone[$titik2][$titik1]) + ($this->rho*(1/(count($this->graph)*$this->graph[$titik2][$titik1])));
				
				}
			}

		
		}

		function updatepheromoneglobal($array)
		{
			//jalurterbaik
			if(!empty($array))
			{
				foreach ($array['Perjalanan'] as $key => $arr) 
				{
					if( $key<count($array['Perjalanan'])-1)
					{
						$a=$array['Perjalanan'][$key];
						$b=$array['Perjalanan'][$key+1];

						if (array_key_exists($b, $this->pheromone[$a])) 
						{
							$this->pheromone[$a][$b]=((1-$this->alpha)*$this->pheromone[$a][$b])+($this->alpha*(1/$array['Jarak']));
						}
						if (array_key_exists($a, $this->pheromone[$b])) 
						{
							$this->pheromone[$b][$a]=((1-$this->alpha)*$this->pheromone[$b][$a])+($this->alpha*(1/$array['Jarak']));
						}

						$cek1[$a]=$b;
						$cek2[$b]=$a;
					}
				}

				//bukan jalur terbaik
				//$alpha=$this->alpha;
				foreach ($this->pheromone as $key1 => $arr) 
				{
					foreach ($arr as $key2 => $value) 
					{
						if (array_key_exists($key1, $cek1) and $cek1[$key1]==$key2) 
						{
							continue;
						}
						else if (array_key_exists($key1, $cek2) and $cek2[$key1]==$key2) 
						{
							continue;
						}
						else
						{
							$this->pheromone[$key1][$key2]=((1-$this->alpha)*$this->pheromone[$key1][$key2])+($this->alpha*0);
						}
					}
				}
			}
			else
			{
				//bukan jalur terbaik
				//$alpha=$this->alpha;
				foreach ($this->pheromone as $key1 => $arr) 
				{
					foreach ($arr as $key2 => $value) 
					{
						$this->pheromone[$key1][$key2]=((1-$this->alpha)*$this->pheromone[$key1][$key2])+($this->alpha*0);
					}
				}
			}
			
		}


			
	}
	//30-3000
	//iterasi 100 maksgraph*50
	//Waktu : 12210.132378817.
?>