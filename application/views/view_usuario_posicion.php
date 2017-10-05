<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/estilos.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <title>Ubicacion</title>
</head>
<body>
    <div class="main">
        <header>
            <h1>Datos de usuario</h1>
            <h2>No. <?php echo $numero_cuenta;?></h2>
        </header>
        <div class="info">
            <h3>Ultima actualización</h3>
            <p><?php echo $fecha;?></p>
            <h3>Dispositivo</h3>
            <p><?php echo $modelo;?> <br/> <?php echo $mac;?></p>
            <h3>Estado</h3>
            <p><?php echo $estado;?></p>
            <br/>
            <?php
                if($latitud !=null && $longitud != null){
                    echo "<h3>Posición</h3>";
                    echo "<p> $latitud,$longitud </p>";
                }
            ?>
        </div>
    </div>
    <div class="mapcont" id="mapcont">
        <div id="map"></div>
    </div>
    <script>
        var marker;
        var latitud = '<?php echo $latitud;?>';
        var longitud = '<?php echo $longitud;?>';

        function initMap() {
            if (latitud != '' && longitud != '') {
                var loc = {lat: parseFloat(latitud), lng: parseFloat(longitud)};
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 19,
                    center: loc
                });
                marker = new google.maps.Marker({
                    map: map,
                    draggable: false,
                    animation: google.maps.Animation.DROP,
                    position: loc,
                    title: 'posicion del usuario'
                });
                marker.addListener('click', toggleBounce);
            } else {
                var mapvis = document.getElementById('map');
                mapvis.className = "error";
                var contmens = document.createElement('div');
                contmens.className = "contmens";
                var imgcont = document.createElement('div');
                imgcont.className = "imgcont";
                var img = document.createElement('img');
                img.src = '/webservice/img/mapico.png';
                var mensaje = document.createElement('h2');
                var newContent = document.createTextNode("El usuario aun no ha registrado posicion");
                imgcont.appendChild(img);
                mensaje.appendChild(newContent);
                contmens.appendChild(imgcont);
                contmens.appendChild(mensaje);
                mapvis.appendChild(contmens);
            }

        }
        function toggleBounce() {
            if (latitud != '' && longitud != '') {
                if (marker.getAnimation() !== null) {
                    marker.setAnimation(null);
                } else {
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                }
            }
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqTYotN45ZcsGJT4jvTazRsigqy0vdCxA&callback=initMap">
    </script>
</body>
</html>
