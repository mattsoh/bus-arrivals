let extended = false;
function toggleExtendedInfo() { 
    console.log(extended,!extended,typeof(extended));
    extended = !extended;
    localStorage.setItem('extended', extended);
    console.log("after",extended);
    var extendedInfo = document.querySelectorAll('.extended');
    var arrivalInfo = document.querySelectorAll('.arrival-time');
    document.getElementById("infoSlider").checked = extended;
    for (var i = 0;i<extendedInfo.length; i++) {
        extendedInfo[i].style.display = extended ? 'block' : 'none';
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
        (mins <= 0) ? span.textContent = "Arriving": span.textContent = mins + " mins" + secs;
    });
}

window.onload = function() {
    setInterval(updateArrivalTimes, 1000);
    setInterval(function() { location.reload(); localStorage.setItem('extended', extended); }, 30000);
    extended = localStorage.getItem('extended');
    console.log(extended);
    extended = (extended == "true");
if (extended == true){
    extended = false;
    toggleExtendedInfo();
}
}
    