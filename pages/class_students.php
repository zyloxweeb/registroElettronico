<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se è stata fornita un'ID di classe nella query string
if (!isset($_GET['class_id'])) {
    header("Location: teacher_dashboard.php");
    exit();
}

$class_id = $_GET['class_id'];

// Ottieni il nome della classe
$sql_class_name = "SELECT name FROM classes WHERE id = ?";
$stmt_class_name = $conn->prepare($sql_class_name);
$stmt_class_name->bind_param("i", $class_id);
$stmt_class_name->execute();
$result_class_name = $stmt_class_name->get_result();
$class_name = ($result_class_name->num_rows > 0) ? $result_class_name->fetch_assoc()['name'] : 'Classe sconosciuta';

// Ottieni i nomi degli studenti della classe specificata
$sql_students = "SELECT DISTINCT name AS student_name
                FROM students 
                WHERE class = ?
                ORDER BY name ASC";
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("s", $class_name);
$stmt_students->execute();
$result_students = $stmt_students->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studenti della Classe <?php echo $class_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white flex justify-center items-start h-screen">
    <div class="container mx-auto p-2 flex flex-col justify-start items-center">
        <h2 class="text-lg font-bold mb-2">Studenti della Classe <?php echo $class_name; ?></h2>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <table class="w-full divide-y divide-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-300 uppercase">Nome Studente</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-300 uppercase">Modifica</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php
                    if ($result_students->num_rows > 0) {
                        while ($row = $result_students->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-2 py-1 whitespace-nowrap'>" . $row['student_name'] . "</td>";
                            echo "<td class='px-2 py-1 whitespace-nowrap'>";
                            echo "<button onclick=\"location.href='edit_students.php?student_name=" . $row['student_name'] . "'\" class='bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded'>Modifica</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td class='px-2 py-1 whitespace-nowrap' colspan='2'>Nessuno studente trovato.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-center items-center">
    <a href="teacher_dashboard.php" class="text-blue-500 hover:text-blue-600 font-semibold text-sm inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
            <path fill-rule="evenodd" d="M10 0a5 5 0 0 1 5 5c0 2.425-1.774 4.428-4.074 4.898A8 8 0 0 1 16 18H4a8 8 0 0 1 5.074-8.102C6.774 9.428 5 7.425 5 5a5 5 0 0 1 5-5zm0 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 2c-2.667 0-8 1.333-8 4v2h16v-2c0-2.667-5.333-4-8-4z" clip-rule="evenodd"/>
        </svg>
        Torna alla Dashboard Insegnante
    </a>
</div>

    <footer class="footer absolute bottom-0 w-full bg-gray-800 py-4 text-center">
        <div class="container mx-auto">
            <p class="text-sm text-gray-400">&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>



<?php
// Chiudi la connessione al database
$conn->close();
?>
