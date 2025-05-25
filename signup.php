<?php
$conn = new mysqli("localhost", "ornatovschi5413", "", "my_ornatovschi5413");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid("user_");
    $nome = $_POST['name'];
    $cognome = $_POST['surname'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Utente (id_utente, nome, cognome, email, pass, numero_carta_credito, indirizzo, cap, cellulare, data_scadenza, cvv, nome_carta)
            VALUES (?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $id, $nome, $cognome, $email, $pass);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: login.php?registered=1");
        exit();
    } else {
        echo "<script>alert('Registrazione fallita');</script>";
    }

    $stmt->close();
    $conn->close();
}
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

    <div id="signup-page" class="flex items-center justify-center min-h-screen px-4 py-12">
        <div id="signup-card" class="w-full max-w-md bg-dark-bg-2 rounded-xl shadow-custom p-8 card-animation">
            <div id="logo" class="flex justify-center mb-6">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-bicycle text-primary-blue text-3xl"></i>
                    <h1 class="text-white font-bold text-xl">Bike Sharing</h1>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-white text-center mb-8">Crea il tuo account</h2>

            <form id="signup-form" class="space-y-5" method="POST">
                <div id="firstname-field">
                    <label for="firstname" class="block text-sm font-medium text-gray-300 mb-1">Nome</label>
                    <input type="text" id="firstname" name="name"
                        class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                        placeholder="Mario" required>
                </div>

                <div id="lastname-field">
                    <label for="lastname" class="block text-sm font-medium text-gray-300 mb-1">Cognome</label>
                    <input type="text" id="lastname" name="surname"
                        class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                        placeholder="Rossi" required>
                </div>

                <div id="email-field">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Indirizzo Email</label>
                    <input type="email" id="email" name="email"
                        class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                        placeholder="tuo@email.com" required>
                </div>

                <div id="password-field">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                            placeholder="••••••••••" required>
                        <button type="button"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-200">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div id="confirm-password-field">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-300 mb-1">Conferma
                        Password</label>
                    <div class="relative">
                        <input type="password" id="confirm-password" name="confirm_password"
                            class="w-full bg-dark-bg border border-gray-700 text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-blue input-focus-effect"
                            placeholder="••••••••••" required>
                        <button type="button"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-200">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <p id="password-error" class="text-red-400 text-sm mt-1 hidden">Le password non corrispondono.</p>
                </div>

                <button type="submit"
                    class="w-full bg-primary-blue hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 btn-hover-effect mt-8">
                    Iscriviti
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-400">
                    Hai già un account?
                    <span class="text-primary-blue hover:text-blue-400 font-medium transition cursor-pointer">
                        <a href="login.php">Accedi</a>
                    </span>
                </p>
            </div>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-dark-bg-2 text-gray-400">oppure continua con</span>
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
            // Toggle password visibility
            const togglePasswordButtons = document.querySelectorAll('.fa-eye, .fa-eye-slash');

            togglePasswordButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const input = this.closest('.relative').querySelector('input');
                    const icon = this;

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>


    <script>
        document.getElementById("signup-form").addEventListener("submit", function (e) {
            const password = document.getElementById("password").value;
            const confirm = document.getElementById("confirm-password").value;

            if (password !== confirm) {
                e.preventDefault(); // Stop form submission
                alert("Passwords non combaciano!");
            }
        });
    </script>

</body>

</html>