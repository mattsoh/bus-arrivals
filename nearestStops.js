options 
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0,
  
  function success(pos) {
    const crd = pos.coords;
  
    // console.log("Your current position is:");
    // console.log(`Latitude : ${crd.latitude}`);
    // console.log(`Longitude: ${crd.longitude}`);
    // console.log(`More or less ${crd.accuracy} meters.`);
  }
  
  function error(err) {
    console.warn(`ERROR(${err.code}): ${err.message}`);
  }
  function getNearestStops() {
    navigator.geolocation.getCurrentPosition(success,error,options);
      fetch('/getStopName')
          .then(response => {
              if (!response.ok) {
                  throw new Error('Network response was not ok ' + response.statusText);
              }
              return response.json(); // or response.text() if you expect text
          })
          .then(data => {
              console.log(data);
          })
          .catch(error => {
              console.error('There has been a problem with your fetch operation:', error);
          });
  }
  // Example usage
  sendGetRequest('https://api.example.com/data');
  
  }
  
  