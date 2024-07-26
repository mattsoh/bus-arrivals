function toggleExtendedInfo() { 
    var checkbox = document.getElementById('infoSlider');
    var extendedInfo = document.querySelectorAll('.extended');
    for (var i = 0; i < extendedInfo.length; i++) {
        extendedInfo[i].style.display = checkbox.checked ? 'block' : 'none';
    }
}

function updateArrivalTimes() {
    var now = new Date();
    var arrivalTimes = document.querySelectorAll('.arrival-time');
    arrivalTimes.forEach(function(span) {
        if (span.classList.contains('no-data')) {
            span.textContent = '';
            span.classList.remove('wobble');
        } else {
            var estimatedArrival = new Date(span.getAttribute('data-estimated-arrival'));
            var diff = estimatedArrival - now;
            if (diff < 0) {
                span.textContent = 'Arriving ';
            } else {
                var minutes = Math.floor(diff / 60000);
                var seconds = Math.floor((diff % 60000) / 1000);
                span.textContent = minutes + ' mins ' + seconds + ' secs';
                if (minutes === 0 && seconds <= 30) {
                    span.classList.add('wobble');
                } else {
                    span.classList.remove('wobble');
                }
            }
        }
    });
}

setInterval(updateArrivalTimes, 1000);
updateArrivalTimes();