<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&signed_in=false&language=es"></script>
<script>
function initialize() {
	var myLatlng = new google.maps.LatLng(19.364601, -99.181830);
	
	var mapOptions = {
		zoom: 16,
		center: myLatlng
	};

	var map = new google.maps.Map(document.getElementById('mapa-ubicacion'), mapOptions);

	var infowindow;

	var request = { placeId: 'ChIJDZOUBPf_0YURnWxSmr_tkao' };

		service = new google.maps.places.PlacesService(map);
		service.getDetails(request, callback);

		infowindow = new google.maps.InfoWindow({maxWidth:200});

		function createMarker(placeMarker) {
	        var marker = new google.maps.Marker({
	            map: map,
	            position: placeMarker.geometry.location
	        });

	        var info = "<h6>"+placeMarker.name+"</h6>";
	        info += "<p>"+placeMarker.adr_address+"</p>";
	        info += "<p>"+placeMarker.formatted_phone_number+"</p>";
            infowindow.setContent(info);
            infowindow.open(map, marker);
	    }

		function callback(place, status) {
		  if (status == google.maps.places.PlacesServiceStatus.OK) {
		    createMarker(place);
		  }
		}
  
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>

<div id="contenido-titulo">
	<h1>Contacto</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url(); ?>images/mundo.png">
	</div>
</div>

<br>
<br>
<div id="contenido-texto">
	Oficina del Consorcio Nacional de Recursos de Información Científica y
	Tecnológica <br /> Av. Insurgentes Sur 1582 <br /> Col. Crédito
	Constructor, Deleg. Benito Juárez <br /> C.P. 03940 México, D.F <br />
	<hr />

	Tel: 5322 7700 <br /> ext 4020 al 4026 <br /> consorcio@conacyt.mx <br />
	http://www.conricyt.mx <br /> <br />
	<div id="mapa-ubicacion"></div>
	

</div>

<br>
<br>