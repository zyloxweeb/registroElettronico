<?php
session_start();

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se l'utente è già autenticato, se sì reindirizza alla dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

// Verifica se è stato inviato il modulo di login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ottieni i dati inseriti dall'utente
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query per trovare l'utente nel database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verifica la correttezza della password
        if (password_verify($password, $user['password'])) {
            // Memorizza i dati dell'utente nella sessione
            $_SESSION['user'] = $user;
            // Reindirizza alla dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Nome utente o password errati.";
        }
    } else {
        $error = "Nome utente o password errati.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex justify-center items-center h-full">
    <div class="w-full max-w-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg p-8">
            <h2 class="text-2xl mb-4 text-center">Login</h2>
            <?php if (isset($error)) { ?>
                <div class="bg-red-600 border border-red-600 text-red-100 px-4 py-2 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <form method="post" action="">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-bold mb-2">Nome utente</label>
                    <input type="text" id="username" name="username" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
            </form>
        </div>
        <footer class="text-center mt-8">
            <p>&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </footer>
    </div>
</body>
</html>


