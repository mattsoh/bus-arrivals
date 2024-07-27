function toggleExtendedInfo() { 
    var checkbox = document.getElementById('infoSlider').checked;
    var extendedInfo = document.querySelectorAll('.extended');
    var arrivalInfo = document.querySelectorAll('.arrival-time');
    for (var i = 0; i < extendedInfo.length; i++) {
        extendedInfo[i].style.display = checkbox.checked ? 'block' : 'none';
        arrivalInfo[i].className
        var classList = arrivalInfo[i].classList;
        classList.forEach(cls => {
            if (cls.length === 3) {
                classList.toggle(cls, checkbox.checked);
            }
        });

    }
}

function updateArrivalTimes() {
    var arrivalTimes = document.querySelectorAll('.arrival-time');
    arrivalTimes.forEach(function(span) {
        // if (span.classList.contains('no-data')) {
        //     span.textContent = '';
        //     span.classList.remove('wobble');
            
        // }
        seconds--;
        if (minutes === 0 && seconds <= 30) {
            span.classList.add('wobble');
        } else {
            span.classList.remove('wobble');
        }
    });
}

setInterval(updateArrivalTimes, 1000);
updateArrivalTimes();