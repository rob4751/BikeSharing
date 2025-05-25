<?php
session_start(); // Start the session at the top

// Connect to the database
$conn = new mysqli("localhost", "ornatovschi5413", "", "my_ornatovschi5413");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form input
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL statement
$stmt = $conn->prepare("SELECT * FROM Utente WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($user = $result->fetch_assoc()) {
    // Check if password is correct
    if (password_verify($password, $user['pass'])) {
        // ✅ Save user info into session
        $_SESSION['utente'] = [
            'nome' => $user['nome'],
            'cognome' => $user['cognome'],
            'email' => $user['email'],
            'id_utente' => $user['id_utente']
        ];
        $_SESSION['utente_id'] = $user['id_utente'];

        // ✅ Redirect to desktop.php
        header("Location: index.php");
        exit();
    } else {
        echo "Incorrect password";
    }
} else {
    echo "";
}

$conn->close();
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
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'dark-bg': '#14161C',
                        'dark-bg-2': '#242731',
                        'primary-blue': '#3b82f6',
                    },
                    boxShadow: {
                        'custom': '0 4px 14px 0 rgba(0, 0, 0, 0.25)',
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-gradient-dark {
            background: #1E3A8A;
            background: radial-gradient(circle, rgba(30, 58, 138, 1) 46%, rgba(17, 25, 39, 1) 100%);
        }

        .input-focus-effect:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        .card-animation {
            transition: transform 0.3s ease;
        }

        .card-animation:hover {
            transform: translateY(-2px);
        }

        .btn-hover-effect {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-hover-effect:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&amp;display=swap" />
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

<body class="bg-gradient-dark min-h-screen">
    <?php if (isset($_GET['registered'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const msg = document.createElement('div');
        msg.innerHTML = `
            <div style="position:fixed;top:30px;left:50%;transform:translateX(-50%);
            background:#22c55e;color:white;padding:10px 20px;border-radius:8px;
            box-shadow:0 2px 10px rgba(0,0,0,0.2);font-family:sans-serif;font-weight:600;
            display:flex;align-items:center;gap:10px;z-index:9999;">
                <i class='fa-solid fa-check'></i> Utente registrato con successo!
            </div>
        `;
        document.body.appendChild(msg);
        setTimeout(() => msg.remove(), 2500);
    });
    </script>
    <?php endif; ?>

    <div id="login-page" class="flex items-center justify-center min-h-screen px-4 py-12">
        <div id="login-card" class="w-full max-w-md bg-dark-bg-2 rounded-xl shadow-custom p-8 card-animation">
            <div id="logo" class="flex justify-center mb-6">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-bicycle text-primary-blue text-3xl"></i>
                    <h1 class="text-white font-bold text-xl">Bike Sharing</h1>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-white text-center mb-8">Bentornato</h2>

            <form id="login-form" class="space-y-5" method="POST">
                <div id="email-field">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Indirizzo Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                        placeholder="tuo@email.com"/>
                </div>

                <div id="password-field">
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                            placeholder="••••••••••" />
                        <button type="button"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-200">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary-blue hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 btn-hover-effect mt-8">
                    Accedi
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-400">
                    Non hai un account?
                    <span class="text-primary-blue hover:text-blue-400 font-medium transition cursor-pointer"><a href="signup.php">Iscriviti</a></span>
                </p>
            </div>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-dark-bg-2 text-gray-400">Oppure continua con</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-3 gap-3">
                    <span
                        class="flex justify-center py-2 px-4 border border-gray-700 rounded-lg hover:bg-gray-800 transition cursor-pointer">
                        <i class="fa-brands fa-google text-gray-300"></i>
                    </span>
                    <span
                        class="flex justify-center py-2 px-4 border border-gray-700 rounded-lg hover:bg-gray-800 transition cursor-pointer">
                        <i class="fa-brands fa-apple text-gray-300"></i>
                    </span>
                    <span
                        class="flex justify-center py-2 px-4 border border-gray-700 rounded-lg hover:bg-gray-800 transition cursor-pointer">
                        <i class="fa-brands fa-facebook-f text-gray-300"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password visibility toggle
            const toggleButtons = document.querySelectorAll('.fa-eye, .fa-eye-slash');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const input = this.closest('.relative').querySelector('input');
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            });

            // Switch between login and signup views
            const loginLink = document.getElementById('login-link');
            const signupLink = document.getElementById('signup-link');
            const loginPage = document.getElementById('login-page');
            const signupPage = document.getElementById('signup-page');

            if (loginLink && signupLink) {
                loginLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    loginPage.classList.remove('hidden');
                    signupPage.classList.add('hidden');
                });

                signupLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    loginPage.classList.add('hidden');
                    signupPage.classList.remove('hidden');
                });
            }
        });
    </script>

</body>

</html>
