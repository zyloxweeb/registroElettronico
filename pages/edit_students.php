<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se è stato fornito il nome dello studente nella query string
if (!isset($_GET['student_name'])) {
    header("Location: teacher_dashboard.php");
    exit();
}

$student_name = $_GET['student_name'];

// Ottieni l'ID dello studente
$sql_student_id = "SELECT id FROM students WHERE name = ?";
$stmt_student_id = $conn->prepare($sql_student_id);
$stmt_student_id->bind_param("s", $student_name);
$stmt_student_id->execute();
$result_student_id = $stmt_student_id->get_result();
$student_id = ($result_student_id->num_rows > 0) ? $result_student_id->fetch_assoc()['id'] : null;

// Verifica se lo studente esiste nel database
if (!$student_id) {
    echo "Lo studente non è stato trovato nel database.";
    exit();
}

// Ottieni tutti i voti dello studente
$sql_grades = "SELECT g.id AS grade_id, c.name AS course_name, g.grade 
                FROM grades g 
                LEFT JOIN courses c ON g.course_id = c.id 
                WHERE student_id = ?";
$stmt_grades = $conn->prepare($sql_grades);
$stmt_grades->bind_param("i", $student_id);
$stmt_grades->execute();
$result_grades = $stmt_grades->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Studente <?php echo $student_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white flex justify-center items-start min-h-screen">
    <div class="container mx-auto p-4">
        <h2 class="text-lg font-bold mb-4">Modifica Studente <?php echo $student_name; ?></h2>

        <div class="bg-gray-800 rounded-lg overflow-hidden mb-4">
            <table class="w-full divide-y divide-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Materia</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Voto</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Azioni</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php
                    if ($result_grades->num_rows > 0) {
                        while ($row = $result_grades->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-4 py-2 whitespace-nowrap'>" . $row['course_name'] . "</td>";
                            echo "<td class='px-4 py-2 whitespace-nowrap'>" . $row['grade'] . "</td>";
                            echo "<td class='px-4 py-2 whitespace-nowrap'>";
                            echo "<button onclick=\"location.href='edit_grade.php?grade_id=" . $row['grade_id'] . "'\" class='bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded'>Modifica Voto</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td class='px-4 py-2 whitespace-nowrap' colspan='3'>Nessun voto trovato per questo studente.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <button onclick="location.href='add_grade.php?student_id=<?php echo $student_id; ?>'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-4">Aggiungi Nuovo Voto</button>

        <p><a href="class_students.php" class="text-blue-500 hover:text-blue-600">Torna alla lista degli studenti</a></p>
    </div>

</body>

    <footer class="footer absolute bottom-0 w-full bg-gray-800 py-4 text-center">
        <div class="container mx-auto">
            <p class="text-sm text-gray-400">&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </div>
    </footer>
</html>


<?php
// Chiudi la connessione al database
$conn->close();
?>
