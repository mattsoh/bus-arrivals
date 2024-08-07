function getNearestStops() {
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    function success(pos) {
        const latitude = pos.coords.latitude;
        const longitude = pos.coords.longitude;
        
        fetch(`/getNearestStops?lat=${latitude}&lng=${longitude}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json(); // or response.text() if you expect text
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
getNearestStops();