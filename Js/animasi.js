var peta;
var animasi;
var tombollokasi=false;
var a=0;
var arrayanimasi=[];
//variable yang digunakan untuk menyimpan data infowindow
var infowindowarr=[];
//variable yang digunakan untuk menyimpan data marke
var markerarr=[];
//variable yang digunakan untuk menyimpan data title marker window
var titleinfowindow=[];
//variabel untuk jalur terpendek
var jalurterpendek=[];
//variabel untuk pheromone
var pheromone=[];


var label1=null;
var label2=null;
function gambaralabel(boxText,lat,lng,uk,param)
{
	latlng=new google.maps.LatLng(lat,lng);
	//t='I LOVE YOU JESUS' +n+'Really really love YOU';
	var note = new google.maps.Marker({
        position:latlng,
        map: peta,
        title: "Win",
        icon:'images/.png',
    	//label: { color: '#00aaff', fontWeight: 'bold', fontSize: '18px', text:t }
      });

	var myOptions = {
		 content: boxText
		,disableAutoPan: false
		,maxWidth: 0
		,pixelOffset: new google.maps.Size(-140, 0)
		,zIndex: null
		,boxStyle: { 
		   background: "url('image/x.jpg') no-repeat"
		   ,opacity: 0.75
		  ,width: uk
		 }
		//,closeBoxMargin: "10px 2px 2px 2px"
		//,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
		,infoBoxClearance: new google.maps.Size(1, 1)
		,isHidden: false
		,pane: "floatPane"
		,enableEventPropagation: false
	};

	var ib = new InfoBox(myOptions);
	
	if(param==1)
		ib.open(peta, note);
	else if (param==2)
	{
		if(label1!=null)
		{
			label1.close(peta, note);
		}
			
		ib.open(peta, note);
		label1=ib;
	}	
	else if (param==3)
	{
		if(label2!=null)
		{
			label2.close(peta, note);
		}
			
		ib.open(peta, note);
		label2=ib;
	}
		

}

ganti=false;
counter=0;
function panggilanimasi(iterasi,semut,jalur,jarak,koordi)
{
	if (ganti==false)
	{
		var boxText = document.createElement("div");
	        boxText.style.cssText = " border: 1px solid black; margin-top: 8px; background: LightCyan; padding: 5px;font-Weight:bold";
	        boxText.innerHTML = "Iterasi ke-"+iterasi+ "<br>Semut ke-"+semut+ "<br>Rute : "+jalur+"<br>Jarak : "+jarak/1000+" KM";
	    gambaralabel(boxText, -0.9037333565903537,119.89121119917809,"200px",2);
	}
	else
	{
		var boxText = document.createElement("div");
        boxText.style.cssText = "font-size:1.1em; border: 1px solid black; margin-top: 8px; background: LightCyan; padding: 5px;font-Weight:bold";
        boxText.innerHTML = "Hasil Rute terpendek<br> Iterasi ke-"+iterasi+"<br>Semut ke- : "+semut+ "<br>Rute : "+jalur+"<br>Jarak : "+jarak/1000+" KM";
    	gambaralabel(boxText, -0.903872814106735,119.88710424141163,"200px",1);
    }

    //pheromone
    var boxText2 = document.createElement("div");
    boxText2.style.cssText = "border: 1px solid black; margin-top: 8px; background: LightCyan; padding: 5px; font-Weight:bold";
       	
    text="";
	i=0;
	j=koordi.length;
	waktuanimasi=(200*j)+3000; //waktu
	var set=setInterval(function()
	{
		if(i<j)
		{
			gambarsemut(JSON.parse(koordi[i]));

			//buat pheromone
			//pheromone
			if (ganti==false)
			{
				text+=pheromone[counter]+"<br>";
				boxText2.innerHTML ="Perubahan pheromone <br>"+ text;
				gambaralabel(boxText2, -0.9037333565903537,119.89342352585072,"200px",3);
				counter+=1;
			}

			i++;
		}
		else
		{
			clearInterval(set);
		}
		
		//console.log(koordi[i]);
	}, waktuanimasi); //waktu pergantian titik
}


function timer(ms) {
 return new Promise(res => setTimeout(res, ms));
}



async function onloadhalaman()
{
	var opsipeta=
	{
		zoom:17,
		center:new google.maps.LatLng(-0.9070387663100969,119.88950964658716)
	};

	peta=new google.maps.Map(document.getElementById('kotak2'),opsipeta);
	
	gambarsimpul();
	gambarlokasi();
	gambargraph();
	//gambarsemut();


	if(a==1)
	{
		// console.log(pheromone.length);
		// ya=0;
		var boxText = document.createElement("div");
        boxText.style.cssText = "font-size:1.1em; border: 1px solid black; margin-top: 8px; background: LightCyan; padding: 5px; font-Weight:bold";
        boxText.innerHTML = "Jumlah Semut : "+info[0]+ "<br>Beta : "+info[1]+ 
        "<br>qo : "+info[2]+"<br>Gamma : "+info[3]+ "<br>Rho : "+info[4]+"<br>Alpha : "+info[5] + "<br>Pheromone Awal : "+info[6]+"<br>Iterasi : "+info[7];
		gambaralabel(boxText,-0.9038728141067351,119.8854412718224,"150px",1);

		

		//animasi perjalanan semut
		waktuanimasi=5000;
		sizeanimasi1=Object.keys(arrayanimasi).length;
		for (var i = 1; i <=sizeanimasi1; i++) 
		{

			// (function(i) {
			// setTimeout(function() { 
			// koordi=[];
				sizeanimasi2=Object.keys(arrayanimasi[i]['proses']).length;
				for (var j = 1; j <=sizeanimasi2; j++) 
				{
					// (function(j) {
		   //      	setTimeout(function() 
		   //      	{
						//y=1;
						z=i;
						r=j;
					// var set=setInterval(function()
					// {
						// if(y<=sizeanimasi2)
						// {
							sizeanimasi3=Object.keys(arrayanimasi[z]['proses'][r]['koordinat']).length;
							jalur=Object.keys(arrayanimasi[z]['proses'][r]['jalur']).length;
							var koordi=[];
							var rute="";
							var iterasi=arrayanimasi[z]['iterasi'];
							var semut=arrayanimasi[z]['proses'][r]['semut'];
							var jarak=arrayanimasi[z]['proses'][r]['jarak'];
							for (var k = 0; k < sizeanimasi3; k++) 
							{
								koordi.push(arrayanimasi[z]['proses'][r]['koordinat'][k]);
							};

							for (var l = 0; l < jalur; l++) 
							{
								if (l<(jalur-1))
									rute+=arrayanimasi[z]['proses'][r]['jalur'][l]+"-";
								else
									rute+=arrayanimasi[z]['proses'][r]['jalur'][l];
							};

							//semut
							panggilanimasi(iterasi,semut,rute,jarak,koordi);

							//waktu
							panjangtitik=arrayanimasi[z]['proses'][r]['jalur'].length;
							console.log(panjangtitik);

							jumlahkoordinat=koordi.length;
							waktuanimasi=(((200*jumlahkoordinat)+3000)*panjangtitik)+1000; //waktu
							//waktuanimasi=4000*panjangtitik; 

							//ya+=1;
							await timer(waktuanimasi);//atur cepat atau lambat perulangan semut
		    	};


			// }, 15000*i);
			// })(i);

		};

		// console.log(ya);
		// console.log(jalurterpendek);
		// //animasi rute terpendek
		var koordi=[];
		var rute="";
		iterasi=jalurterpendek['Iterasi'];
		semut=jalurterpendek['Semut'];
		jarak=jalurterpendek['Bobot'];

		sizeanimasi1=Object.keys(jalurterpendek['Koordinat']).length;
		for (var k = 0; k < sizeanimasi1; k++) 
		{
			koordi.push(jalurterpendek['Koordinat'][k]);
		};

		jalur=Object.keys(jalurterpendek['Ruteterpendek']).length;
		for (var l = 1; l <=jalur; l++) 
		{
			if (l<=(jalur-1))
				rute+=jalurterpendek['Ruteterpendek'][l]+"-";
			else
				rute+=jalurterpendek['Ruteterpendek'][l];
		};


		//lastanimation
		// for (var i = 0; i < koordi.length; i++) 
		// {
		// 	gambarjp(JSON.parse(koordi[i]));
		// };


		//lastanimation
		poly=[];
		for (var i = 0; i < koordi.length; i++) 
		{
			k=JSON.parse(koordi[i]);
			for (var j = 0; j < k.length; j++) 
			{
			    poly.push(new google.maps.LatLng(k[j][0],k[j][1])); 
			};
		};
		pol(poly);
		

		ganti=true;
		await timer(1000);
		panggilanimasi(iterasi,semut,rute,jarak,koordi);

		
	}

 

	google.maps.event.addListener(peta, 'click', function( event )
	{
		if(tombollokasi==true)
		{
			if (window.confirm("Apakah anda ingin menambahkan lokasi baru (latitude : "+event.latLng.lat()+" , "+"longitude : "+event.latLng.lng()+') ini pada peta ?' ))
			{
			    window.location.href = 'php/simpanlokasi.php?lat='+event.latLng.lat()+"&lng="+event.latLng.lng(); 
			}
			
		}
	});

	
}

//ketika klik tombol lokasi
function tombollokasii()
{
	if(document.getElementById('lokasi').innerHTML=='Aktifkan Penginputan Lokasi')
	{
		tombollokasi=true;
		document.getElementById('lokasi').innerHTML='Nonaktifkan Penginputan Lokasi';
	}
	else
	{
		tombollokasi=false;
		document.getElementById('lokasi').innerHTML='Aktifkan Penginputan Lokasi';
	}

}

//ketika klik tombol simpul
function tombolsimpul()
{

	if(document.getElementById('lihatsimpul').innerHTML=='Aktifkan Lihat Nomor Simpul')
	{
		for (var i = 0; i < infowindowarr.length; i++) 
		{
			infowindowarr[i].setContent(titleinfowindow[i]);
			infowindowarr[i].open(peta,markerarr[i]);
		};
		document.getElementById('lihatsimpul').innerHTML='Nonaktifkan Lihat Nomor Simpul';
	}
	else
	{
		for (var i = 0; i < infowindowarr.length; i++) 
		{
			infowindowarr[i].close();
		};
		document.getElementById('lihatsimpul').innerHTML='Aktifkan Lihat Nomor Simpul';
	}
	
}

//gambar graph
function gambargraph()
{  
	jQuery.ajax({
    type: "POST",
    async: false,
    url: "php/garis.php",
    dataType: "json",
    success: function(result)
    {
    	var koor=result;
    	animasi=result;
		
		$.each(koor, function(key1, value1) 
		{
			$.each(value1, function(key2, value2) 
			{
				poly=[];
				k=JSON.parse(value2);
				for (var j = 0; j < k.length; j++) 
		        {
		            poly.push(new google.maps.LatLng(k[j][0],k[j][1])); 
		        };
		        polyline(poly,key1,key2);
			});
		});	

    },
    error:function()
    {
        console.log("Error")
    }
    });

}

//gambar lokasi
function gambarlokasi()
{
	jQuery.ajax({
    type: "POST",
    async: false,
    url: "php/lokasi.php",
    dataType: "json",
    success: function(result)
    {
    	var lokasi=result;
    	
    	for (var i = 0; i < lokasi.length; i++) 
    	{
    		l=JSON.parse(lokasi[i]);
    		//console.log(l);
			var lokasixx = new google.maps.LatLng(l[0],l[1])
			var marker = new google.maps.Marker({
				position: lokasixx,
				map: peta,
				title: " "+l[0]+','+l[1],
				icon:'images/1.png',
			});

			var konten_marker = "<div id='infoWindow'>" +l[0]+','+l[1]+ "</div>";
			var infowindow_marker = new google.maps.InfoWindow();
			
			google.maps.event.addListener(marker,'click', (function(marker,konten_marker,infowindow_marker)
			{ 
				return function() {
				infowindow_marker.setContent(konten_marker);
				infowindow_marker.open(peta,marker);
				};
			})(marker,konten_marker,infowindow_marker));
    	};

    	

    },
    error:function()
    {
        console.log("Error")
    }
    });
}

//gambar simpul
function gambarsimpul()
{
	jQuery.ajax({
    type: "POST",
    async: false,
    url: "php/simpul.php",
    dataType: "json",
    success: function(result)
    {
    	var simpul=result;

		$.each(simpul, function(key1, value1) 
		{
			k=JSON.parse(value1);
				
			var a=key1;
			var x=" "+a;
			var lokasi = new google.maps.LatLng(k[0],k[1])
			var marker = new google.maps.Marker({
			    position: lokasi,
			    map: peta,
			    title: " "+a,
			    //label: { color: 'black', fontWeight: 'bold', fontSize: '12px', text:x },
			    icon:'images/4.png',
			});
			var konten_marker = "<div id='infoWindow'>" +a+ "</div>";
			var infowindow_marker = new google.maps.InfoWindow();
			infowindowarr.push(infowindow_marker);
			markerarr.push(marker);
			z=" "+a+" ";
			titleinfowindow.push(z);
			// infowindow_marker.setContent(konten_marker);
			// infowindow_marker.open(peta,marker);
		
			google.maps.event.addListener(marker,'click', (function(marker,konten_marker,infowindow_marker)
			{ 
				return function() {
				infowindow_marker.setContent(konten_marker);
					infowindow_marker.open(peta,marker);
				};
			})(marker,konten_marker,infowindow_marker)); 

		});

		//console.log(koor);

    },
    error:function()
    {
        console.log("Error")
    }
    });
}

//gambar semut
//hanya buat inisialisasi
semutygjalan=[];
var pilihanlokasi=
{
	map: peta,
};
var mark = new google.maps.Marker(pilihanlokasi);
semutygjalan.push(mark);

function gambarsemut(koordinat)
{
	semutygjalan[0].setMap(null);

    var pilihanlokasi=
    {
		map: peta,
		icon:'images/5.png',
    };

    var mark = new google.maps.Marker(pilihanlokasi);
    semutygjalan[0]=mark;

    var kord=[];
	var poly=[];
	a=0;
	warna2=warna2+1;

	for (var i = 0; i < koordinat.length; i++) 
	{
    	kord.push(new google.maps.LatLng(koordinat[i][0],koordinat[i][1]));
    };

	
	for (let i=0; i<koordinat.length; i++) 
	{
		setTimeout( function timer()
		{
			if(i<koordinat.length-1)
			{
				// poly.push(kord[i]);
				mark.setPosition(kord[i]);
				// warna2=255; 
			    // pol(poly);	
			}
			else
			{
				// poly.push(kord[i]);
				mark.setPosition(kord[i]);
				// warna2=255; 
			    // pol(poly);	
			    //mark.setMap(null);
			}
		    
	    }, i*200); //atur cepat atau lambat perulangan pindah koordinat

	}

	//marker.setMap(null);
}

function wait(ms){
   var start = new Date().getTime();
   var end = start;
   while(end < start + ms) {
     end = new Date().getTime();
  }
}


//inisialisasi warna
var warna1=70;
var warna2=130;
var warna3=180;
//variable untuk menyimpan data garis secara global
var gambargaris=new Array;

//function polyline(poly,key1,key2)
function pol(pol)
{

   var garis=new google.maps.Polyline({
                        path:pol,
                        strokeColor:'#4169E1',
                        strokeOpacity: 0.5,
                        strokeWeight: 12,
                        zIndex: 1,
                    });

    //gambargaris.push({[key1]:{[key2]:garis}});
   
    garis.setMap(peta);

}
function polyline(poly,key1,key2)
{

    garis=new google.maps.Polyline({
                        path:poly,
                        strokeColor:'rgb('+warna1+','+warna2+','+warna3+')',
                        strokeWeight:4,
                    });

    gambargaris.push({[key1]:{[key2]:garis}});
   
    garis.setMap(peta);

}

// function gambarjp(koordinat1)
// {
// 	var lat=[];
// 	var lng=[];
// 	//memisahkan lat dan lng
// 	for (var i = 0; i < koordinat1.length; i++) 
// 	{
// 		lat.push(koordinat1[i][0]);
// 		lng.push(koordinat1[i][1]);
// 	};

// 	var arraylat;
// 	var arraylng;

// 	for (var i = 0; i < lat.length; i++) 
// 	{
// 		if (i===lat.length-1)
// 		{
// 			break;
// 		}

// 		arraylat=new google.maps.LatLng(lat[i],lng[i]);
// 		arraylng=new google.maps.LatLng(lat[i+1],lng[i+1]);
// 	};

//    	var poly=new Array();
// 	for (var i = 0; i < lat.length; i++) 
// 	{
// 		var pos = new google.maps.LatLng(lat[i],lng[i]);
// 		poly.push(pos);

// 		var polyOptions = {
// 			path:poly,
// 		    strokeColor:'yellow',
//             strokeWeight:4,
//             // editable:true,
//             zIndex: 1,
// 			};
// 	 };

// 	var polyline = new google.maps.Polyline(polyOptions);
//     polyline.setMap(peta);

// }

//proses algoritma
function prosesalgoritma()
{
	warna1=0;
	warna2=0;
	warna3=255;

	$.each(gambargaris, function(key1, value1) 
	{
		$.each(value1, function(key2, value2) 
		{
			$.each(value2, function(key3, value3) 
			{
				value3.setOptions({strokeColor: 'rgb('+warna1+','+warna2+','+warna3+')'});
				// warna1=warna1+20;
				// warna2=warna2+8;
				// warna3=warna3+10;
			});
		});
	});	

	warna1=137;
	warna2=196;
	warna3=24;
	
	// $.each(gambargaris, function(key1, value1) 
	// {
	// 	$.each(value1, function(key2, value2) 
	// 	{
	// 		if (key2==6)
	// 		{
	// 			$.each(value2, function(key3, value3) 
	// 			{
	// 				if (key3==8)
	// 				{
	// 					console.log(key1);
	// 				}
	// 			});
	// 		}
			
	// 	});
		
	console.log(gambargaris[21][8][6]);
	console.log(gambargaris[17][6][8]);

	gambargaris[21][8][6].setOptions({strokeColor: 'rgb('+warna1+','+warna2+','+warna3+')'});
	gambargaris[17][6][8].setOptions({strokeColor: 'rgb('+warna1+','+warna2+','+warna3+')'});

	console.log(gambargaris);

}

// fungsi yang digunakan saat selesai melakukan perhitungan
function selesai()
{
	warna1=70;
	warna2=130;
	warna3=180;

	$.each(gambargaris, function(key1, value1) 
	{
		$.each(value1, function(key2, value2) 
		{
			$.each(value2, function(key3, value3) 
			{
				value3.setOptions({strokeColor: 'rgb('+warna1+','+warna2+','+warna3+')'});
				// warna1=warna1+20;
				// warna2=warna2+8;
				// warna3=warna3+10;
			});
		});
	});	

}























