function update(stop) {
    console.log(`Updating ${stop}`);
}

function save(stops, stopNames){
    if (stops.length == stops.length){
        localStorage['stops'] = stops;
        localStorage['stopNames'] = stopNames;
    }else{
        localStorage['stops'] = "";
        localStorage['stopNames'] = "";
    }
    console.log("saved");
}

function saveStop(stop, stopName) {
    var [stops, stopNames] = getStops();
    console.log(stops,stopNames)
    if (!stops.includes(stop)) {
        stops.push(stop);
        stopNames.push(stopName)
        save(stops,stopNames)
        
    } else {
        console.log("Stop already bookmarked");
    }
}

function removeStop(stop) {
    var {stops,stopNames} = getStops();
    if (stops.includes(stop)) {
        let index = stops.indexOf(stop);
        stops.splice(index, 1);
        stopNames.splice(index,1);
        save(stops,stopNames);
    }
}

function getStops() {
    const stops = localStorage.getItem('stops');
    const stopNames = localStorage.getItem('stopNames')
    console.log(stops,stopNames,stops != null,stopNames != null)
    if ((stops != null) && (stopNames != null)){
        console.log("returning ", stops,stopNames)
        return [stops,stopNames];
    }else{ 
        return[[],[]];
    }
}

function displayStops() {
    const [stops, stopNames] = getStops();
    const container = document.getElementById('bookmarked-stops');
    container.innerHTML = '';
    console.log(stops)
    stops.forEach((stop, index) => {
        const stopElement = document.createElement('div');
        stopElement.textContent = `stop: ${stopNames[index]}`;
        container.appendChild(stopElement);
    });

}

// document.addEventListener('DOMContentLoaded', () => {
    
//     displayStops();
// });
