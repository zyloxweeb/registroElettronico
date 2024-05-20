<?php
session_start();
// Include il file di connessione al database
include '../../includes/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Aggiornamento delle informazioni dell'utente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = !empty($_POST['username']) ? $_POST['username'] : $user['username'];
    $image_path = $user['profile_image'];
    $password_changed = false;

    // Gestione dell'immagine del profilo
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $profile_image = $_FILES['profile_image'];
        $upload_dir = '../../images/';
        $upload_file = $upload_dir . basename($profile_image['name']);

        // Verifica che il file sia un'immagine
        $check = getimagesize($profile_image['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($profile_image['tmp_name'], $upload_file)) {
                $image_path = '../images/' . basename($profile_image['name']);
            } else {
                $error_message = "Errore nel caricamento dell'immagine.";
            }
        } else {
            $error_message = "Il file non è un'immagine valida.";
        }
    }

    // Gestione della password
    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $error_message = "La nuova password e la conferma non coincidono.";
        } else {
            // Verifica la password corrente
            $sql_password_check = "SELECT password FROM users WHERE id = ?";
            $stmt_password_check = $conn->prepare($sql_password_check);
            $stmt_password_check->bind_param("i", $user_id);
            $stmt_password_check->execute();
            $result_password_check = $stmt_password_check->get_result();
            $row = $result_password_check->fetch_assoc();

            if (password_verify($current_password, $row['password'])) {
                // Aggiorna la password
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_update_password = "UPDATE users SET password = ? WHERE id = ?";
                $stmt_update_password = $conn->prepare($sql_update_password);
                $stmt_update_password->bind_param("si", $new_password_hashed, $user_id);
                $stmt_update_password->execute();
                $password_changed = true;
            } else {
                $error_message = "La password corrente non è corretta.";
            }
        }
    }

    // Aggiorna le informazioni nel database
    if (!isset($error_message)) {
        $sql_update = "UPDATE users SET username = ?, profile_image = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $username, $image_path, $user_id);

        if ($stmt_update->execute()) {
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['profile_image'] = $image_path;
            $success_message = "Informazioni aggiornate con successo.";
            if ($password_changed) {
                $success_message .= " La password è stata cambiata con successo.";
            }
        } else {
            $error_message = "Errore nell'aggiornamento delle informazioni.";
        }
    }
}

// Ottieni le informazioni aggiornate dell'utente
$sql_user_info = "SELECT * FROM users WHERE id = ?";
$stmt_user_info = $conn->prepare($sql_user_info);
$stmt_user_info->bind_param("i", $user_id);
$stmt_user_info->execute();
$result_user_info = $stmt_user_info->get_result();
$user_info = $result_user_info->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4 flex flex-col items-center">
        <nav class="bg-gray-900 border-gray-200 dark:bg-gray-900 relative w-full">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" onclick="toggleDropdown()">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-14 h-14 rounded-full" src="<?php echo htmlspecialchars("../{$user_info['profile_image']}"); ?>" alt="user photo">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-gray-600 divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600 absolute top-0 right-0 mt-16" id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-white-900 dark:text-white"><?php echo htmlspecialchars($user_info['username']); ?></span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="info.php" class="block px-4 py-2 text-sm text-white-700 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Info</a>
                            </li>
                            <li>
                                <a href="settings.php" class="block px-4 py-2 text-sm text-white-700 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                            </li>
                            <li>
                                <a href="../logout.php" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-900 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-gray-700 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <a href="../student_dashboard.php" class="block py-2 px-3 text-white bg-white-700 rounded md:bg-transparent md:text-white-700 md:p-0 md:dark:text-white-500" aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="../navbarStudent/homeworkStudent.php" class="block py-2 px-3 text-white-900 rounded hover:bg-white-100 md:hover:bg-transparent md:hover:text-white-700 md:p-0 dark:text-white md:dark:hover:text-white-500 dark:hover:bg-white-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-white-700">Compiti</a>
                        </li>
                        <li>
                            <a href="../navbarStudent/orario.php" class="block py-2 px-3 text-white-900 rounded hover:bg-white-100 md:hover:bg-transparent md:hover:text-white-700 md:p-0 dark:text-white md:dark:hover:text-white-500 dark:hover:bg-white-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-white-700">Orario</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="bg-gray-800 p-6 rounded-lg mt-8 w-full max-w-xl text-center">
            <h2 class="text-2xl font-bold mb-4">Impostazioni</h2>
            <?php if (isset($error_message)): ?>
                <p class="text-red-500"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <p class="text-green-500"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            <form action="settings.php" method="POST" enctype="multipart/form-data" class="flex flex-col items-center space-y-4">
                <div class="w-full">
                    <label for="username" class="block mb-2 text-sm font-medium text-white">Nome Login</label>
                    <input type="text" name="username" id="username" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo htmlspecialchars($user_info['username']); ?>" required>
                </div>
                <div class="w-full">
                    <label for="profile_image" class="block mb-2 text-sm font-medium text-white">Immagine profilo</label>
                    <input type="file" name="profile_image" id="profile_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                </div>
                <div class="w-full">
                    <label for="current_password" class="block mb-2 text-sm font-medium text-white">Password Corrente</label>
                    <input type="password" name="current_password" id="current_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label for="new_password" class="block mb-2 text-sm font-medium text-white">Nuova Password</label>
                    <input type="password" name="new_password" id="new_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <div class="w-full">
                    <label for="confirm_password" class="block mb-2 text-sm font-medium text-white">Conferma Nuova Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Aggiorna</button>
            </form>
        </div>

        <footer class="footer bottom-0 w-full bg-gray-800 py-4 text-center my-10">
            <div class="container mx-auto">
                <p class="text-sm text-gray-400">&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
            </div>
        </footer>
    </div>
    
    <script>
        // Funzione per aprire o chiudere il menu
        function toggleDropdown() {
            var dropdownMenu = document.getElementById("user-dropdown");
            dropdownMenu.classList.toggle("hidden");
            dropdownMenu.classList.toggle("open-dropdown");
        }
    </script>
</body>
</html>
