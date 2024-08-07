function getStops() {
    let stops = JSON.parse(localStorage.getItem('stops'));
    let stopNames = JSON.parse(localStorage.getItem('stopNames'));
    console.log(stops,stopNames,(stops&&stopNames),stops.length == stopNames.length)
    console.log(stops.length,stopNames.length)
    console.log(stops,stopNames)
    if (stops && stopNames && stops.length == stopNames.length){
        console.log("returning ", stops,stopNames)
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
        save(stops,stopNames);
    }
}
function queryBookmark(stop) {
    var form = document.getElementById("busStopForm");
    form.elements['busStopCode'].value = stop;
    form.submit();
}
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
                stopElement.onclick = function(stop) { return function() { queryBookmark(stop[0]); } }(stop);
                stopElement.textContent = `${stop[1]} (${stop[0]})`;
                container.appendChild(stopElement);
            });
            container.style.display = "";
            button.textContent = "refresh";
        })  
        // .catch(error => {
        //     window.alert('Error finding nearest stops:', error);
        // });
    }
    function error(err) {
        window.alert(`Unable to get current location (${err.code}: ${err.message})`);
    }
    navigator.geolocation.getCurrentPosition(success,error,options);
}

document.addEventListener('DOMContentLoaded', function() {
    let [stops, stopNames] = getStops();
    let container = document.getElementById('bookmarked-stops');
    container.innerHTML = '';
    // console.log(stops);
    for (var i = 0; i < stops.length; i++) {
        let stopElement = document.createElement('li');
        let stopSpan = document.createElement('span');

        stopElement.innerHTML = `${stopNames[i]} (<span class="bookmark-code" onclick="removeStop(${stops[i]})">${stops[i]}</span>)`;
        stopElement.onclick = function(stop) { return function() { queryBookmark(stop); } }(stops[i]);
        stopElement.classList.add("bookmark");
        stopSpan.classList.add("bookmark-code")
    
        stopElement.appendChild(stopSpan);
        container.appendChild(stopElement);
    }
    
});
