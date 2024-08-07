function getNearestStops() {
    let options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    function success(pos) {
        let latitude = pos.coords.latitude;
        let longitude = pos.coords.longitude;
        
        fetch(`/getNearestStops.php?lat=${latitude}&long=${longitude}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('could not connect to server' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            let container = document.getElementById("nearby-stops");
            let button = document.getElementById("nearby-button");
            console.log(data);

            container.innerHTML = "";
            data.forEach(stop => {
                let stopElement = document.createElement("li");
                stopElement.textContent = `${stop[1]} (${stop[0]})`;
                container.appendChild(stopElement);
            });
            container.style.display = "";
            button.style.display = "none";
        })  
        .catch(error => {
            window.alert('Error finding nearest stops:', error);
        });
    }
    function error(err) {
        window.alert(`Unable to get current location (${err.code}: ${err.message})`);
    }
    navigator.geolocation.getCurrentPosition(success,error,options);
}