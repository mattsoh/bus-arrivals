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
            span.textContent = "Arriving";
        }else{
            span.classList.remove("wobble");
            span.textContent = mins + " mins" + secs;
        }
        
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setInterval(updateArrivalTimes, 1000);
    // setInterval(function() { location.reload(); localStorage.setItem('extended', extended); }, 30000);
    extended = localStorage.getItem('extended') == "false";
    toggleExtendedInfo();
});
    