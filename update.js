function toggleExtendedInfo() { 
    var extendedInfo = document.querySelectorAll('.extended');
    var arrivalInfo = document.querySelectorAll('.arrival-time');
    for (var i = 0;i<extendedInfo.length; i++) {
        extendedInfo[i].style.display = document.getElementById('infoSlider').checked ? 'block' : 'none';
    }
    for (var j = 0;j<arrivalInfo.length; j++){
        arrivalInfo[j].classList.toggle("override");
    }
}

function updateArrivalTimes() {
    var arrivalTimes = document.querySelectorAll('.arrival-time');
    arrivalTimes.forEach(function(span) {
        var mins = parseInt(span.getAttribute('mins'));
        var secs = parseInt(span.getAttribute('secs'));
        secs--;
        // if (mins ){
        //     if (secs < 0){
        //         secs = 0;
        //     }
        //     mins = 0;
        // } else { 
        // if (isNaN(mins)) mins = 0; 
        if (secs < 0){
            secs = 59;
            mins--;
        }
        span.setAttribute('secs', secs);
        span.setAttribute('mins', mins);
        (mins <= 0) ? span.textContent = "Arriving": span.textContent = mins + " mins" + secs;
    });
}

setInterval(updateArrivalTimes, 1000);
setInterval(function() { location.reload(); }, 30000);

updateArrivalTimes();


setInterval(updateArrivalTimes, 1000);
setInterval(function() {location.reload();}, 30000);

updateArrivalTimes();