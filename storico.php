<?php
session_start();

if (!isset($_SESSION['utente_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "ornatovschi5413", "LA_TUA_PASSWORD", "my_ornatovschi5413");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$id_utente = $_SESSION['utente_id'];
$sql = "SELECT * FROM Utente WHERE id_utente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_utente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $utente = $result->fetch_assoc();
} else {
    echo "Utente non trovato.";
    exit();
}
$sqlNoleggi = "SELECT data_noleggio FROM Noleggio WHERE id_utente = ? ORDER BY data_noleggio DESC";
$stmtNoleggi = $conn->prepare($sqlNoleggi);
$stmtNoleggi->bind_param("s", $id_utente);
$stmtNoleggi->execute();
$resultNoleggi = $stmtNoleggi->get_result();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script> window.FontAwesomeConfig = { autoReplaceSvg: 'nest' };</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <style>
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            100: '#2E3039',
                            200: '#242731',
                            300: '#1E2029',
                            400: '#191B22',
                            500: '#14161C',
                            800: '#1e40af',
                        },
                        accent: {
                            blue: '#5D8CF7',
                            green: '#38C97C',
                            purple: '#A87FF3',
                            pink: '#F57EB5',
                            yellow: '#F7C65D'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&amp;display=swap" />
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }

        /* Preserve Font Awesome icons */
        .fa,
        .fas,
        .far,
        .fal,
        .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }
    </style>
    <style>
        .highlighted-section {
            outline: 2px solid #3F20FB;
            background-color: rgba(63, 32, 251, 0.1);
        }

        .edit-button {
            position: absolute;
            z-index: 1000;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        html,
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-800 text-gray-200 font-sans">
    <!-- Sidebar Navigation - Updated for mobile responsiveness -->
    <div id="sidebar"
        class="fixed left-0 top-0 h-full w-64 bg-gray-900 shadow-lg z-20 transition-transform duration-300 transform lg:translate-x-0 -translate-x-full">
        <div class="p-5 border-b border-dark-100">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 rounded-md bg-accent-blue bg-opacity-20 flex items-center justify-center text-accent-blue">
                    <i class="fa-solid fa-bicycle"></i>
                </div>
                <h1 class="ml-3 text-xl font-semibold text-white"><a href="index.php">BikeShare</a></h1>
            </div>
        </div>

        <div class="p-5">
            <nav>
                <ul class="space-y-1">
                    <li>
                        <a href="profile.php">
                            <span
                                class="flex items-center px-3 py-2.5 rounded-lg text-gray-400 hover:bg-dark-100 hover:text-white transition-colors cursor-pointer">
                                <i class="fa-solid fa-chart-simple w-5"></i>
                                <span class="ml-3">Dashboard</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <span class="flex items-center px-3 py-2.5 rounded-lg bg-dark-100 text-white cursor-pointer">
                            <i class="fa-solid fa-coins w-5"></i>
                            <span class="ml-3">Storico</span>
                        </span>
                    </li>
                </ul>
            </nav>

            <div class="mt-2 pt-2 border-t border-dark-100">
                <span
                    class="flex items-center px-3 py-2.5 rounded-lg text-gray-400 hover:bg-dark-100 hover:text-white transition-colors cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span class="ml-3"><a href="logout.php">Logout</a></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="fixed top-4 left-4 z-30 lg:hidden bg-gray-900 p-2 rounded-lg text-white">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Main Content Area - Updated for mobile responsiveness -->
    <div id="main-content" class="lg:ml-64 min-h-screen transition-all duration-300">
        <!-- Top Bar - Updated -->
        <div id="topbar" class="bg-gray-900 shadow-md h-16 flex items-center justify-between px-4 lg:px-6">
            <div class="relative hidden md:block">

            </div>

            <div class="flex items-center space-x-2 md:space-x-4 ml-auto">
                <div class="flex items-center">
                    <div class="mr-3 text-right hidden sm:block">
                        <p class="text-sm font-medium text-white">
                            <?php echo htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']); ?>
                        </p>
                        <p class="text-xs text-gray-400">Piano gratis</p>
                    </div>
                    <?php
                        $initials = strtoupper(substr($utente['nome'], 0, 1) . substr($utente['cognome'], 0, 1));
                    ?>
                    <div class="w-10 h-10 rounded-full bg-accent-blue flex items-center justify-center text-white font-medium text-sm">
                        <?php echo $initials; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content - Updated -->
        <div id="profile-content" class="p-4 md:p-6">
            <h1 class="text-2xl font-semibold mb-6">Storico noleggi</h1>
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-4 md:gap-6">
                
                <div class="bg-gray-900 rounded-xl p-6 shadow-lg">
                    <?php if ($resultNoleggi->num_rows > 0): ?>
                        <table class="w-full text-left text-sm">
                            <thead class="text-gray-400 border-b border-gray-700">
                                <tr>
                                    <th class="py-2">Data Noleggio</th>
                                    <th class="py-2">Utente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultNoleggi->fetch_assoc()): ?>
                                    <tr class="border-b border-gray-800 hover:bg-gray-800 transition">
                                        <td class="py-2"><?php echo date("d/m/Y H:i", strtotime($row['data_noleggio'])); ?></td>
                                        <td class="py-2"><?php echo htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-gray-400">Nessun noleggio effettuato finora.</p>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>










    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mainContent = document.getElementById('main-content');

        // Toggle sidebar and hide/show burger icon
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full');
            mainContent.classList.toggle('lg:ml-64');

            // Hide the menu button when opening sidebar
            if (!isOpen) {
                mobileMenuBtn.classList.add('hidden');
            }
        });

        // Close sidebar when clicking outside (on mobile only)
        document.addEventListener('click', (e) => {
            const clickedOutside =
                window.innerWidth < 1024 &&
                !sidebar.contains(e.target) &&
                !mobileMenuBtn.contains(e.target) &&
                !sidebar.classList.contains('-translate-x-full');

            if (clickedOutside) {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('lg:ml-64');
                mobileMenuBtn.classList.remove('hidden'); // Show burger icon back
            }
        });

        // Restore button on desktop if resizing from mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                mobileMenuBtn.classList.remove('hidden');
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.add('lg:ml-64');
            }
        });
    </script>



</body>

</html>