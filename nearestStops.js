function getNearestStops() {
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    function success(pos) {
        const latitude = pos.coords.latitude;
        const longitude = pos.coords.longitude;
        
        fetch(`/getNearestStops.php?lat=${latitude}&long=${longitude}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Could not connect to server' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
        })  
        .catch(error => {
            console.error('Error finding nearest stops:', error);
        });
    }
    function error(err) {
        console.warn(`Unable to get current location (${err.code}: ${err.message})`);
    }
    navigator.geolocation.getCurrentPosition(success,error,options);
}