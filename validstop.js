document.getElementById('busStopForm').addEventListener('submit', function(event) {
    const input = document.getElementById('busStopCode');
    const errorMessage = document.getElementById('error-message');
    
    const value = input.value;
    if (/^\d{5}$/.test(value)) {
        errorMessage.style.display = "none"; 
        input.style.backgroundColor = "";
        input.style.opacity = "";
    } else {
        event.preventDefault();
        errorMessage.style.display = "block";
        errorMessage.textContent = "Error! Please enter a 5-digit code."
        input.style.backgroundColor = "red";
        input.style.opacity = "0.7"; 
    }
});
