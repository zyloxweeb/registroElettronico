<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se è stato fornito l'ID dello studente nella query string
if (!isset($_GET['student_id'])) {
    header("Location: teacher_dashboard.php");
    exit();
}

$student_id = $_GET['student_id'];

// Ottieni i dettagli dello studente
$sql_student_details = "SELECT name FROM students WHERE id = ?";
$stmt_student_details = $conn->prepare($sql_student_details);
$stmt_student_details->bind_param("i", $student_id);
$stmt_student_details->execute();
$result_student_details = $stmt_student_details->get_result();

// Verifica se lo studente esiste nel database
if ($result_student_details->num_rows == 0) {
    echo "Lo studente non è stato trovato nel database.";
    exit();
}

$student_name = $result_student_details->fetch_assoc()['name'];

// Ottieni tutte le materie
$sql_courses = "SELECT id, name FROM courses";
$result_courses = $conn->query($sql_courses);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Voto per <?php echo $student_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto max-w-md p-4">
        <h2 class="text-2xl font-bold mb-4">Aggiungi Voto per <?php echo $student_name; ?></h2>

        <form method="post" action="process_add_grade.php">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            <div class="mb-4">
                <label for="course" class="block">Materia:</label>
                <select name="course_id" id="course" class="w-full py-2 px-3 rounded border-gray-300 focus:outline-none focus:border-blue-400 text-black">
                    <?php
                    if ($result_courses->num_rows > 0) {
                        while ($row = $result_courses->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="grade" class="block">Voto:</label>
                <input type="number" name="grade" id="grade" min="0" max="10" step="0.5" required class="w-full py-2 px-3 rounded border-gray-300 focus:outline-none focus:border-blue-400 text-black">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Aggiungi Voto</button>
        </form>

        <p class="mt-4"><a href="class_students.php" class="text-blue-500 hover:text-blue-600">Torna alla lista degli studenti</a></p>
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
