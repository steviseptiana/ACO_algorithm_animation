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
//var arrayanimasi;

function gambaralabel(boxText)
{
	latlng=new google.maps.LatLng(-0.9041241425483684,119.89121119917809);
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
		  ,width: "280px"
		 }
		//,closeBoxMargin: "10px 2px 2px 2px"
		//,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
		,infoBoxClearance: new google.maps.Size(1, 1)
		,isHidden: false
		,pane: "floatPane"
		,enableEventPropagation: false
	};

	var ib = new InfoBox(myOptions);
	ib.open(peta, note);
	
}


function panggilanimasi(iterasi,semut,jalur,jarak,koordi)
{
	var boxText = document.createElement("div");
        boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: yellow; padding: 5px;";
        boxText.innerHTML = "Iterasi ke-"+iterasi+ "<br>Semut ke-"+semut+ "<br>Rute : "+jalur+"<br>Jarak : "+jarak/1000+" KM";
		
	gambaralabel(boxText);

	i=0;
	j=koordi.length;
	var set=setInterval(function()
	{
		if(i<j)
		{
			gambarsemut(JSON.parse(koordi[i]));
			i++;
		}
		else
		{
			//console.log('selesai');
			clearInterval(set);
		}
		
		//console.log(koordi[i]);
	}, 1000);
}


function onloadhalaman()
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

		sizeanimasi1=Object.keys(arrayanimasi).length;
		for (var i = 1; i <=sizeanimasi1; i++) 
		{
			(function(i) {
			setTimeout(function() { 
			// koordi=[];
				sizeanimasi2=Object.keys(arrayanimasi[i]['proses']).length;
				for (var j = 1; j <=sizeanimasi2; j++) 
				{
					(function(j) {
		        	setTimeout(function() 
		        	{
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

							panggilanimasi(iterasi,semut,rute,jarak,koordi);
						// }
						// else
						// {
						// 	console.log('selesai');
						// 	clearInterval(set);
						// }


					},15000*j);
		    		})(j);

		    	};

					console.log("asjhfsaf   ")
						
						//console.log(koordi[i]);
					//}, 10000);

					
				

			}, 165000*i);
			})(i);
		};

		
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
function gambarsemut(koordinat)
{
    var pilihanlokasi=
    {
		map: peta,
		icon:'images/2.png',
    };

    var mark = new google.maps.Marker(pilihanlokasi);

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
				poly.push(kord[i]);
				mark.setPosition(kord[i]);
				warna2=255; 
			    pol(poly);	
			}
			else
			{
				poly.push(kord[i]);
				mark.setPosition(kord[i]);
				warna2=255; 
			    pol(poly);	
			    mark.setMap(null);
			}
		    
	    }, i*30);

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
                        strokeColor:'rgb('+warna1+','+warna2+','+warna3+')',
                        strokeWeight:4,
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























