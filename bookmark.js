function update(stop) {
    console.log(`Updating ${stop}`);
}

function save(stops, stopNames){
    if (stops.length == stops.length){
        localStorage['stops'] = JSON.stringify(stops);
        localStorage['stopNames'] = JSON.stringify(stopNames);
    }else{
        localStorage.clear();
    }
    console.log("saved");
}
function saveStop(stop){
    var [stops, stopNames] = getStops();
    if (!stops.includes(toString(stop))) {
        console.log(stop,toString(stop));
        stops.push(''+stop);
        stopNames.push(document.getElementById('stopName').textContent);
        save(stops,stopNames)
        window.alert("Saved stop")
    } else {
        window.alert("Stop already bookmarked");
    }
}
function getStops() {
    let stops = localStorage['stops'];
    let stopNames = localStorage['stopNames'];
    console.log(stops,stopNames)
    if (stops && stopNames && JSON.parse(stops).length == JSON.parse(stopNames).length){
        console.log("returning ", stops,stopNames)
        return [JSON.parse(stops),JSON.parse(stopNames)];
    }else{ 
        return[[],[]];
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var [stops, stopNames] = getStops();
    let stop = document.getElementById('stopName').getAttribute('stop');
    if (stops.includes(parseInt(stop))){
        document.getElementById('bookmark').style['display'] = "none";
    }
});
