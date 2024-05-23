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

// Verifica se l'utente è un insegnante, altrimenti reindirizza alla dashboard appropriata
if ($user['role'] !== 'teacher') {
    header("Location: dashboard.php");
    exit();
}

// Ottieni le classi in cui l'insegnante insegna
$teacher_id = $user['id'];
$sql_classes = "SELECT tc.class_id, c.name FROM teacher_classes tc INNER JOIN classes c ON tc.class_id = c.id WHERE tc.teacher_id = $teacher_id";
$result_classes = $conn->query($sql_classes);

// Ottieni l'immagine del profilo dello studente
$sql_profile_image = "SELECT profile_image FROM users WHERE id = ?";
$stmt_profile_image = $conn->prepare($sql_profile_image);
$stmt_profile_image->bind_param("i", $student_id);
$stmt_profile_image->execute();
$result_profile_image = $stmt_profile_image->get_result();
$profile_image = ($result_profile_image->num_rows > 0) ? $result_profile_image->fetch_assoc()['profile_image'] : '../images/default_profile.jpg';

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
    <title>Dashboard - Insegnante</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
</head>
<body class="bg-gray-900 text-white flex justify-center items-center h-screen">
    <div class="container mx-auto p-8">

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
                                <a href="dropdownProfile/teacher_settings.php" class="block px-4 py-2 text-sm text-white-700 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
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
                    </ul>
                </div>
            </div>
        </nav>

        <div class="text-center mb-12">
            <div id="animated-text" class="text-3xl font-bold mb-2"></div> <!-- Testo animato -->
            <p class="text-lg">Ti trovi nell'area riservata agli insegnanti.</p>
            <p class="text-sm text-gray-400">Qui puoi visualizzare le classi in cui insegni.</p>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg text-center">
            <h3 class="text-xl mb-4">Classi in cui insegni:</h3>
            <ul>
                <?php
                if ($result_classes->num_rows > 0) {
                    while ($row = $result_classes->fetch_assoc()) {
                        $class_id = $row['class_id'];
                        $class_name = $row['name'];
                        echo "<li class='mb-2'><a href='class_students.php?class_id=$class_id' class='block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded'>$class_name</a></li>";
                    }
                } else {
                    echo "<li>Non insegni ancora nessuna classe.</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-8">
            <p>&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </footer>
    </div>

    <script>
        var options = {
            strings: ['Benvenuto, <?php echo $user['username']; ?>!'],
            typeSpeed: 100,
            loop: false
        };
        var typed = new Typed('#animated-text', options);

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
