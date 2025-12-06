<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<!-- Heatmap -->
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<!-- cloudflare -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Geocoder CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<!-- Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>



<body>
    <div id="search-map">
        <div class="search">
            <input type="text" id="search-input" placeholder="Cari lokasi...">
            <button type="submit" id="search-btn"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="formBlock">
        <form id="#">
            <input type="text" name="start" class="input" id="start" placeholder="Choose Starting Point">
            <input type="text" name="end" class="input" id="destination" placeholder="Choose Destination">
            <button type="submit">Get Direction</button>
        </form>
    </div>

    <div id="map"></div>

    <button class="btn-map" onclick=fullScreenView()>View Full Screen</button>

    <div id="heatmap-legend" class="heatmap-legend">
        <span>Weak</span>
        <div class="heatmap-bar"></div>
        <span>Strong</span>
    </div>


    <div class="coordinate"></div>

    <!-- Tooltip mengikuti mouse -->
    <div id="mouse-follow"></div>

    <!-- Context Menu -->
    <div id="context-menu">
        <ul>
            <li onclick="addMarker()">Tambah Marker</li>
            <li onclick="copyCoord()">Copy Koordinat</li>
            <li onclick="zoomHere()">Zoom di Sini</li>
        </ul>
    </div>
</body>


<!-- Container utama untuk form dan hasil -->
<div class="container-form">
    <!-- Header dengan ikon kalkulator -->
    <h1><i class="fas fa-calculator"></i> Perhitungan Tower Point-to-Point</h1>

    <!-- Form untuk input data tower -->
    <form id="calcForm">
        <!-- Input untuk Tower 1 -->
        <div class="form-group">
            <label for="coord1">Koordinat Tower 1 (lat, lon):</label>
            <input type="text" id="coord1" placeholder="-6.2088, 106.8456" required>
        </div>

        <div class="form-group">
            <label for="height1">Ketinggian Tower 1 (meter):</label>
            <input type="number" id="height1" step="any" required>
        </div>

        <!-- Input untuk Tower 2 -->
        <div class="form-group">
            <label for="coord2">Koordinat Tower 2 (lat, lon):</label>
            <input type="text" id="coord2" placeholder="-7.7956, 110.3695" required>
        </div>

        <div class="form-group">
            <label for="height2">Ketinggian Tower 2 (meter):</label>
            <input type="number" id="height2" step="any" required>
        </div>

        <!-- Tombol submit dengan ikon play -->
        <button type="submit"><i class="fas fa-play"></i> Hitung</button>
    </form>
    <!-- Panel untuk menampilkan hasil perhitungan -->
    <div id="results">
        <h3><i class="fas fa-chart-line"></i> Hasil Perhitungan:</h3>
        <p id="output">Masukkan data dan klik Hitung.</p>
    </div>
</div>


<script>
    // Fungsi untuk menghitung jarak menggunakan formula Haversine (dalam km)
    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius bumi dalam km
        const dLat = (lat2 - lat1) * Math.PI / 180; // Konversi derajat ke radian
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Jarak dalam km
    }

    // Fungsi untuk menghitung kemiringan (elevation angle) dalam derajat
    function calculateElevationAngle(h1, h2, distanceKm) {
        const distanceM = distanceKm * 1000; // Konversi jarak ke meter
        const angleRad = Math.atan((h2 - h1) / distanceM); // Hitung sudut dalam radian
        return (angleRad * 180 / Math.PI).toFixed(2); // Konversi ke derajat dan bulatkan
    }

    document.getElementById('calcForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Ambil input koordinat
        const coord1 = document.getElementById('coord1').value.split(',');
        const coord2 = document.getElementById('coord2').value.split(',');

        const lat1 = parseFloat(coord1[0]);
        const lon1 = parseFloat(coord1[1]);
        const lat2 = parseFloat(coord2[0]);
        const lon2 = parseFloat(coord2[1]);

        const h1 = parseFloat(document.getElementById('height1').value);
        const h2 = parseFloat(document.getElementById('height2').value);

        // Hitung jarak menggunakan Haversine
        const distance = haversineDistance(lat1, lon1, lat2, lon2);

        // Hitung kemiringan
        const elevationAngle = calculateElevationAngle(h1, h2, distance);

        // Selisih ketinggian
        const heightDiff = h2 - h1;

        document.getElementById('output').innerHTML = `
        <strong>Jarak:</strong> ${distance.toFixed(2)} km<br>
        <strong>Kemiringan (Elevation Angle):</strong> ${elevationAngle}°<br>
        <strong>Perbedaan Ketinggian:</strong> ${heightDiff.toFixed(2)} m<br>
        <em>Line-of-sight lebih mudah jika elevation angle positif.</em>
    `;
    });
</script>


<!-- Geocoder JS -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>


<script>
    // Tambah legend ke container map Leaflet
    const mapContainer = document.querySelector('#map');
    mapContainer.appendChild(document.getElementById('heatmap-legend'));

    // ============================
    // INISIALISASI MAP
    // ============================
    var map = L.map('map').setView([-6.200000, 106.816666], 13);

    // Tampilkan marker yg sudah diinput
    var markersGroup = L.featureGroup().addTo(map);

    // DATA TOWER + POLYLINE + HEATMAP (fetch hanya 1x!)
    var towerCoordinates = [];

    fetch('/data/tower')
        .then(response => response.json())
        .then(data => {

            data.forEach(item => {
                let lat = parseFloat(item.latitude);
                let lng = parseFloat(item.longtitude);

                if (!lat || !lng) return; // hindari NaN

                var latlng = [lat, lng];

                // Simpan koordinat
                towerCoordinates.push(latlng);

                // Tambah marker
                var marker = L.marker(latlng)
                    .bindPopup("<b>" + item.nama_tower + "</b>");
                markersGroup.addLayer(marker);
            });

            // Fit map ke marker
            if (markersGroup.getLayers().length > 0) {
                map.fitBounds(markersGroup.getBounds());
            }

            // ==============================
            // 1. POLYLINE
            // ==============================
            if (towerCoordinates.length >= 2) {
                var polyline = L.polyline(towerCoordinates, {
                    color: 'red'
                }).addTo(map);

                map.fitBounds(polyline.getBounds());
            }

            // ==============================
            // 2. HEATMAP
            // ==============================
            let heatPoints = [];

            // generate heat di sepanjang polyline
            for (let i = 0; i < towerCoordinates.length - 1; i++) {
                let start = towerCoordinates[i];
                let end = towerCoordinates[i + 1];

                // 40 titik interpolasi
                for (let t = 0; t <= 40; t++) {
                    let lat = start[0] + (end[0] - start[0]) * (t / 40);
                    let lng = start[1] + (end[1] - start[1]) * (t / 40);

                    let intensity = 1 - (t / 40); // semakin jauh semakin redup

                    heatPoints.push([lat, lng, intensity]);
                }
            }

            console.log("Heatpoints:", heatPoints); // cek muncul di console

            if (heatPoints.length > 0) {
                L.heatLayer(heatPoints, {
                    radius: 55,
                    blur: 35,
                    minOpacity: 0.35,
                    gradient: {
                        0.0: "#2b78e4",
                        0.4: "#00bcd4",
                        0.6: "#8bc34a",
                        0.8: "#ffeb3b",
                        1.0: "#f44336"
                    }
                }).addTo(map);
            }


        })
        .catch(err => console.error(err));


    // ===============================
    // ROUTING FROM FORM INPUT
    // ===============================
    var routingForm = document.querySelector(".formBlock form");
    var routingMachine = null;

    routingForm.addEventListener("submit", function(e) {
        e.preventDefault();

        let start = document.getElementById("start").value;
        let end = document.getElementById("destination").value;

        if (!start || !end) {
            alert("Isi titik awal dan tujuan!");
            return;
        }

        let startSplit = start.split(",");
        let endSplit = end.split(",");

        let startLatLng = L.latLng(parseFloat(startSplit[0]), parseFloat(startSplit[1]));
        let endLatLng = L.latLng(parseFloat(endSplit[0]), parseFloat(endSplit[1]));

        if (routingMachine) {
            map.removeControl(routingMachine);
        }

        routingMachine = L.Routing.control({
            waypoints: [startLatLng, endLatLng],
            routeWhileDragging: true
        }).addTo(map);
    });


    // ===============================
    // BASEMAP LAYERS
    // ===============================
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    var satelit = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        });

    L.control.layers({
        "OpenStreetMap": osm,
        "Satelit": satelit
    }).addTo(map);

    // =========================
    // SCALE + FULLSCREEN
    // =========================
    L.control.scale({
        position: 'bottomleft'
    }).addTo(map);

    var mapId = document.getElementById('map');

    function fullScreenView() {
        mapId.requestFullscreen();
    }


    // =========================
    // MOUSE TRACKING TOOLTIP
    // =========================
    var tooltipDiv = document.getElementById("mouse-follow");
    var contextMenu = document.getElementById("context-menu");
    var lastLatLng = null;

    map.on("mousemove", function(e) {
        tooltipDiv.style.left = e.originalEvent.pageX + 15 + "px";
        tooltipDiv.style.top = e.originalEvent.pageY + 15 + "px";

        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        tooltipDiv.innerHTML = lat + ", " + lng;
        tooltipDiv.style.display = "block";

        lastLatLng = e.latlng;
    });

    map.on("mouseout", function() {
        tooltipDiv.style.display = "none";
    });

    // Disable browser context menu
    map.getContainer().addEventListener("contextmenu", (e) => e.preventDefault());

    // CUSTOM CONTEXT MENU
    map.on("contextmenu", function(e) {
        contextMenu.style.left = e.originalEvent.pageX + "px";
        contextMenu.style.top = e.originalEvent.pageY + "px";
        contextMenu.style.display = "block";

        lastLatLng = e.latlng;
    });

    map.on("click", function() {
        contextMenu.style.display = "none";
    });
    document.body.addEventListener("click", function() {
        contextMenu.style.display = "none";
    });

    // Add marker
    function addMarker() {
        L.marker(lastLatLng).addTo(map);
        contextMenu.style.display = "none";
    }

    // Copy koordinat
    function copyCoord() {
        const text = lastLatLng.lat.toFixed(6) + ", " + lastLatLng.lng.toFixed(6);
        navigator.clipboard.writeText(text);
        alert("Koordinat disalin:\n" + text);
        contextMenu.style.display = "none";
    }

    // Zoom here
    function zoomHere() {
        map.setView(lastLatLng, map.getZoom() + 2);
        contextMenu.style.display = "none";
    }
</script>