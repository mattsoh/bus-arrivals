document.getElementById('busStopForm').addEventListener('submit', function(event) {
    const input = document.getElementById('busStopCode');
    const errorMessage = document.getElementById('error-message');
    const submitButton = document.getElementById('submit-button');
    
    const value = input.value;
    if (/^\d{5}$/.test(value)) {
        errorMessage.style.display = "none"; 
        input.classList.remove('input-error');
        submitButton.classList.remove('flash');
    } else {
        event.preventDefault();
        errorMessage.style.display = "block";
        errorMessage.textContent = "Error! Please enter a 5-digit code.";
        input.classList.add('input-error');
        submitButton.classList.remove('flash');
        submitButton.classList.add('flash');
    }
});
