function getStops() {
    let stops = JSON.parse(localStorage.getItem('stops'));
    let stopNames = JSON.parse(localStorage.getItem('stopNames'));
    // console.log(stops,stopNames,(stops&&stopNames),stops.length == stopNames.length)
    // console.log(stops.length,stopNames.length)
    // console.log(stops,stopNames)
    if (stops && stopNames && stops.length == stopNames.length){
        // console.log("returning ", stops,stopNames)
        return [stops,stopNames];
    }else{ 
        return[[],[]];
    }
}
function removeStop(stop){
    var [stops,stopNames] = getStops();
    if (stops.includes(stop)) {
        let index = stops.indexOf(stop);
        stops.splice(index, 1);
        stopNames.splice(index,1);
        if (stops.length == stops.length){
            localStorage['stops'] = JSON.stringify(stops);
            localStorage['stopNames'] = JSON.stringify(stopNames);
        }else{
            localStorage.clear();
        }
        console.log("saved");
    }else{
        console.log("stop not in bookmarks");
    }
    location.reload();
}
function query(stop) {
    var form = document.getElementById("busStopForm");
    form.elements['busStopCode'].value = stop;
    form.submit();
}
function getNearestStops() {
    console.log("running");
    let options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    };
    let container = document.getElementById("nearby-stops");
    let button = document.getElementById("nearby-button");
    function success(pos) {
        let latitude = pos.coords.latitude;
        let longitude = pos.coords.longitude;
        
        fetch(`/getNearestStops.php?lat=${latitude}&long=${longitude}`)
        .then(response => {
            if (!response.ok) {
                window.alert(`Could not connect to server (${response.statusText}). This could be a fault on our part, but please make sure that you have an active internet connection.`);
            }
            return response.json();
        })
        .then(data => {
            container.innerHTML = "";
            data.forEach(stop => {
                let stopElement = document.createElement("li");
                stopElement.onclick = function(stop) { return function() { query(stop[0]); } }(stop);
                stopElement.textContent = `${stop[1]} (${stop[0]})`;
                stopElement.classList.add("stop");
                container.appendChild(stopElement);
            });
            container.style.display = "";
            button.textContent = "refresh";
            button.disabled = false;
        }) 
    }
    function error(err) {
        window.alert(`We couldn't determine your current location (${err.message}). Please ensure that location services are enabled on your device.`);
        button.disabled = false;
    }
    button.disabled = true;
    navigator.geolocation.getCurrentPosition(success,error,options);
}

document.addEventListener('DOMContentLoaded', function() {
    let [stops, stopNames] = getStops();
    let container = document.getElementById('bookmarks');
    // container.innerHTML = '';
    console.log(stops);
    if (stops.length == 0) container.style.display = "none";
    for (var i = 0; i < stops.length; i++) {
        let stopElement = document.createElement('li');
        let nameSpan = document.createElement('span');
        let stopSpan = document.createElement('span');

        nameSpan.textContent = stopNames[i]+' ';
        nameSpan.onclick = function(stop) { return function() { query(stop); } }(stops[i]);
        stopElement.classList.add("stop");
        stopSpan.classList.add("code")
        stopSpan.textContent = `(${stops[i]})`;
        stopSpan.onclick = function(stop) { return function() { removeStop(stop); } }(stops[i]);
        stopElement.appendChild(nameSpan);
        stopElement.appendChild(stopSpan);
        console.log(nameSpan, stopSpan);
        container.appendChild(stopElement);
    }
    
});
