<?php
session_start();

if (!isset($_SESSION['utente']['id_utente'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "ornatovschi5413", "", "my_ornatovschi5413");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['fullName'] ?? '';
    $numero_carta = $_POST['cardNumber'] ?? '';
    $data_scadenza = $_POST['expDate'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $id_utente = $_SESSION['utente']['id_utente'];

    $sql = "UPDATE Utente SET nome_carta = ?, numero_carta_credito = ?, data_scadenza = ?, cvv = ? WHERE id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $numero_carta, $data_scadenza, $cvv, $id_utente);

    if ($stmt->execute()) {
        
        $inserisciNoleggio = $conn->prepare("INSERT INTO Noleggio (id_utente, data_noleggio) VALUES (?, NOW())");
        $inserisciNoleggio->bind_param("s", $id_utente);
        $inserisciNoleggio->execute();

        header("Location: index.php");
        exit();
    } else {
        echo "Errore nel salvataggio: " . $stmt->error;
    }

    $stmt->close();
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
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                    },
                    boxShadow: {
                        'card': '0 4px 20px rgba(0, 0, 0, 0.25)',
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

<body class="bg-gray-900 font-sans text-white min-h-screen flex items-center justify-center p-4">
    <div id="payment-container" class="w-full max-w-md">
        <!-- Logo and header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-2">
                <i class="fa-solid fa-bicycle text-primary text-3xl mr-2"></i>
                <h1 class="text-2xl font-bold">BikeShare</h1>
            </div>
            <p class="text-gray-400">Complete your payment to start riding</p>
        </div>

        <!-- Payment card -->
        <div id="payment-card" class="bg-gray-800 rounded-xl p-6 shadow-card mb-6">
            <!-- Price display -->
            <div id="price-display" class="text-center mb-8 py-4 border-b border-gray-700">
                <span class="text-4xl font-bold">€5</span>
                <span class="text-gray-400 text-xl ml-2">for 1 hour</span>
            </div>

            <!-- Payment form -->
            <form id="payment-form" method="POST" action="pagamento.php">
                <div class="space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label for="fullName" class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                        <div class="relative">
                            <input type="text" id="fullName" name="fullName"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Jane Doe" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-regular fa-user text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card Number -->
                    <div>
                        <label for="cardNumber" class="block text-sm font-medium text-gray-300 mb-1">Card Number</label>
                        <div class="relative">
                            <input type="text" id="cardNumber" name="cardNumber"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="1234 5678 9012 3456" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-regular fa-credit-card text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Expiration and CVV -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="expDate" class="block text-sm font-medium text-gray-300 mb-1">Expiration
                                Date</label>
                            <input type="text" id="expDate" name="expDate"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="MM/YY" />
                        </div>
                        <div>
                            <label for="cvv" class="block text-sm font-medium text-gray-300 mb-1">CVV</label>
                            <div class="relative">
                                <input type="text" id="cvv" name="cvv"
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="123" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fa-regular fa-circle-question text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment button -->
                    <button type="submit" id="pay-button"
                        class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-lg mt-4 transition-colors duration-200 flex items-center justify-center">
                        <i class="fa-solid fa-lock mr-2"></i>
                        Pay Now
                    </button>
                </div>
            </form>
        </div>

        <!-- Security note -->
        <div id="security-note" class="text-center text-gray-400 text-sm">
            <p class="flex items-center justify-center">
                <i class="fa-solid fa-shield-halved mr-2"></i>
                Secured by 256-bit SSL encryption
            </p>
            <p class="mt-2">
                By completing this payment, you agree to our
                <span class="text-primary hover:underline cursor-pointer">Terms of Service</span>
            </p>
        </div>

        <!-- Back button -->
        <div class="text-center mt-8">
            <span class="text-gray-400 hover:text-white flex items-center justify-center cursor-pointer">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to bike selection
            </span>
        </div>
    </div>

    <script>
        // Simple card input formatting
        document.getElementById('cardNumber').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';

            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }

            e.target.value = formattedValue.substring(0, 19);
        });
        document.getElementById('cvv').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value.substring(0, 3);
        });

        // Expiration date formatting
        document.getElementById('expDate').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';

            if (value.length > 0) {
                formattedValue = value.substring(0, 2);
                if (value.length > 2) {
                    formattedValue += '/' + value.substring(2, 4);
                }
            }

            e.target.value = formattedValue;
        });

        
    </script>


</body>

</html>