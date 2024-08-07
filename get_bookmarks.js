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

document.addEventListener('DOMContentLoaded', function() {
    let [stops, stopNames] = getStops();
    let container = document.getElementById('bookmarked-stops');
    container.innerHTML = '';
    // console.log(stops);
    for (var i = 0; i < stops.length; i++) {
        let stopElement = document.createElement('div');
        let stopSpan = document.createElement('span');

        stopElement.innerHTML = `${stopNames[i]} (<span class="bookmark-code" onclick="removeStop(${stops[i]})">${stops[i]}</span>)`;
        stopElement.onclick = function(stop) { return function() { queryBookmark(stop); } }(stops[i]);
        stopElement.classList.add("bookmark");
        stopSpan.classList.add("bookmark-code")
    
        stopElement.appendChild(stopSpan);
        container.appendChild(stopElement);
    }
    
});
