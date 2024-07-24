document.getElementById('busStopForm').addEventListener('submit', function(event) {
    const input = document.getElementById('busStopCode');
    const errorMessage = document.getElementById('error-message');
    
    const value = input.value;
    if (/^\d{5}$/.test(value)) {
        errorMessage.style.display = "none"; 
    } else {
        event.preventDefault();
        errorMessage.style.display = "block";
        console.log("error!")
    }

    // Check if the value is exactly 5 digits
    // if (/^\d{5}$/.test(value)) {
    //     errorMessage.textContent = "";  // Clear any previous error message
    // } else {
    //     event.preventDefault();  // Prevent form submission
    //     errorMessage.textContent = "Please enter exactly 5 digits.";
    // }
});

// function enforceMaxLength(input, maxLength) {
//     if (input.value.length > maxLength) {
//         input.value = input.value.slice(0, maxLength);
//     }
// }
