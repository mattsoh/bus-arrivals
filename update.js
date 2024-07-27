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
        var secs = parseInt(span.getAttribute('secs'));
        var mins = null;
        secs--;
        if (span.textContent == "Arriving"){
            if (secs < 0){
                secs = 0;
            }
            mins = 0;
        } else {
            var mins = parseInt(span.textContent.split("mins")[0].trim());
            if (isNaN(mins)) mins = 0; 
            if (secs < 0){
                secs = 59;
                mins--;
            }
        }
        span.setAttribute('secs', secs);
        (mins == 0) ? span.textContent = "Arriving": span.textContent = mins + " mins" + secs;
    });
}

// setInterval(updateArrivalTimes, 1000);
// setInterval(function() { location.reload(); }, 30000);

// updateArrivalTimes();


setInterval(updateArrivalTimes, 1000);
setInterval(function() {location.reload();}, 30000);

updateArrivalTimes();