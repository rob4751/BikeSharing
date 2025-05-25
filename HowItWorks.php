<?php
session_start();
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

<body class="h-full text-base-content bg-white">
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
            <a href="#how-it-works-story" class="block hover:text-primary-400">Come funziona</a>
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
    <section id="how-it-works-story" class="relative bg-gray-900 pt-40 pb-32 overflow-hidden">
        <div class="container mx-auto px-6">
            <h2 class="text-5xl font-bold text-white text-center mb-24">The BikeShare Journey</h2>

            <!-- Step 1: Find -->
            <div id="story-step-1" class="grid md:grid-cols-2 gap-20 items-center mb-32 opacity-0 translate-y-10" data-aos="fade-up">
                <div class="relative h-[500px] overflow-hidden rounded-3xl order-2 md:order-1">
                    <img class="absolute inset-0 w-full h-full object-cover" src="img/nav2.webp"
                        alt="person using smartphone to locate bike on map app, night city background, neon lights" />
                    <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black to-transparent"></div>
                </div>
                <div class="space-y-8 order-1 md:order-2">
                    <div class="flex items-center space-x-4">
                        <span class="text-7xl font-bold text-primary-400">01</span>
                        <div class="h-[2px] flex-grow bg-primary-400"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-white">Trova la tua bicicletta perfetta</h3>
                    <p class="text-xl text-gray-400">Apri l'app e scopri le biciclette disponibili vicino a te. La nostra mappa intelligente mostra in tempo reale i tipi di Noleggio e di bicicletta.</p>
                </div>
            </div>
            <!-- Step 2: Unlock -->
            <div id="story-step-2" class="grid md:grid-cols-2 gap-20 items-center mb-32 opacity-0 translate-y-10" data-aos="fade-up">
                <div class="space-y-8 order-2 md:order-1">
                    <div class="flex items-center space-x-4">
                        <span class="text-7xl font-bold text-primary-400">02</span>
                        <div class="h-[2px] flex-grow bg-primary-400"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-white">Sblocco rapido</h3>
                    <p class="text-xl text-gray-400">È sufficiente scansionare il codice QR sulla bicicletta scelta. Il nostro sistema sicuro verifica e sblocca istantaneamente la bicicletta.</p>
                </div>
                <div class="relative h-[500px] overflow-hidden rounded-3xl order-2 md:order-1">
                <img class="absolute inset-0 w-full h-full object-cover"
                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/48e791c634-497f527a9f8c1145c29a.png"
                        alt="close up of person scanning QR code on modern bike, futuristic UI overlay" />
                    <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black to-transparent"></div>
                </div>
            </div>

            <!-- Step 3: Ride -->
            <div id="story-step-3" class="grid md:grid-cols-2 gap-20 items-center mb-32 opacity-0 translate-y-10" data-aos="fade-up">
                <div class="relative h-[500px] overflow-hidden rounded-3xl order-2 md:order-1">
                    <img class="absolute inset-0 w-full h-full object-cover"
                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/e504e3c0d0-71018c8989ba34e932f2.png"
                        alt="person riding modern bike through city street, motion blur, sunset lighting" />
                    <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black to-transparent"></div>
                </div>
                <div class="space-y-8 order-1 md:order-2">
                    <div class="flex items-center space-x-4">
                        <span class="text-7xl font-bold text-primary-400">03</span>
                        <div class="h-[2px] flex-grow bg-primary-400"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-white">Goditi la tua corsa</h3>
                    <p class="text-xl text-gray-400">Percorri le strade con fiducia. Le nostre biciclette sono sottoposte a regolare manutenzione e dotate di dispositivi di sicurezza.</p>
                </div>
            </div>

            <!-- Step 4: Return -->
            <div id="story-step-4" class="grid md:grid-cols-2 gap-20 items-center opacity-0 translate-y-10" data-aos="fade-up">
                <div class="space-y-8 order-2 md:order-1">
                    <div class="flex items-center space-x-4">
                        <span class="text-7xl font-bold text-primary-400">04</span>
                        <div class="h-[2px] flex-grow bg-primary-400"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-white">Restituzione facile</h3>
                    <p class="text-xl text-gray-400">Parcheggiate in qualsiasi punto designato, bloccate la bicicletta e terminate la corsa nell'app. È così semplice!</p>
                </div>
                <div class="relative h-[500px] overflow-hidden rounded-3xl order-2 md:order-1">
                    <img class="absolute inset-0 w-full h-full object-cover"
                        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/d28bb230ed-a9406da0ac5822c33b30.png"
                        alt="bike docking station at night with person locking bike, holographic UI overlay" />
                    <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black to-transparent"></div>
                </div>
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

    <!--Layout-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-10');
                        entry.target.classList.add('opacity-100', 'translate-y-0', 'transition-all', 'duration-1000');
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('[data-aos]').forEach(el => observer.observe(el));
        });
    </script>

</body>

</html>