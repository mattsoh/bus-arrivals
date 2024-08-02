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
    if (!stops.includes(stop)) {
        stops.push(stop);
        stopNames.push(document.getElementById('stopName').textContent);
        save(stops,stopNames)
    } else {
        console.log("Stop already bookmarked");
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
function toggleBookmark(stop, remove) {
    var [stops, stopNames] = getStops();
    console.log(remove);
    if (remove){
        if (!stops.includes(stop)) {
            stops.push(stop);
            stopNames.push(document.getElementById('stopName').textContent);
            save(stops,stopNames)
        } else {
            console.log("Stop already bookmarked");
        }
    }else {
        if (stops.includes(stop)) {
            let index = stops.indexOf(stop);
            stops.splice(index, 1);
            stopNames.splice(index,1);
            save(stops,stopNames);
        }
    }
    
}
function getStops() {
    const stops = localStorage['stops'];
    const stopNames = localStorage['stopNames'];
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
        document.getElementById('bookmark').style['display'] = "hidden";
    }
});
