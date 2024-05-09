<?php
session_start();

// Verifica se l'utente è autenticato come studente
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Ottieni l'ID dello studente dalla sessione
$student_id = $_SESSION['user']['id'];

// Verifica se è stata fornita un'ID della materia nella query string
if (!isset($_GET['course_id'])) {
    header("Location: student_dashboard.php");
    exit();
}

$course_id = $_GET['course_id'];

// Ottieni il nome della materia
$sql_course_name = "SELECT name FROM courses WHERE id = ?";
$stmt_course_name = $conn->prepare($sql_course_name);
$stmt_course_name->bind_param("i", $course_id);
$stmt_course_name->execute();
$result_course_name = $stmt_course_name->get_result();
$course_name = ($result_course_name->num_rows > 0) ? $result_course_name->fetch_assoc()['name'] : 'Materia sconosciuta';

// Ottieni i voti dello studente per questa materia
$sql_student_grades = "SELECT grade FROM grades WHERE student_id = ? AND course_id = ?";
$stmt_student_grades = $conn->prepare($sql_student_grades);
$stmt_student_grades->bind_param("ii", $student_id, $course_id);
$stmt_student_grades->execute();
$result_student_grades = $stmt_student_grades->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voti per <?php echo $course_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Voti per <?php echo $course_name; ?></h2>

        <table class="w-full">
            <thead>
                <tr>
                    <th class="py-2">Voto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_student_grades->num_rows > 0) {
                    while ($row = $result_student_grades->fetch_assoc()) {
                        $grade = $row['grade'];
                        $colore = ($grade >= 6) ? 'green' : (($grade >= 5) ? 'yellow' : 'red');
                        echo "<tr>";
                        echo "<td class='py-2'>";
                        echo "<div class='flex items-center justify-center'>";
                        echo "<div class='relative w-12 h-12 mr-4'>";
                        echo "<div class='absolute w-full h-full rounded-full bg-gray-800'></div>";
                        echo "<div class='absolute w-full h-full rounded-full bg-$colore' style='clip-path: circle(calc($grade * 10% - 1px))'></div>";
                        echo "<div class='absolute inset-0 flex items-center justify-center text-xs'>$grade</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td class='py-2'>Nessun voto trovato.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="mt-4"><a href="student_dashboard.php" class="text-blue-500 hover:text-blue-600">Torna alla Dashboard Studente</a></p>
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
