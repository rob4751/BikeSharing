<?php
session_start();
function getInitials($name, $surname) {
  return strtoupper(substr($name, 0, 1) . substr($surname, 0, 1));
}
?>
<html>

<head>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.tailwindcss.com"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

  <!--no scrollbar-->
  <style>
    * {
      font-family: "Inter", sans-serif;
    }

    ::-webkit-scrollbar {
      display: none;
    }

    html {
      scroll-behavior: smooth;
    }
  </style>

  <!--Colors-->
  <style>
    :root {
      /* Primary colors */
      --color-primary: #3b82f6;
      --color-primary-50: #eff6ff;
      --color-primary-100: #dbeafe;
      --color-primary-200: #bfdbfe;
      --color-primary-300: #93c5fd;
      --color-primary-400: #60a5fa;
      --color-primary-500: #3b82f6;
      --color-primary-600: #2563eb;
      --color-primary-700: #1d4ed8;
      --color-primary-800: #1e40af;
      --color-primary-900: #1e3a8a;
      --color-primary-focus: #2563eb;
      --color-primary-content: #ffffff;
    }

    /*Menu CSS*/
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

  <!--TailwindConfig-->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ...{ "transparent": "transparent", "current": "currentColor", "black": "#000000", "white": "#ffffff", "gray": { "50": "#f9fafb", "100": "#f3f4f6", "200": "#e5e7eb", "300": "#d1d5db", "400": "#9ca3af", "500": "#6b7280", "600": "#4b5563", "700": "#374151", "800": "#1f2937", "900": "#111827" }, "red": { "50": "#fef2f2", "100": "#fee2e2", "200": "#fecaca", "300": "#fca5a5", "400": "#f87171", "500": "#ef4444", "600": "#dc2626", "700": "#b91c1c", "800": "#991b1b", "900": "#7f1d1d" }, "yellow": { "50": "#fffbeb", "100": "#fef3c7", "200": "#fde68a", "300": "#fcd34d", "400": "#fbbf24", "500": "#f59e0b", "600": "#d97706", "700": "#b45309", "800": "#92400e", "900": "#78350f" }, "green": { "50": "#ecfdf5", "100": "#d1fae5", "200": "#a7f3d0", "300": "#6ee7b7", "400": "#34d399", "500": "#10b981", "600": "#059669", "700": "#047857", "800": "#065f46", "900": "#064e3b" }, "blue": { "50": "#eff6ff", "100": "#dbeafe", "200": "#bfdbfe", "300": "#93c5fd", "400": "#60a5fa", "500": "#3b82f6", "600": "#2563eb", "700": "#1d4ed8", "800": "#1e40af", "900": "#1e3a8a" }, "indigo": { "50": "#eef2ff", "100": "#e0e7ff", "200": "#c7d2fe", "300": "#a5b4fc", "400": "#818cf8", "500": "#6366f1", "600": "#4f46e5", "700": "#4338ca", "800": "#3730a3", "900": "#312e81" }, "purple": { "50": "#f5f3ff", "100": "#ede9fe", "200": "#ddd6fe", "300": "#c4b5fd", "400": "#a78bfa", "500": "#8b5cf6", "600": "#7c3aed", "700": "#6d28d9", "800": "#5b21b6", "900": "#4c1d95" }, "pink": { "50": "#fdf2f8", "100": "#fce7f3", "200": "#fbcfe8", "300": "#f9a8d4", "400": "#f472b6", "500": "#ec4899", "600": "#db2777", "700": "#be185d", "800": "#9d174d", "900": "#831843" } },
            ...{ "primary": { "50": "var(--color-primary-50)", "100": "var(--color-primary-100)", "200": "var(--color-primary-200)", "300": "var(--color-primary-300)", "400": "var(--color-primary-400)", "500": "var(--color-primary-500)", "600": "var(--color-primary-600)", "700": "var(--color-primary-700)", "800": "var(--color-primary-800)", "900": "var(--color-primary-900)", "DEFAULT": "var(--color-primary)", "focus": "var(--color-primary-focus)", "content": "var(--color-primary-content)" }, "secondary": { "50": "var(--color-secondary-50)", "100": "var(--color-secondary-100)", "200": "var(--color-secondary-200)", "300": "var(--color-secondary-300)", "400": "var(--color-secondary-400)", "500": "var(--color-secondary-500)", "600": "var(--color-secondary-600)", "700": "var(--color-secondary-700)", "800": "var(--color-secondary-800)", "900": "var(--color-secondary-900)", "DEFAULT": "var(--color-secondary)", "focus": "var(--color-secondary-focus)", "content": "var(--color-secondary-content)" }, "accent": { "50": "var(--color-accent-50)", "100": "var(--color-accent-100)", "200": "var(--color-accent-200)", "300": "var(--color-accent-300)", "400": "var(--color-accent-400)", "500": "var(--color-accent-500)", "600": "var(--color-accent-600)", "700": "var(--color-accent-700)", "800": "var(--color-accent-800)", "900": "var(--color-accent-900)", "DEFAULT": "var(--color-accent)", "focus": "var(--color-accent-focus)", "content": "var(--color-accent-content)" }, "neutral": { "50": "var(--color-neutral-50)", "100": "var(--color-neutral-100)", "200": "var(--color-neutral-200)", "300": "var(--color-neutral-300)", "400": "var(--color-neutral-400)", "500": "var(--color-neutral-500)", "600": "var(--color-neutral-600)", "700": "var(--color-neutral-700)", "800": "var(--color-neutral-800)", "900": "var(--color-neutral-900)", "DEFAULT": "var(--color-neutral)", "focus": "var(--color-neutral-focus)", "content": "var(--color-neutral-content)" }, "info": { "50": "var(--color-info-50)", "100": "var(--color-info-100)", "200": "var(--color-info-200)", "300": "var(--color-info-300)", "400": "var(--color-info-400)", "500": "var(--color-info-500)", "600": "var(--color-info-600)", "700": "var(--color-info-700)", "800": "var(--color-info-800)", "900": "var(--color-info-900)", "DEFAULT": "var(--color-info)", "focus": "var(--color-info-focus)", "content": "var(--color-info-content)" }, "success": { "50": "var(--color-success-50)", "100": "var(--color-success-100)", "200": "var(--color-success-200)", "300": "var(--color-success-300)", "400": "var(--color-success-400)", "500": "var(--color-success-500)", "600": "var(--color-success-600)", "700": "var(--color-success-700)", "800": "var(--color-success-800)", "900": "var(--color-success-900)", "DEFAULT": "var(--color-success)", "focus": "var(--color-success-focus)", "content": "var(--color-success-content)" }, "warning": { "50": "var(--color-warning-50)", "100": "var(--color-warning-100)", "200": "var(--color-warning-200)", "300": "var(--color-warning-300)", "400": "var(--color-warning-400)", "500": "var(--color-warning-500)", "600": "var(--color-warning-600)", "700": "var(--color-warning-700)", "800": "var(--color-warning-800)", "900": "var(--color-warning-900)", "DEFAULT": "var(--color-warning)", "focus": "var(--color-warning-focus)", "content": "var(--color-warning-content)" }, "error": { "50": "var(--color-error-50)", "100": "var(--color-error-100)", "200": "var(--color-error-200)", "300": "var(--color-error-300)", "400": "var(--color-error-400)", "500": "var(--color-error-500)", "600": "var(--color-error-600)", "700": "var(--color-error-700)", "800": "var(--color-error-800)", "900": "var(--color-error-900)", "DEFAULT": "var(--color-error)", "focus": "var(--color-error-focus)", "content": "var(--color-error-content)" }, "danger": { "50": "var(--color-error-50)", "100": "var(--color-error-100)", "200": "var(--color-error-200)", "300": "var(--color-error-300)", "400": "var(--color-error-400)", "500": "var(--color-error-500)", "600": "var(--color-error-600)", "700": "var(--color-error-700)", "800": "var(--color-error-800)", "900": "var(--color-error-900)", "DEFAULT": "var(--color-error)", "focus": "var(--color-error-focus)", "content": "var(--color-error-content)" }, "failure": { "50": "var(--color-error-50)", "100": "var(--color-error-100)", "200": "var(--color-error-200)", "300": "var(--color-error-300)", "400": "var(--color-error-400)", "500": "var(--color-error-500)", "600": "var(--color-error-600)", "700": "var(--color-error-700)", "800": "var(--color-error-800)", "900": "var(--color-error-900)", "DEFAULT": "var(--color-error)", "focus": "var(--color-error-focus)", "content": "var(--color-error-content)" } },
          },
        },
      },
      variants: {
        extend: {
          backgroundColor: ['active', 'group-hover'],
          textColor: ['active', 'group-hover'],
        },
      },
      plugins: [],
    };

  </script>

  <!--Toggle menu-->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('menu-toggle');
      const menu = document.getElementById('mobile-menu');
      let isOpen = false;

      toggle.addEventListener('click', () => {
        isOpen = !isOpen;
        menu.style.maxHeight = isOpen ? menu.scrollHeight + 'px' : '0px';
      });
    });
  </script>



</head>

<body class="h-full text-base-content">
<header class="bg-gray-900 text-white fixed w-full z-50">
  <div class="flex justify-between items-center px-6 py-4">
    <!-- Logo -->
    <div class="flex items-center space-x-2 font-bold h-10">
      <i class="fa-solid fa-bicycle text-primary-400 text-2xl"></i>
      <a href="index.php" class="text-2xl leading-none">Bike Sharing</a>
    </div>

    <!-- Hamburger (mobile only) -->
    <button id="menu-toggle" class="text-white text-2xl md:hidden">
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Desktop nav -->
    <nav id="desktop-nav" class="hidden md:flex items-center space-x-6">
      <a href="HowItWorks.php" class="hover:text-primary-400">Come funziona</a>
      <a href="noleggio.php" class="hover:text-primary-400">Noleggio</a>
      <a href="#Piani" class="hover:text-primary-400">Piani</a>

      <?php if (isset($_SESSION['utente'])): ?>
      <?php
        $nome = $_SESSION['utente']['nome'];
        $cognome = $_SESSION['utente']['cognome'];
        $initials = getInitials($nome, $cognome);
      ?>
      <a href="profile.php" class="flex items-center space-x-3 group">
        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
          <?php echo $initials; ?>
        </div>
      </a>
      <a href="logout.php" class="ml-3 text-red-500 hover:underline">Logout</a>
      <?php else: ?>
        <a href="login.php"><button class="bg-primary-900 hover:bg-primary-800 px-4 py-2 rounded-lg">Log In</button></a>
        <a href="signup.php"><button class="bg-primary-500 hover:bg-primary-600 px-4 py-2 rounded-lg">Sign Up</button></a>
      <?php endif; ?>
    </nav>
  </div>

  <!-- Mobile nav -->
  <div id="mobile-menu" class="flex flex-col bg-gray-900 text-white p-6 pb-8 space-y-3">
    <a href="HowItWorks.php" class="block hover:text-primary-400">Come funziona</a>
    <a href="#" class="block hover:text-primary-400">Noleggio</a>
    <a href="#Piani" class="block hover:text-primary-400">Piani</a>

    <?php if (isset($_SESSION['utente'])): ?>
      <!-- Logged-in mobile -->
      <a href="profile.php" class="block text-primary-400 font-semibold"> <?php echo $_SESSION['utente']['nome']; ?></a>
      <a href="logout.php" class="block bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg text-left">Logout</a>
    <?php else: ?>
      <!-- Guest mobile -->
      <a href="login.php" class="block bg-primary-900 hover:bg-primary-800 px-4 py-2 rounded-lg text-left">Log In</a>
      <a href="signup.php" class="block bg-primary-500 hover:bg-primary-600 px-4 py-2 rounded-lg text-left">Sign Up</a>
    <?php endif; ?>

    <div class="h-10"></div>
  </div>
</header>



  <!-- Hero Section -->
  <section id="hero" class="relative h-[100vh] flex items-center bg-gray-900 overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img class="object-cover w-full h-full opacity-40"
        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/cba1636e4e-e47c93f69142cd33cf62.png"
        alt="dark moody urban scene with people riding bikes at dusk, city lights in background, cinematic lighting" />
    </div>
    <div class="container mx-auto px-6 z-10 relative">
      <div class="max-w-3xl">
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-4 leading-tight">Your City.<br />Your Ride.</h1>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl">Experience the freedom of urban exploration with our premium
          bike sharing service. Sustainable, convenient, and designed for modern city living.</p>
        <div class="flex flex-col sm:flex-row gap-4">
          <span
            class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center transition-all transform hover:translate-y-[-2px] cursor-pointer">
            <i class="fa-solid fa-bicycle mr-2"></i> <a href="noleggio.php">Noleggia</a> 
          </span>
          <span
            class="bg-gray-800 hover:bg-gray-700 text-white border border-gray-700 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center transition-all transform hover:translate-y-[-2px] cursor-pointer">
            <i class="fa-solid fa-circle-info mr-2"></i> <a href="HowItWorks.php">Scopri come funziona</a>
          </span>
        </div>
      </div>
    </div>
    <div class="absolute bottom-10 left-0 right-0 flex justify-center">
      <span class="text-white animate-bounce cursor-pointer">
        <i class="fa-solid fa-chevron-down text-2xl"></i>
      </span>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="bg-gray-900 py-24 relative">
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">How It Works</h2>
        <p class="text-gray-400 text-xl max-w-2xl mx-auto">Renting a bike has never been easier. Follow these simple
          steps and start your journey.</p>
      </div>

      <div class="relative h-[500px]">

        <div class="relative z-10 flex flex-col md:flex-row items-start justify-between gap-8">
          <div id="step-1"
            class="bg-gray-800 bg-opacity-80 p-8 rounded-xl border-l-4 border-primary-500 transform hover:translate-y-[-28px] transition-all duration-300 w-full md:w-1/4">
            <div class="text-primary-400 text-5xl font-bold mb-4">01</div>
            <h3 class="text-2xl font-bold text-white mb-3">Sign Up</h3>
            <p class="text-gray-300 mb-4">Create your account in seconds. All you need is your email and phone number to
              get started.</p>
            <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center mb-4">
              <i class="fa-solid fa-user-plus text-primary-400 text-2xl"></i>
            </div>
          </div>

          <div id="step-2"
            class="bg-gray-800 bg-opacity-80 p-8 rounded-xl border-l-4 border-primary-500 transform hover:translate-y-[-28px] transition-all duration-300 w-full md:w-1/4 md:mt-16">
            <div class="text-primary-400 text-5xl font-bold mb-4">02</div>
            <h3 class="text-2xl font-bold text-white mb-3">Choose Bike</h3>
            <p class="text-gray-300 mb-4">Browse available bikes nearby. Select the one that suits your needs and
              reserve it instantly.</p>
            <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center mb-4">
              <i class="fa-solid fa-bicycle text-primary-400 text-2xl"></i>
            </div>
          </div>

          <div id="step-3"
            class="bg-gray-800 bg-opacity-80 p-8 rounded-xl border-l-4 border-primary-500 transform hover:translate-y-[-28px] transition-all duration-300 w-full md:w-1/4">
            <div class="text-primary-400 text-5xl font-bold mb-4">03</div>
            <h3 class="text-2xl font-bold text-white mb-3">Ride</h3>
            <p class="text-gray-300 mb-4">Unlock your bike with the app and enjoy your ride. Explore the city at your
              own pace.</p>
            <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center mb-4">
              <i class="fa-solid fa-route text-primary-400 text-2xl"></i>
            </div>
          </div>

          <div id="step-4"
            class="bg-gray-800 bg-opacity-80 p-8 rounded-xl border-l-4 border-primary-500 transform hover:translate-y-[-28px] transition-all duration-300 w-full md:w-1/4 md:mt-16">
            <div class="text-primary-400 text-5xl font-bold mb-4">04</div>
            <h3 class="text-2xl font-bold text-white mb-3">Return</h3>
            <p class="text-gray-300 mb-4">Park your bike at any designated station and end your ride through the app.
              It's that simple.</p>
            <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center mb-4">
              <i class="fa-solid fa-location-dot text-primary-400 text-2xl"></i>
            </div>
          </div>
        </div>


      </div>
    </div>
  </section>



  <!-- Find Bikes Near You Section -->
  <section id="Noleggio" class="bg-gray-800 py-24 relative">
    <div class="container mx-auto px-6">
      <div class="flex flex-col md:flex-row gap-12 items-center">
        <div class="w-full md:w-1/2">
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Find Bikes Near You</h2>
          <p class="text-gray-400 text-xl mb-8">With hundreds of bike stations across the city, you're never far from
            your next ride. Use our interactive map to find the closest available bike.</p>

          <div class="space-y-4 mb-8">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                <i class="fa-solid fa-location-dot text-white"></i>
              </div>
              <div>
                <h4 class="text-white font-bold">Real-time Availability</h4>
                <p class="text-gray-400">See which bikes are available at each station in real-time</p>
              </div>
            </div>

            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                <i class="fa-solid fa-route text-white"></i>
              </div>
              <div>
                <h4 class="text-white font-bold">Route Planning</h4>
                <p class="text-gray-400">Plan your journey with bike-friendly routes and paths</p>
              </div>
            </div>

            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                <i class="fa-solid fa-star text-white"></i>
              </div>
              <div>
                <h4 class="text-white font-bold">Favorite Stations</h4>
                <p class="text-gray-400">Save your most used stations for quick access</p>
              </div>
            </div>
          </div>

          <button
            class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center transition-all transform hover:translate-y-[-2px]">
            <i class="fa-solid fa-map-location-dot mr-2"></i><a href="noleggio.php">Noleggia</a> 
          </button>
        </div>

        <div class="w-full md:w-1/2 relative">
          <div class="absolute -inset-4 bg-primary-500 bg-opacity-20 rounded-xl blur-xl"></div>
          <div class="relative rounded-xl overflow-hidden border border-gray-700 shadow-2xl">
            <div id="map" style="height: 400px; width: 100%;z-index: 0;"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Piani Section -->
  <section id="Piani" class="bg-gray-800 py-24 relative">
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-gray-900 to-transparent"></div>
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Piani</h2>
        <p class="text-gray-400 text-xl max-w-2xl mx-auto">
          Find the plan that suits your riding needs. Start free, upgrade anytime.
        </p>
      </div>
  
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
  
        <!-- Standard Plan -->
        <div id="Piani-standard"
          class="relative z-10 bg-gray-900 rounded-xl overflow-hidden transform hover:translate-y-[-8px] transition-all duration-300 border border-gray-700 hover:border-primary-500">
          <div class="h-2 bg-primary-500"></div>
          <div class="p-8 flex flex-col h-full">
            <h3 class="text-2xl font-bold text-white mb-2">Standard Plan</h3>
            <p class="text-gray-400 mb-6">Start free, perfect for casual users</p>
            <div class="flex items-end gap-2 mb-6">
              <span class="text-5xl font-bold text-white">Free</span>
              <span class="text-gray-400 mb-1">to join</span>
            </div>
  
            <ul class="space-y-3 mb-8 text-gray-300">
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                €5 to unlock a bike
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                €3 per 30 minutes after first 30 mins
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                Access to all bike types
              </li>
            </ul>
  
            <div class="mt-auto">
              <button class="w-full bg-gray-700 hover:bg-primary-600 text-white py-3 rounded-lg transition-colors">
                Start Riding Free
              </button>
            </div>
          </div>
        </div>
  
        <!-- Pro Plan -->
        <div id="Piani-pro"
          class="relative z-10 bg-gray-900 rounded-xl overflow-hidden transform hover:translate-y-[-8px] transition-all duration-300 border border-gray-700 hover:border-primary-500 md:-mt-4">
          <div class="h-2 bg-primary-400"></div>
          <div class="absolute top-0 left-0 bg-primary-500 text-white text-xs px-5 py-1 rounded-br-md">
            MOST POPULAR
          </div>
          <div class="p-8 flex flex-col h-full">
            <h3 class="text-2xl font-bold text-white mb-2">Pro Plan</h3>
            <p class="text-gray-400 mb-6">Pay less per ride with a low monthly fee</p>
            <div class="flex items-end gap-2 mb-6">
              <span class="text-5xl font-bold text-white">€20</span>
              <span class="text-gray-400 mb-1">one-time fee</span>
            </div>
  
            <ul class="space-y-3 mb-8 text-gray-300">
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                €2 to unlock a bike
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                €2 per 30 minutes
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                Access to all bike types
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                Route planning + Mobile app
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                24/7 support
              </li>
            </ul>
  
            <div class="mt-auto">
              <button class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-lg transition-colors">
                Join Pro Plan
              </button>
            </div>
          </div>
        </div>
  
        <!-- Premium Plan -->
        <div id="Piani-premium"
          class="relative z-10 bg-gray-900 rounded-xl overflow-hidden transform hover:translate-y-[-8px] transition-all duration-300 border border-gray-700 hover:border-primary-500">
          <div class="h-2 bg-primary-500"></div>
          <div class="p-8 flex flex-col h-full">
            <h3 class="text-2xl font-bold text-white mb-2">Premium Plan</h3>
            <p class="text-gray-400 mb-6">Best deal for frequent riders</p>
            <div class="flex items-end gap-2 mb-6">
              <span class="text-5xl font-bold text-white">€50</span>
              <span class="text-gray-400 mb-1">one-time fee</span>
            </div>
  
            <ul class="space-y-3 mb-8 text-gray-300">
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                Free unlocks for every ride
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                €1 per 30 minutes
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                Priority electric bike access
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                Route planning + App
              </li>
              <li class="flex items-center">
                <i class="fa-solid fa-check text-primary-400 mr-3"></i>
                24/7 support + Member perks
              </li>
            </ul>
  
            <div class="mt-auto">
              <button class="w-full bg-gray-700 hover:bg-primary-600 text-white py-3 rounded-lg transition-colors">
                Upgrade to Premium
              </button>
            </div>
          </div>
        </div>
  
      </div>
    </div>
  </section>
  

  <!-- App Promotion Section -->
  <section id="app-promo" class="bg-gray-900 py-24 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-primary-500 opacity-10 rounded-l-full blur-3xl"></div>
    <div class="container mx-auto px-6 relative z-10">
      <div class="flex flex-col md:flex-row gap-12 items-center">
        <div class="w-full md:w-1/2 order-2 md:order-1">
          <div class="relative">
            <div class="absolute -inset-4 bg-primary-500 bg-opacity-20 rounded-xl blur-xl"></div>
            <div class="relative flex justify-center">
              <div class="transform rotate-12 translate-x-8">
                <img class="w-[240px] h-auto rounded-3xl shadow-2xl border border-gray-700"
                  src="https://storage.googleapis.com/uxpilot-auth.appspot.com/01e0fd1f14-5fe488f72fa389e1e7d1.png"
                  alt="smartphone showing bike sharing app dark mode UI with map and bike selection screen, sleek modern design" />
              </div>
              <div class="transform -rotate-6 -translate-x-8 translate-y-12">
                <img class="w-[240px] h-auto rounded-3xl shadow-2xl border border-gray-700"
                  src="https://storage.googleapis.com/uxpilot-auth.appspot.com/01e0fd1f14-4125a6837b05ee438837.png"
                  alt="smartphone showing bike sharing app dark mode UI with profile and ride history screen, sleek modern design" />
              </div>
            </div>
          </div>
        </div>

        <div class="w-full md:w-1/2 order-1 md:order-2">
          <div class="bg-gray-800 bg-opacity-50 p-8 rounded-xl backdrop-blur-sm">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Your Bike Companion Is Coming Soon</h2>
            <p class="text-gray-300 text-xl mb-8">Get ready for a seamless bike-sharing experience with our upcoming
              mobile app. Unlock bikes, track routes, and manage your account from anywhere.</p>

            <div class="space-y-4 mb-8">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                  <i class="fa-solid fa-unlock text-white"></i>
                </div>
                <div>
                  <h4 class="text-white font-bold">One-Tap Unlocking</h4>
                  <p class="text-gray-400">Unlock any bike with a simple tap on your phone</p>
                </div>
              </div>

              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                  <i class="fa-solid fa-map text-white"></i>
                </div>
                <div>
                  <h4 class="text-white font-bold">Live Navigation</h4>
                  <p class="text-gray-400">Get turn-by-turn directions to your destination</p>
                </div>
              </div>

              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                  <i class="fa-solid fa-bell text-white"></i>
                </div>
                <div>
                  <h4 class="text-white font-bold">Ride Notifications</h4>
                  <p class="text-gray-400">Get alerts about your ride time and nearby stations</p>
                </div>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
              <button
                class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center justify-center">
                <span>
                  <span class="block font-bold">COMING SOON</span>
                </span>
              </button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="bg-gray-800 py-24 relative">
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-gray-900 to-transparent"></div>
    <div class="container mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">What Our Riders Say</h2>
        <p class="text-gray-400 text-xl max-w-2xl mx-auto">Discover why thousands of riders choose our bike sharing
          service every day.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div id="testimonial-1"
          class="bg-gray-900 p-8 rounded-xl border border-gray-700 transform hover:translate-y-[-8px] transition-all duration-300">
          <div class="flex items-center gap-4 mb-6">
            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-2.jpg" alt="User Avatar"
              class="w-16 h-16 rounded-full object-cover border-2 border-primary-500" />
            <div>
              <h4 class="text-white font-bold">Michael T.</h4>
              <div class="flex text-primary-400">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>
          </div>
          <p class="text-gray-300 mb-6">"I use the electric bikes for my daily commute and it's been a game changer. I
            save money, avoid traffic, and get some exercise. The app makes it super easy to find and unlock bikes."</p>
        </div>

        <div id="testimonial-2"
          class="bg-gray-900 p-8 rounded-xl border border-gray-700 transform hover:translate-y-[-8px] transition-all duration-300">
          <div class="flex items-center gap-4 mb-6">
            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-5.jpg" alt="User Avatar"
              class="w-16 h-16 rounded-full object-cover border-2 border-primary-500" />
            <div>
              <h4 class="text-white font-bold">Sarah K.</h4>
              <div class="flex text-primary-400">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-alt"></i>
              </div>
            </div>
          </div>
          <p class="text-gray-300 mb-6">"The weekly plan is perfect for tourists like me. I explored the entire city on
            these bikes and discovered hidden spots I would have missed otherwise. Highly recommend!"</p>
        </div>

        <div id="testimonial-3"
          class="bg-gray-900 p-8 rounded-xl border border-gray-700 transform hover:translate-y-[-8px] transition-all duration-300">
          <div class="flex items-center gap-4 mb-6">
            <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg" alt="User Avatar"
              class="w-16 h-16 rounded-full object-cover border-2 border-primary-500" />
            <div>
              <h4 class="text-white font-bold">David R.</h4>
              <div class="flex text-primary-400">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>
          </div>
          <p class="text-gray-300 mb-6">"As someone who cares about the environment, I love that I can reduce my carbon
            footprint while getting around. The bikes are always well-maintained and the monthly plan is a great value."
          </p>
        </div>
      </div>

      <div class="mt-12 text-center">
        <button
          class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center">
          <i class="fa-solid fa-pen-to-square mr-2"></i><a href="reviews.html"> Write a Review</a>
        </button>
      </div>
    </div>
  </section>

  <!-- Final CTA Section -->
  <section id="final-cta" class="bg-gray-900 py-24 relative">
    <div class="absolute inset-0 z-0">
      <img class="object-cover w-full h-full opacity-20"
        src="https://storage.googleapis.com/uxpilot-auth.appspot.com/ed82930ebe-b8bb7ecdc7ba1cde9ace.png"
        alt="aerial view of city bike lanes with glowing lines at night, futuristic minimal design" />
    </div>
    <div class="container mx-auto px-6 relative z-10">
      <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Experience the City on Two Wheels?</h2>
        <p class="text-gray-300 text-xl mb-8">Join thousands of riders who have discovered a better way to navigate the
          city. Sign up today and get your first ride free.</p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <span
            class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center transition-all transform hover:translate-y-[-2px] cursor-pointer">
            <i class="fa-solid fa-user-plus mr-2"></i><a href="signup.php">Sign Up Now</a> 
          </span>
          <span
            class="bg-gray-800 hover:bg-gray-700 text-white border border-gray-700 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center transition-all transform hover:translate-y-[-2px] cursor-pointer">
            <i class="fa-solid fa-right-to-bracket mr-2"></i><a href="login.php">Log In</a> 
          </span>
        </div>

      </div>
    </div>
  </section>

  <!-- Footer Section -->
  <footer id="footer" class="bg-gray-900 pt-16 pb-8 border-t border-gray-800">
    <div class="container mx-auto px-6">
      <!-- Logo + social (full width) -->
      <div class="mb-12">
        <div class="text-2xl font-bold text-white flex items-center mb-4">
          <i class="fa-solid fa-bicycle text-primary-400 mr-2"></i>
          <span>Bike Sharing</span>
        </div>
        <p class="text-gray-400 mb-4">Experience the freedom of urban exploration with our premium bike sharing
          service.</p>
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
          <h4 class="text-white font-bold text-lg mb-4">Explore</h4>
          <ul class="space-y-2">
            <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a
                  href="HowItWorks.php">How It Works</a></span></li>
            <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a
                  href="noleggio.php">Noleggio</a></span></li>
            <li><span class="text-gray-400 hover:text-primary-400 transition-colors cursor-pointer"><a
                  href="#Piani">Piani</a></span></li>
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

  <script>
    // Add scroll-based animations and effects
    window.addEventListener('scroll', function () {
      const scrollPosition = window.scrollY;
      const header = document.getElementById('header');

      // Header background opacity based on scroll
      if (scrollPosition > 50) {
        header.classList.add('bg-opacity-90', 'backdrop-blur-sm');
      } else {
        header.classList.remove('bg-opacity-90', 'backdrop-blur-sm');
      }
    });
  </script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <!--Map-->
  <script>
    var map = L.map('map', {
      center: [25.7617, -80.1918], // Miami
      zoom: 13,
      attributionControl: false // <== Hides the Leaflet text
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
      subdomains: 'abcd',
      maxZoom: 19
    }).addTo(map);

    // Custom icon
    var customIcon = L.icon({
      iconUrl: 'https://img.icons8.com/material-rounded/96/FFFFFF/marker.png', // replace with your icon URL
      iconSize: [38, 38], // width, height
      iconAnchor: [19, 38], // point of the icon which will correspond to marker's location
      popupAnchor: [0, -38] // point from which the popup should open relative to the iconAnchor
    });

    L.marker([25.7617, -80.1918], { icon: customIcon })
      .addTo(map)
      .openPopup();
  </script>
</body>

</html>