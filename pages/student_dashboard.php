<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Ottieni i dettagli dell'utente dalla sessione
$user = $_SESSION['user'];

// Verifica se l'utente è uno studente, altrimenti reindirizza alla dashboard appropriata
if ($user['role'] !== 'student') {
    header("Location: dashboard.php");
    exit();
}

// Ottieni l'ID dello studente
$student_id = $user['id'];

// Ottieni l'immagine del profilo dello studente
$sql_profile_image = "SELECT profile_image FROM users WHERE id = ?";
$stmt_profile_image = $conn->prepare($sql_profile_image);
$stmt_profile_image->bind_param("i", $student_id);
$stmt_profile_image->execute();
$result_profile_image = $stmt_profile_image->get_result();
$profile_image = ($result_profile_image->num_rows > 0) ? $result_profile_image->fetch_assoc()['profile_image'] : 'default_profile.jpg';

// Ottieni tutte le materie e le relative medie dei voti dello studente
$sql_courses = "SELECT c.id AS course_id, c.name AS course_name, AVG(g.grade) AS average_grade
                FROM courses c
                LEFT JOIN grades g ON c.id = g.course_id
                WHERE g.student_id = ?
                GROUP BY c.id, c.name
                ORDER BY c.name ASC";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->bind_param("i", $student_id);
$stmt_courses->execute();
$result_courses = $stmt_courses->get_result();
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Studente</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
    <style>
        .open-dropdown {
            z-index: 9999; /* Assicura che il menu sia sopra tutti gli altri elementi */
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <nav class="bg-gray-900 border-gray-200 dark:bg-gray-900 relative">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">

                <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" onclick="toggleDropdown()">
                        <span class="sr-only">Open user menu</span>
                       
                        <img class="w-14 h-14 rounded-full" src="<?php echo htmlspecialchars($profile_image); ?>" alt="user photo">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-gray-600 divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600 absolute top-0 right-0 mt-16" id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-white-900 dark:text-white"><?php echo htmlspecialchars($user['username']); ?></span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-white-700 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Info</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-white-700 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-red-500 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-900 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-gray-700 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <a href="student_dashboard.php" class="block py-2 px-3 text-white bg-white-700 rounded md:bg-transparent md:text-white-700 md:p-0 md:dark:text-white-500" aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="navbarStudent/homeworkStudent.php" class="block py-2 px-3 text-white-900 rounded hover:bg-white-100 md:hover:bg-transparent md:hover:text-white-700 md:p-0 dark:text-white md:dark:hover:text-white-500 dark:hover:bg-white-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-white-700">Compiti</a>
                        </li>
                        <li>
                            <a href="navbarStudent/orario.php" class="block py-2 px-3 text-white-900 rounded hover:bg-white-100 md:hover:bg-transparent md:hover:text-white-700 md:p-0 dark:text-white md:dark:hover:text-white-500 dark:hover:bg-white-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-white-700">Orario</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        

        <div class="bg-gray-800 p-6 rounded-lg">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="py-2 text-center">Materia</th>
                        <th class="py-2 text-center">Media Voti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_courses->num_rows > 0) {
                        while ($row = $result_courses->fetch_assoc()) {
                            // Calcoliamo il colore in base alla media
                            $media_voti = round($row['average_grade'], 2);
                            $colore = ($media_voti >= 6) ? 'green' : (($media_voti >= 5) ? 'yellow' : 'red');

                            // Stampiamo la riga della tabella con il colore appropriato
                            echo "<tr>";
                            echo "<td class='py-2 text-left'>";
                            echo "<a href='view_grades.php?course_id=" . $row['course_id'] . "' class='text-blue-500 hover:text-blue-600 block text-center'>" . $row['course_name'] . "</a>";
                            echo "</td>";
                            echo "<td class='py-2 text-center'>";
                            echo "<div class='flex items-center justify-center'>";
                            echo "<div class='relative w-12 h-12'>";
                            echo "<div class='absolute w-full h-full rounded-full bg-$colore-500'></div>";
                            echo "<div class='absolute inset-0 flex items-center justify-center text-xs'>$media_voti</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='py-2'>Nessuna materia trovata.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
            dropdownMenu.classList.toggle("open-dropdown"); // Aggiungi o rimuovi la classe per il posizionamento del menu
        }
    </script>
</body>
</html>

<?php
// Chiudi la connessione al database
$conn->close();
?>