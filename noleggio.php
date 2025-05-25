<?php
session_start();
$loggedIn = isset($_SESSION['utente']);
$linkNoleggia = $loggedIn ? 'index.php' : 'signup.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bike Sharing</title>

    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: 'var(--color-primary-50)',
                            100: 'var(--color-primary-100)',
                            200: 'var(--color-primary-200)',
                            300: 'var(--color-primary-300)',
                            400: 'var(--color-primary-400)',
                            500: 'var(--color-primary-500)',
                            600: 'var(--color-primary-600)',
                            700: 'var(--color-primary-700)',
                            800: 'var(--color-primary-800)',
                            900: 'var(--color-primary-900)',
                            DEFAULT: 'var(--color-primary)',
                            focus: 'var(--color-primary-focus)',
                            content: 'var(--color-primary-content)'
                        }
                    }
                }
            }
        };
    </script>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        :root {
            --color-primary: #3b82f6;
            --color-primary-400: #60a5fa;
            --color-primary-500: #3b82f6;
            --color-primary-600: #2563eb;
            --color-primary-700: #60a5fa;
            --color-primary-800: #89a6c9;
            --color-primary-900: #1e3a8a;
        }

        * {
            font-family: "Inter", sans-serif;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out, padding 0.3s ease, gap 0.3s ease;
            padding-top: 0;
            padding-bottom: 0;
            gap: 0;
        }

        

        @media (min-width: 900px) {
            #menu-toggle {
                display: none;
            }

            #desktop-nav {
                display: flex !important;
            }

            #mobile-menu {
                display: none !important;
            }
        }
    </style>
</head>

<body class="h-full text-base-content bg-gray-800">
    <header class="bg-gray-900 text-white fixed w-full z-50">
        <div class="flex justify-between items-center px-6 py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-2 font-bold h-10">
                <i class="fa-solid fa-bicycle text-primary-400 text-2xl"></i>
                <a href="index.php" class="text-2xl leading-none">Bike Sharing</a>
            </div>

            <!-- Mobile: initials + hamburger -->
            <div class="flex items-center space-x-4 md:hidden">
                <?php if (isset($_SESSION['utente'])): ?>
                <a href="profile.php" class="w-9 h-9 rounded-full bg-primary-500 text-white flex items-center justify-center font-semibold text-sm">
                    <?php
                        echo strtoupper(substr($_SESSION['utente']['nome'], 0, 1)) . strtoupper(substr($_SESSION['utente']['cognome'], 0, 1));
                    ?>
                </a>
                <?php endif; ?>

                <button id="menu-toggle" class="text-white text-2xl">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>


            <!-- Desktop nav (hidden by default) -->
            <nav id="desktop-nav" class="hidden md:flex items-center space-x-6">
                <a href="HowItWorks.php" class="hover:text-primary-400">Come funziona</a>
                <a href="noleggio.php" class="hover:text-primary-400">Noleggio</a>
                <a href="index.php#Piani" class="hover:text-primary-400">Piani</a>

                <?php if (isset($_SESSION['utente'])): ?>
                    <!-- Logged-in: show user initials as circle + logout -->
                    <a href="profile.php" class="bg-primary-500 hover:bg-primary-600 px-4 py-2 rounded-full text-white font-semibold flex items-center justify-center w-10 h-10">
                    <?php
                        echo strtoupper(substr($_SESSION['utente']['nome'], 0, 1)) . strtoupper(substr($_SESSION['utente']['cognome'], 0, 1));
                    ?>
                    </a>
                    <a href="logout.php" class="ml-3 text-red-500 hover:underline">Disconnetti</a>
                <?php else: ?>
                    <!-- Guest -->
                    <a href="login.php" class="bg-primary-900 hover:bg-primary-800 px-4 py-2 rounded-lg">Accedi</a>
                    <a href="signup.php" class="bg-primary-500 hover:bg-primary-600 px-4 py-2 rounded-lg">Iscriviti</a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Mobile dropdown nav (hidden by default, expands via JS) -->
        <div id="mobile-menu" class="flex flex-col bg-gray-900 text-white p-6 pb-8 space-y-3">
            <a href="HowItWorks.php" class="block hover:text-primary-400">Come funziona</a>
            <a href="noleggio.php" class="block hover:text-primary-400">Noleggio</a>
            <a href="index.php#Piani" class="block hover:text-primary-400">Piani</a>
            <?php if (isset($_SESSION['utente'])): ?>
            <a href="logout.php" class="block bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg text-left">Disconnetti</a>
            <?php else: ?>
            <a href="login.php" class="block bg-primary-900 hover:bg-primary-800 px-4 py-2 rounded-lg text-left">Accedi</a>
            <a href="signup.php" class="block bg-primary-500 hover:bg-primary-600 px-4 py-2 rounded-lg text-left">Iscriviti</a>
            <?php endif; ?>

            <div class="h-10"></div>
        </div>
    </header>

    <!-- HERO TITLE -->
    <section class="pt-28 pb-6 px-6 text-center bg-gray-800">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-2">
            Scegli la tua bici e noleggiala subito
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto">
            Scopri le bici disponibili vicino a te in tempo reale.
        </p>
    </section>

    <!-- MAP SECTION -->
    <section class="relative px-6 pb-10 bg-gray-800 z-10">
        <div class="max-w-screen-xl mx-auto">
            <div class="relative w-full h-[500px] sm:h-[550px] md:h-[600px] rounded-xl overflow-hidden border border-gray-700 shadow-2xl">
            <div class="absolute -inset-4 bg-primary-500 bg-opacity-20 rounded-xl blur-xl z-0"></div>
            <div id="map" class="relative z-10 w-full h-full rounded-xl"></div>
            </div>
        </div>
    </section>



    <footer id="footer" class="bg-gray-900 pt-16 pb-8 border-t border-gray-800">
        <div class="container mx-auto px-6">
            <!-- Logo + social (full width) -->
            <div class="mb-12">
                <div class="text-2xl font-bold text-white flex items-center mb-4">
                    <i class="fa-solid fa-bicycle text-primary-400 mr-2"></i>
                    <span>Bike Sharing</span>
                </div>
                <p class="text-gray-400 mb-4">Scopri la libertà di esplorare la città con il nostro servizio premium di bike sharing.</p>
                <div class="flex space-x-4">
                    <span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer">
                        <i class="fa-brands fa-facebook-f"></i>
                    </span>
                    <span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer">
                        <i class="fa-brands fa-twitter"></i>
                    </span>
                    <span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer">
                        <i class="fa-brands fa-instagram"></i>
                    </span>
                    <span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </span>
                </div>
            </div>

            <!-- Explore + Company grid -->
            <div class="grid grid-cols-2  gap-8 mb-12">
                <div>
                    <h4 class="text-white font-bold text-lg mb-4">Esplora</h4>
                    <ul class="space-y-2">
                        <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a href="HowItWorks.php">Come funziona</a></span></li>
                        <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a href="noleggio.php">Noleggio</a></span></li>
                        <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a href="index.php#Piani">Piani</a></span></li>
                    </ul>
                </div>
                
            </div>


            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-500 mb-4 md:mb-0">
                    © 2025 Bike Sharing. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!--ToggleMenu-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('menu-toggle');
            const menu = document.getElementById('mobile-menu');
            let isOpen = false;

            toggle.addEventListener('click', () => {
                isOpen = !isOpen;
                if (isOpen) {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                } else {
                    menu.style.maxHeight = '0px';
                }
            });
        });
    </script>

    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const map = L.map('map', {
                attributionControl: false
            }).setView([0, 0], 13);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            // Icona utente (bianca)
            const userIcon = L.icon({
                iconUrl: 'https://img.icons8.com/material-rounded/96/FFFFFF/marker.png',
                iconSize: [38, 38],
                iconAnchor: [19, 38],
                popupAnchor: [0, -38]
            });

            // Icona bici (blu)
            const bikeIcon = L.icon({
                iconUrl: 'https://img.icons8.com/ios-filled/50/3b82f6/marker.png',
                iconSize: [38, 38],
                iconAnchor: [19, 38],
                popupAnchor: [0, -38]
            });

            let userLat = 0;
            let userLng = 0;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;

                    map.setView([userLat, userLng], 15);

                    L.marker([userLat, userLng], { icon: userIcon }).addTo(map).bindPopup("Sei qui").openPopup();

                    addBikeMarkers();
                }, function () {
                    alert("Impossibile ottenere la tua posizione.");
                    map.setView([45.4064, 11.8768], 14);
                    addBikeMarkers();
                });
            } else {
                alert("Geolocalizzazione non supportata.");
                map.setView([45.4064, 11.8768], 14);
                addBikeMarkers();
            }

            function addBikeMarkers() {
                const bikeLocations = [
                    { lat: 45.402755, lng: 11.873678 },
                    { lat: 45.402798, lng: 11.881619 },
                    { lat: 45.405004, lng: 11.880115 },
                    { lat: 45.403593, lng: 11.875461 },
                    { lat: 45.405312, lng: 11.875973 },
                    { lat: 45.403031, lng: 11.884063 },
                    { lat: 45.401911, lng: 11.879251 },
                    { lat: 45.402872, lng: 11.876552 },
                    { lat: 45.405269, lng: 11.878352 },
                    { lat: 45.404538, lng: 11.877338 },
                    { lat: 45.403389, lng: 11.878835 },
                    { lat: 45.404796, lng: 11.882242 },
                    { lat: 45.401740, lng: 11.877505 },
                    { lat: 45.404141, lng: 11.875672 },
                    { lat: 45.401568, lng: 11.881263 },
                    { lat: 45.403707, lng: 11.880711 },
                    { lat: 45.405446, lng: 11.881821 },
                    { lat: 45.402283, lng: 11.876938 },
                    { lat: 45.404354, lng: 11.879619 },
                    { lat: 45.405089, lng: 11.877063 },
                    { lat: 45.408152, lng: 11.877813 },
                    { lat: 45.407108, lng: 11.879797 },
                    { lat: 45.410102, lng: 11.881221 },
                    { lat: 45.410668, lng: 11.873897 },
                    { lat: 45.411343, lng: 11.875800 },
                    { lat: 45.406420, lng: 11.886545 },
                    { lat: 45.405905, lng: 11.872439 },
                    { lat: 45.409774, lng: 11.870452 },
                    { lat: 45.403900, lng: 11.868213 },
                    { lat: 45.403213, lng: 11.890832 },
                    { lat: 45.407201, lng: 11.889230 },
                    { lat: 45.412598, lng: 11.884023 },
                    { lat: 45.412011, lng: 11.878263 },
                    { lat: 45.408895, lng: 11.886910 },
                    { lat: 45.408457, lng: 11.867601 },
                    { lat: 45.410728, lng: 11.866053 },
                    { lat: 45.409239, lng: 11.892098 },
                    { lat: 45.406325, lng: 11.894511 },
                    { lat: 45.400780, lng: 11.872837 },
                    { lat: 45.402102, lng: 11.866828 }
                ];
                function isMobileDevice() {
                    return window.innerWidth <= 768;
                }

                bikeLocations.forEach(loc => {
                    const marker = L.marker([loc.lat, loc.lng], { icon: bikeIcon }).addTo(map);

                    const popupContent = `
                        <div class="text-black text-sm">
                        <strong>Distanza:</strong> <span class="distance"></span><br/>
                        <a href="<?= $linkNoleggia ?>" class="mt-2 inline-block bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded">Noleggia ora</a>
                        </div>
                    `;

                    marker.bindPopup(popupContent);

                    if (!isMobileDevice()) {
                        marker.on("mouseover", function () {
                        marker.openPopup();

                        const userLatLng = userMarker.getLatLng();
                        const distanceInMeters = map.distance(userLatLng, marker.getLatLng());
                        const distanceKm = (distanceInMeters / 1000).toFixed(2);

                        const container = document.createElement("div");
                        container.innerHTML = popupContent;
                        container.querySelector(".distance").innerText = `${distanceKm} km`;

                        marker.setPopupContent(container.innerHTML);
                        });

                        marker.on("mouseout", function () {
                        marker.closePopup();
                        });
                    } else {
                        marker.on("click", function () {
                        const userLatLng = userMarker.getLatLng();
                        const distanceInMeters = map.distance(userLatLng, marker.getLatLng());
                        const distanceKm = (distanceInMeters / 1000).toFixed(2);

                        const container = document.createElement("div");
                        container.innerHTML = popupContent;
                        container.querySelector(".distance").innerText = `${distanceKm} km`;

                        marker.setPopupContent(container.innerHTML);
                        marker.openPopup();
                        });
                    }
                });


                bikeLocations.forEach(loc => {
                    const distanceMeters = map.distance([userLat, userLng], [loc.lat, loc.lng]);
                    const distanceKm = (distanceMeters / 1000).toFixed(2);

                    const popupContent = `
                        <div style="color: black; font-size: 14px;">
                            <strong>Distanza:</strong> ${distanceKm} km<br/>
                            <a href="<?= $linkNoleggia ?>" 
                                style="margin-top: 8px; display: inline-block; background-color: #3b82f6; padding: 6px 12px; color: white; border-radius: 6px; text-decoration: none;">
                                Noleggia ora
                            </a>
                        </div>
                    `;

                    const marker = L.marker([loc.lat, loc.lng], { icon: bikeIcon }).addTo(map);
                    marker.bindPopup(popupContent);

                    let popupTimeout;

                    // Apri popup al passaggio
                    marker.on('mouseover', function () {
                        clearTimeout(popupTimeout); // se stava per chiudersi, annulla
                        this.openPopup();
                    });

                    // Chiudi solo se né il marker né il popup sono più attivi
                    marker.on('mouseout', function () {
                        const popupEl = document.querySelector('.leaflet-popup');
                        if (popupEl) {
                            popupEl.addEventListener('mouseenter', () => clearTimeout(popupTimeout));
                            popupEl.addEventListener('mouseleave', () => {
                                popupTimeout = setTimeout(() => marker.closePopup(), 300);
                            });
                        }
                        popupTimeout = setTimeout(() => marker.closePopup(), 300);
                    });
                });
            }
        });
    </script>







</body>

</html>