// Function to initialize the map
function initializeMap(lat, lon, ip) {
    const map = L.map('map').setView([lat, lon], 13);

    // Add OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Add a marker
    L.marker([lat, lon])
        .addTo(map)
        .bindPopup(`<b>Your IP:</b> ${ip}<br><b>Coordinates:</b> ${lat}, ${lon}`)
        .openPopup();
}

// Function to fetch user's geolocation
async function fetchLocation() {
    const locationInfo = document.getElementById('location-info');
    try {
        // Replace 'your_actual_token_here' with your ipinfo.io token
        const response = await fetch("https://ipinfo.io/json?token=0c4d99affbc93d");
        if (!response.ok) {
            throw new Error(`API Error: ${response.statusText}`);
        }
        const data = await response.json();

        // Extract location details
        const [lat, lon] = data.loc.split(',');
        const ip = data.ip;
        const city = data.city;
        const region = data.region;

        locationInfo.textContent = `You are in ${city}, ${region}.`;
        initializeMap(lat, lon, ip);
    } catch (error) {
        locationInfo.textContent = "Unable to fetch location.";
        console.error("Error fetching location:", error.message);
    }
}

// Load location on page load
document.addEventListener('DOMContentLoaded', fetchLocation);
