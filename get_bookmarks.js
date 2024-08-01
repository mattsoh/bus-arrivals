function getStops() {
    const stops = JSON.parse(localStorage.getItem('stops'));
    const stopNames = JSON.parse(localStorage.getItem('stopNames'));
    // console.log(stops,stopNames,(stops&&stopNames),stops.length == stopNames.length)
    // console.log(stops.length,stopNames.length)
    if (stops && stopNames && stops.length == stopNames.length){
        console.log("returning ", stops,stopNames)
        return [stops,stopNames];
    }else{ 
        return[[],[]];
    }
}

function queryBookmark(stop) {
    var form = document.getElementById("busStopForm");
    form.elements['busStopCode'].value = stop;
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const [stops, stopNames] = getStops();
    const container = document.getElementById('bookmarked-stops');
    container.innerHTML = '';
    // console.log(stops);
    for (var i = 0; i < stops.length; i++) {
        const stopElement = document.createElement('div');
        stopElement.textContent = `${stopNames[i]} (${stops[i]})`;
        stopElement.onclick = function(stop) {return function(){queryBookmark(stop);}}(stops[i]);
        // console.log(stopElement.onclick);
        container.appendChild(stopElement);
    }
});
