<?php session_start(); ?>
<html>


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bike Sharing</title>

    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&display=swap"
        rel="stylesheet" />

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
    <!-- Leaflet CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
    <style>
        #mobile-menu a {
            font-size: 0.875rem !important; /* Tailwind's text-sm */
            border-radius: 0.375rem !important; /* Tailwind's rounded-md */
        }
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out, padding 0.3s ease, gap 0.3s ease;
            padding-top: 0;
            padding-bottom: 0;
            gap: 0;
        }

        #mobile-menu.open {
            max-height: 500px; /* enough to show everything */
            opacity: 1;
            visibility: visible;
            padding-top: 1rem;
            padding-bottom: 1rem;
            gap: 0.5rem; /* less than before */
        }
    </style>
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
        });
    </script>
</head>
<body class="h-full text-base-content">
    <header class="bg-gray-900 text-white fixed w-full z-50">
        <div class="flex justify-between items-center px-6 py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-2 font-bold h-10">
                <i class="fa-solid fa-bicycle text-primary-400 text-2xl"></i>
                <a href="index.php" class="text-xl leading-none">Bike Sharing</a>
            </div>

            <!-- Mobile: initials + hamburger -->
            <div class="flex items-center space-x-4 md:hidden">
            <?php if (isset($_SESSION['utente'])): ?>
                <a href="profile.php"
                class="w-9 h-9 rounded-full bg-primary-500 text-white flex items-center justify-center font-semibold text-sm">
                <?php
                    echo strtoupper(substr($_SESSION['utente']['nome'], 0, 1)) . strtoupper(substr($_SESSION['utente']['cognome'], 0, 1));
                ?>
                </a>
            <?php endif; ?>

            <button id="menu-toggle" class="text-white text-2xl focus:outline-none block md:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>
            </div>
        </div>

        <!-- Mobile dropdown nav -->
        <div id="mobile-menu" class="flex flex-col bg-gray-900 text-white p-6 pb-8 space-y-3">
            <a href="HowItWorks.php" class="block hover:text-primary-400">Come funziona</a>
            <a href="#Noleggio" class="block hover:text-primary-400">Noleggio</a>
            <a href="#Piani" class="block hover:text-primary-400">Piani</a>

            <?php if (isset($_SESSION['utente'])): ?>
                <a href="logout.php" class="bg-red-600 hover:bg-red-500 text-white text-sm px-4 py-2 rounded-md w-full text-center">Disconnetti</a>
            <?php else: ?>
                <a href="login.php" class="bg-primary-900 hover:bg-primary-800 text-white text-sm px-4 py-2 rounded-md w-full">Accedi</a>
                <a href="signup.php" class="bg-primary-500 hover:bg-primary-600 text-white text-sm px-4 py-2 rounded-md w-full mt-2 mb-8">Iscriviti</a>
                <p></p>
            <?php endif; ?>
        </div>
    </header>


    <!-- Hero Section -->
    <section id="hero" class="relative h-screen bg-gray-900 flex items-center justify-center pt-16">
        <div class="absolute inset-0 z-0">
            <img class="object-cover w-full h-full opacity-40"
                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/933021943f-832ec6fdf7f3db4621f8.png"
                alt="urban night bike scene" />
        </div>
        <div class="z-10 text-center px-6 max-w-xl">
            <h1 class="text-white mb-4 leading-tight" style="font-size: 10vw;">La tua città.<br/>La tua corsa.</h1>
            <p class="text-gray-300 mb-6">Scopri la libertà di esplorare la città con il nostro servizio
                di bike sharing premium. Sostenibile, conveniente e progettato per la vita moderna in città.
            </p>
            <div class="flex flex-col gap-3 items-center">
                <a href="noleggio.php">
                    <button class="bg-primary-500 hover:bg-primary-600 text-white text-base px-6 py-3 rounded-xl font-semibold w-full max-w-xs">
                        <i class="fa-solid fa-bicycle mr-2"></i> Noleggia
                    </button>
                </a>
                <a href="HowItWorks.php">
                    <button class="bg-gray-800 hover:bg-gray-700 text-white border border-gray-700 text-base px-6 py-3 rounded-xl font-semibold w-full max-w-xs">
                        <i class="fa-solid fa-circle-info mr-2"></i> Scopri Come funziona
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Come funziona Section -->
    <section id="how-it-works" class="bg-gray-900 py-16">
        <div class="px-5">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-3">Come funziona</h2>
                <p class="text-gray-400">Noleggiare una bicicletta non è mai stato così facile. Segui questi semplici passaggi e inizia il tuo viaggio.</p>
            </div>

            <div class="snap-x snap-mandatory flex overflow-x-auto pb-6 -mx-5 px-5 space-x-4 hide-scrollbar">
                <div id="step-1"
                    class="snap-center flex-shrink-0 w-[80%] bg-gray-800 p-6 rounded-xl border-l-4 border-primary-500">
                    <div class="text-primary-400 text-4xl font-bold mb-3">01</div>
                    <h3 class="text-xl font-bold text-white mb-2">Iscriviti</h3>
                    <p class="text-gray-300 mb-4">Crea il tuo account in pochi secondi. Tutto ciò di cui hai bisogno è la tua email per iniziare.</p>
                    <div class="w-14 h-14 rounded-full bg-gray-700 flex items-center justify-center">
                        <i class="fa-solid fa-user-plus text-primary-400 text-xl"></i>
                    </div>
                </div>

                <div id="step-2"
                    class="snap-center flex-shrink-0 w-[80%] bg-gray-800 p-6 rounded-xl border-l-4 border-primary-500">
                    <div class="text-primary-400 text-4xl font-bold mb-3">02</div>
                    <h3 class="text-xl font-bold text-white mb-2">Scegli la tua bici</h3>
                    <p class="text-gray-300 mb-4">Sfoglia le biciclette disponibili nelle vicinanze. Seleziona quella che soddisfa le tue esigenze e
                        prenotala istantaneamente.</p>
                    <div class="w-14 h-14 rounded-full bg-gray-700 flex items-center justify-center">
                        <i class="fa-solid fa-bicycle text-primary-400 text-xl"></i>
                    </div>
                </div>

                <div id="step-3"
                    class="snap-center flex-shrink-0 w-[80%] bg-gray-800 p-6 rounded-xl border-l-4 border-primary-500">
                    <div class="text-primary-400 text-4xl font-bold mb-3">03</div>
                    <h3 class="text-xl font-bold text-white mb-2">Fai un giro</h3>
                    <p class="text-gray-300 mb-4">Sblocca la tua bici con l'app oppure dal sito e goditi il tuo giro. Esplora la città al tuo
                        ritmo.</p>
                    <div class="w-14 h-14 rounded-full bg-gray-700 flex items-center justify-center">
                        <i class="fa-solid fa-route text-primary-400 text-xl"></i>
                    </div>
                </div>

                <div id="step-4"
                    class="snap-center flex-shrink-0 w-[80%] bg-gray-800 p-6 rounded-xl border-l-4 border-primary-500">
                    <div class="text-primary-400 text-4xl font-bold mb-3">04</div>
                    <h3 class="text-xl font-bold text-white mb-2">Restituisci</h3>
                    <p class="text-gray-300 mb-4">Parcheggia la tua bici in un qualsiasi posto designato e termina il tuo giro.
                        È così semplice.</p>
                    <div class="w-14 h-14 rounded-full bg-gray-700 flex items-center justify-center">
                        <i class="fa-solid fa-location-dot text-primary-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pagination Dots -->
            <div class="flex justify-center space-x-2 mt-4">
                <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
            </div>
        </div>
    </section>
    <!-- Find Bikes Near You Section -->
    <section id="Noleggio" class="bg-gray-800 py-16">
        <div class="px-5">
            <h2 class="text-3xl font-bold text-white mb-3">Trova La Bici Vicino a Te</h2>
            <p class="text-gray-400 mb-6">
                Con centinaia di biciclette in tutta la città, non sei mai lontano dalla tua prossima corsa. Usa la nostra mappa interattiva per trovare la bici disponibile più vicina.
            </p>

            <!-- Interactive Leaflet Map with Blue Glow -->
            <div class="relative mb-6">
                <!-- Blue radiant glow -->
                <div class="absolute inset-0 z-0 rounded-xl bg-primary-500 opacity-30 blur-3xl"></div>

                <!-- Map container -->
                <div class="relative z-10 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                    <!-- <div
                        class="absolute top-3 left-3 bg-gray-900 bg-opacity-80 backdrop-blur-sm px-3 py-2 rounded-lg text-white text-sm">
                        <i class="fa-solid fa-location-crosshairs text-primary-400 mr-1"></i>
                    </div> -->
                </div>
            </div>

            <!-- Features List -->
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary-500 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-star text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">Disponibilità in tempo reale</h4>
                        <p class="text-gray-400 text-sm">Vedi quali biciclette sono disponibili in tempo reale</p>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <a href="noleggio.php">
                <button class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-lg text-base font-medium flex items-center justify-center">
                    <i class="fa-solid fa-map-location-dot mr-2"></i> Noleggia
                </button>
            </a> 
        </div>
    </section>




    <!-- Piani Plans Section -->
    <section id="Piani" class="bg-gray-800 py-16">
        <div class="px-5">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-3">Piani</h2>
                <p class="text-gray-400">Trova il piano che soddisfa le tue esigenze di guida. Inizia gratis, aggiorna in qualsiasi momento.</p>
            </div>

            <div class="snap-x snap-mandatory flex overflow-x-auto pb-6 -mx-5 px-5 space-x-4 hide-scrollbar">

                <!-- Single Ride -->
                <div id="Piani-single"
                    class="snap-center flex-shrink-0 w-[85%] bg-gray-900 rounded-xl overflow-hidden border border-gray-700 flex flex-col">
                    <div class="h-2 bg-primary-500"></div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-white mb-1">Piano Standard</h3>
                        <p class="text-gray-400 text-sm mb-4">Inizia gratis, perfetto per utenti occasionali</p>
                        <div class="flex items-end gap-1 mb-4">
                            <span class="text-3xl font-bold text-white">Gratis</span>
                            <span class="text-gray-400 text-sm mb-1">per iscriversi</span>
                        </div>
                        <ul class="space-y-2 mb-5 text-sm">
                            <li class="flex items-center text-gray-300"><i
                                    class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>€5 per sbloccare una bici
                            </li>
                            <li class="flex items-center text-gray-300"><i
                                    class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>€3 per 30 minuti dopo i primi 30 minuti</li>
                            <li class="flex items-center text-gray-300"><i
                                    class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>Accesso a tutte le bici</li>
                        </ul>
                        <div class="mt-auto">
                            <button
                                class="w-full bg-gray-700 hover:bg-primary-600 text-white py-3 rounded-lg text-sm">Inizia a Girare Gratis</button>
                        </div>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div id="Piani-weekly"
                    class="snap-center flex-shrink-0 w-[85%] bg-gray-900 rounded-xl overflow-hidden border border-gray-700 flex flex-col relative">
                    <div class="h-2 bg-primary-400"></div>
                    <div class="absolute top-0 left-0 bg-primary-500 text-white text-xs px-2 py-1 rounded-br-md">PIÙ POPOLARE
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-white mb-1">Piano Pro</h3>
                        <p class="text-gray-400 text-sm mb-4">Paga meno per corsa con una bassa tariffa mensile</p>
                        <div class="flex items-end gap-1 mb-4">
                            <span class="text-3xl font-bold text-white">€20</span>
                            <span class="text-gray-400 text-sm mb-1">tariffa una tantum</span>
                        </div>
                        <ul class="space-y-2 mb-5 text-sm">
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>€2 per sbloccare una bici
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>€2 per 30 minuti
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>Accesso a tutte le bici
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>Pianificazione del percorso + App mobile
                            </li>
                        </ul>
                        <div class="mt-auto">
                            <button class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-lg text-sm">
                                Passa a Pro
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Monthly Plan -->
                <div id="Piani-monthly"
                    class="snap-center flex-shrink-0 w-[85%] bg-gray-900 rounded-xl overflow-hidden border border-gray-700 flex flex-col">
                    <div class="h-2 bg-primary-500"></div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-white mb-1">Piano Premium</h3>
                        <p class="text-gray-400 text-sm mb-4">Miglior affare per i ciclisti frequenti</p>
                        <div class="flex items-end gap-1 mb-4">
                            <span class="text-3xl font-bold text-white">€50</span>
                            <span class="text-gray-400 text-sm mb-1">tariffa una tantum</span>
                        </div>
                        <ul class="space-y-2 mb-5 text-sm">
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>
                                Sblocchi gratuiti per ogni corsa
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>
                                €1 per 30 minuti
                            </li>
                            <li class="flex items-center text-gray-300">
                                <i class="fa-solid fa-check text-primary-400 mr-2 w-4"></i>
                                Accesso prioritario alle bici
                            </li>
                        </ul>
                        <div class="mt-auto">
                            <button class="w-full bg-gray-700 hover:bg-primary-600 text-white py-3 rounded-lg text-sm">
                                Passa a Premium
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pagination Dots -->
            <div class="flex justify-center space-x-2 mt-2" id="Piani-dots">
                <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
            </div>
        </div>
    </section>


    <!-- App Promotion Section -->
    <section id="app-promo" class="bg-gray-900 py-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-primary-500 opacity-10 rounded-l-full blur-3xl"></div>
        <div class="px-5 relative z-10">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-white mb-3">Il Tuo Compagno di Bici Sta Arrivando</h2>
                <p class="text-gray-400">Preparati per un'esperienza di bike-sharing senza interruzioni con la nostra prossima app mobile. Sblocca biciclette, traccia percorsi e gestisci il tuo account ovunque ti trovi.</p>
            </div>

            <div class="flex justify-center mb-8">
                <div class="relative">
                    <div class="absolute -inset-4 bg-primary-500 bg-opacity-20 rounded-xl blur-xl"></div>
                    <div class="relative flex justify-center">
                        <div class="transform rotate-12 translate-x-5 z-10">
                            <img class="w-[140px] h-auto rounded-2xl shadow-xl border border-gray-700"
                                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/01e0fd1f14-0d9cc8add3f0787f5d1a.png"
                                alt="smartphone showing bike sharing app dark mode UI with map and bike selection screen, sleek modern design" />
                        </div>
                        <div class="transform -rotate-6 -translate-x-5 translate-y-5">
                            <img class="w-[140px] h-auto rounded-2xl shadow-xl border border-gray-700"
                                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/01e0fd1f14-c4ef03decc1cb219bec1.png"
                                alt="smartphone showing bike sharing app dark mode UI with profile and ride history screen, sleek modern design" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary-500 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-unlock text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">Sblocca con un tocco</h4>
                        <p class="text-gray-400 text-sm">Sblocca qualsiasi bici con un semplice tocco sul tuo telefono</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary-500 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-map text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">Navigazione in tempo reale</h4>
                        <p class="text-gray-400 text-sm">Ricevi indicazioni passo-passo per la tua destinazione</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button
                    class="bg-gray-700 text-white py-3 rounded-lg transition-colors flex items-center justify-center">
                    <span>
                        <span class="block font-bold">IN ARRIVO</span>
                    </span>
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="bg-gray-800 py-16">
        <div class="px-5">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-3">Cosa Dicono i Nostri Ciclisti</h2>
                <p class="text-gray-400">
                    Scopri perché migliaia di ciclisti scelgono il nostro servizio di bike sharing ogni giorno.
                </p>
            </div>

            <div class="snap-x snap-mandatory flex overflow-x-auto pb-6 -mx-5 px-5 space-x-4 hide-scrollbar">
                <div id="testimonial-1"
                    class="snap-center flex-shrink-0 w-[85%] bg-gray-900 p-6 rounded-xl border border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-2.jpg"
                            alt="User Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-primary-500" />
                        <div>
                            <h4 class="text-white font-bold">Michael T.</h4>
                            <div class="flex text-primary-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        "Uso le bici elettriche per il mio tragitto quotidiano ed è stata una svolta. Risparmio denaro, evito il traffico e faccio un po' di esercizio. L'app rende tutto molto semplice per trovare e sbloccare le bici."
                    </p>
                </div>

                <div id="testimonial-2"
                    class="snap-center flex-shrink-0 w-[85%] bg-gray-900 p-6 rounded-xl border border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-5.jpg"
                            alt="User Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-primary-500" />
                        <div>
                            <h4 class="text-white font-bold">Sarah K.</h4>
                            <div class="flex text-primary-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        "Il piano Pro è perfetto per turisti come me. Ho esplorato l'intera città
                        con queste bici e ho scoperto angoli nascosti che altrimenti avrei perso. Altamente consigliato!"
                    </p>
                </div>

                <div id="testimonial-3"
                    class="snap-center flex-shrink-0 w-[85%] bg-gray-900 p-6 rounded-xl border border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg"
                            alt="User Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-primary-500" />
                        <div>
                            <h4 class="text-white font-bold">David R.</h4>
                            <div class="flex text-primary-400 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        "Per una persona che si preoccupa per l'ambiente, adoro poter ridurre la mia impronta
                        di carbonio mentre mi sposto. Le biciclette sono sempre ben mantenute e il piano mensile è un ottimo valore."
                    </p>
                </div>
            </div>

            <!-- Pagination Dots -->
            <div class="flex justify-center space-x-2 mt-2">
                <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
            </div>

            <div class="mt-6 text-center">
                <a href="reviews.html">
                    <button
                        class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-sm inline-flex items-center">
                        <i class="fa-solid fa-pen-to-square mr-2"></i>Scrivi una recensione
                    </button>
                </a>

            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section id="final-cta" class="bg-gray-900 py-16 relative">
        <div class="absolute inset-0 z-0">
            <img class="object-cover w-full h-full opacity-20"
                src="https://storage.googleapis.com/uxpilot-auth.appspot.com/ed82930ebe-2a16c2ea1add47057917.png"
                alt="aerial view of city bike lanes with glowing lines at night, futuristic minimal design" />
        </div>
        <div class="px-5 relative z-10">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Pronto a Scoprire la Città su Due Ruote?</h2>
                <p class="text-gray-300 mb-6">Unisciti a migliaia di ciclisti che hanno scoperto un modo migliore per navigare in città. Iscriviti oggi.</p>

                <div class="flex flex-col gap-3">

                        <button
                            class="bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-lg text-base font-medium flex items-center justify-center">
                            <i class="fa-solid fa-user-plus mr-2"></i> <a href="signup.php">Iscriviti ora</a>
                        </button>
                        
                        <button
                            class="bg-gray-800 hover:bg-gray-700 text-white border border-gray-700 py-3 rounded-lg text-base font-medium flex items-center justify-center">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i><a href="login.php">Accedi</a>
                        </button>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer id="footer" class="bg-gray-900 pt-12 pb-6 border-t border-gray-800">
        <div class="px-5">
            <div class="mb-8">
                <div class="text-xl font-bold text-white flex items-center mb-3">
                    <i class="fa-solid fa-bicycle text-primary-400 mr-2"></i>
                    <span>Bike Sharing</span>
                </div>
                <p class="text-gray-400 text-sm mb-4">Scopri la libertà di esplorare la città con il nostro servizio premium di bike sharing.</p>
                <div class="flex space-x-4">
                    <span class="text-gray-400 hover:text-primary-400 cursor-pointer">
                        <i class="fa-brands fa-facebook-f"></i>
                    </span>
                    <span class="text-gray-400 hover:text-primary-400 cursor-pointer">
                        <i class="fa-brands fa-twitter"></i>
                    </span>
                    <span class="text-gray-400 hover:text-primary-400 cursor-pointer">
                        <i class="fa-brands fa-instagram"></i>
                    </span>
                    <span class="text-gray-400 hover:text-primary-400 cursor-pointer">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4 text-md">Esplora</h4>
                    <ul class="space-y-2 text-sm">
                        <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a
                                    href="HowItWorks.php">Come funziona</a></span></li>
                        <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a
                                    href="noleggio.php">Noleggio</a></span></li>
                        <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a
                                    href="#Piani">Piani</a></span></li>
                    </ul>
                </div>
                
            </div>

            <div class="border-t border-gray-800 pt-6 text-center">
                <div class="text-gray-500 text-xs">
                    © 2025 Bike Sharing. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!--Bullet Points-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const observeSnapScroll = (sectionSelector, dotSelector) => {
                const steps = document.querySelectorAll(`${sectionSelector} > div > .snap-x > div`);
                const dots = document.querySelectorAll(`${sectionSelector} ${dotSelector} > div`);

                const observer = new IntersectionObserver(
                    entries => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const index = Array.from(steps).indexOf(entry.target);
                                dots.forEach((dot, i) => {
                                    dot.classList.toggle("bg-primary-500", i === index);
                                    dot.classList.toggle("bg-gray-600", i !== index);
                                });
                            }
                        });
                    },
                    {
                        root: document.querySelector(`${sectionSelector} .snap-x`),
                        threshold: 0.5,
                    }
                );

                steps.forEach(step => observer.observe(step));
            };

            // Apply to both sections
            observeSnapScroll("#how-it-works", ".flex.justify-center");
            observeSnapScroll("#Piani", "#Piani-dots");
            observeSnapScroll("#testimonials", ".flex.justify-center");
        });
    </script>

    <!--Piani Centered-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const PianiSnap = document.querySelector("#Piani .snap-x");
            const weeklyCard = document.getElementById("Piani-weekly");

            if (PianiSnap && weeklyCard) {
                // Scroll only the container, not the page
                const offset = weeklyCard.offsetLeft - (PianiSnap.clientWidth - weeklyCard.clientWidth) / 2;
                PianiSnap.scrollLeft = offset;
            }
        });
    </script>
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



</body>

</html>