function initMap() {
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: { lat: 25.6816, lng: -100.4636 } // Santa Catarina
    });
    directionsRenderer.setMap(map);

    // 1. Define tu DESTINO ÚNICO (La UT)
    const destinoFinal = "Universidad Tecnológica de Santa Catarina, Nuevo León";

    // 2. Define tus PARADAS (Waypoints)
    const paradasIntermedias = [
        { location: "Soriana Híper La Puerta, Santa Catarina", stopover: true },
        { location: "Plaza Principal de Santa Catarina", stopover: true },
        { location: "Paseo Santa Catarina", stopover: true }
    ];

    // 3. Configura la petición
    const request = {
        origin: "Punto de inicio o ubicación actual", 
        destination: destinoFinal,
        waypoints: paradasIntermedias,
        optimizeWaypoints: true, // Esto ordena las paradas de la forma más rápida
        travelMode: google.maps.TravelMode.DRIVING
    };

    // 4. Dibuja la ruta
    directionsService.route(request, (result, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(result);
        } else {
            console.error("Error al cargar la ruta: " + status);
        }
    });
}

