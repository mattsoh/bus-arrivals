function update(stop) {
    console.log(`Updating ${stop}`);
    displayStops();
}

function saveStop(stop) {
    const stops = getStops();
    if (!stops.includes(stop)) {
        stops.push(stop);
        localStorage.setItem('stops', JSON.stringify(stops));
        update(stop);
        
    } else {
        console.log("Stop already bookmarked");
    }
}

function removeStop(stop) {
    const stops = getStops();
    if (stops.includes(stop)) {
        const index = stops.indexOf(stop);
        stops.splice(index, 1);
        localStorage.setItem('stops', JSON.stringify(stops));
        update(stop);
    }
}

function toggleBookmark(stop) {
    const stops = getStops();
    if (stops.includes(stop)) {
        removeStop(stop);
    } else {
        saveStop(stop);
    }
}

function getStops() {
    const stops = localStorage.getItem('stops');
    return stops ? JSON.parse(stops) : [];
}

function displayStops() {
    const stops = getStops();
    const container = document.getElementById('bookmarked-stops');
    container.innerHTML = '';

    stops.forEach(stop => {
        const stopElement = document.createElement('div');
        stopElement.textContent = stop;
        container.appendChild(stopElement);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const stops = getStops();
    stops.forEach(stop => {
        update(stop);
    });
    displayStops();
});
