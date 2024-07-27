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
        // if (span.classList.contains('no-data')) {
        //     span.textContent = '';
        //     span.classList.remove('wobble');
            
        // }
    //     seconds--;
    //     if (minutes === 0 && seconds <= 30) {
    //         span.classList.add('wobble');
    //     } else {
    //         span.classList.remove('wobble');
    //     }
    });
}

setInterval(updateArrivalTimes, 1000);
updateArrivalTimes();