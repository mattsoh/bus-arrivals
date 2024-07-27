document.getElementById('busStopForm').addEventListener('submit', function(event) {
    const input = document.getElementById('busStopCode');
    const errorMessage = document.getElementById('error-message');
    const submitButton = document.getElementById('submit-button');
    
    const value = input.value;
    submitButton.classList.add('flash');
    setTimeout(() => {
        submitButton.classList.remove('flash');
    }, 500);
    if (/^\d{5}$/.test(value)) {
        errorMessage.style.display = "none"; 
        input.classList.remove('input-error');
    } else {
        event.preventDefault();
        errorMessage.style.display = "block";
        errorMessage.textContent = "Error! Please enter a 5-digit code.";
        input.classList.add('input-error');
        
        // Add the flash class and then remove it after a short delay
         // Adjust the timeout duration as needed
    }
});
