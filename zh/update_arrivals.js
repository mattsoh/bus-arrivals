let extended = false;
function toggleExtendedInfo() { 
    extended = !extended;
    localStorage.setItem('extended', extended);
    var extendedInfo = document.querySelectorAll('.extended');
    var arrivalInfo = document.querySelectorAll('.arrival-time');
    document.getElementById("infoSlider").checked = extended;
    for (var i = 0;i<extendedInfo.length; i++) {
        extendedInfo[i].style.display = extended ? 'block' : 'none';
    }
    for (var j = 0;j<arrivalInfo.length; j++){
        (extended)? arrivalInfo[j].classList.add("override") :arrivalInfo[j].classList.remove("override") ;
    }
}

function updateArrivalTimes() {
    var arrivalTimes = document.querySelectorAll('.arrival-time');
    arrivalTimes.forEach(function(span) {
        var mins = parseInt(span.getAttribute('mins'));
        var secs = parseInt(span.getAttribute('secs'));
        secs--;
        if (secs < 0){
            secs = 59;
            mins--;
        }
        if (mins < -1) {
            localStorage.setItem('extended', extended);
            location.reload();
        }
        span.setAttribute('secs', secs);
        span.setAttribute('mins', mins);
        if (mins <= 0){
            span.classList.add("wobble");
            span.textContent = "到达";
        }else{
            span.classList.remove('wobble');
            span.textContent = mins + "分钟";
        }
        
    });
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
    var [stops, _] = getStops();
    let stop = document.getElementById('stopName').getAttribute('stop');
    console.log(stop, stops, stops.includes(parseInt(stop)));
    if (stops.includes(stop)) document.getElementById('bookmark').style['display'] = "none";
    setInterval(updateArrivalTimes, 1000);
    setInterval(function() { location.reload(); localStorage.setItem('extended', extended); }, 30000);
    extended = localStorage.getItem('extended') == "false";
    toggleExtendedInfo();
});
    