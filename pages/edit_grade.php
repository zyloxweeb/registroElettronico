<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se è stato fornito l'ID del voto nella query string
if (!isset($_GET['grade_id'])) {
    header("Location: teacher_dashboard.php");
    exit();
}

$grade_id = $_GET['grade_id'];

// Ottieni i dettagli del voto
$sql_grade_details = "SELECT g.id AS grade_id, g.grade, c.name AS course_name, s.name AS student_name 
                        FROM grades g 
                        LEFT JOIN courses c ON g.course_id = c.id 
                        LEFT JOIN students s ON g.student_id = s.id 
                        WHERE g.id = ?";
$stmt_grade_details = $conn->prepare($sql_grade_details);
$stmt_grade_details->bind_param("i", $grade_id);
$stmt_grade_details->execute();
$result_grade_details = $stmt_grade_details->get_result();

// Verifica se il voto esiste nel database
if ($result_grade_details->num_rows == 0) {
    echo "Il voto non è stato trovato nel database.";
    exit();
}

$grade = $result_grade_details->fetch_assoc();

// Gestisci la modifica del voto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_grade = $_POST['new_grade'];

    // Aggiorna il voto nel database
    $sql_update_grade = "UPDATE grades SET grade = ? WHERE id = ?";
    $stmt_update_grade = $conn->prepare($sql_update_grade);
    $stmt_update_grade->bind_param("di", $new_grade, $grade_id);

    if ($stmt_update_grade->execute()) {
        // Reindirizza alla pagina di visualizzazione del voto aggiornato
        header("Location: edit_students.php?student_name=" . urlencode($grade['student_name']));
        exit();
    } else {
        echo "Errore nell'aggiornamento del voto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Voto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto max-w-md p-4">
        <h2 class="text-2xl font-bold mb-4">Modifica Voto per <?php echo $grade['student_name']; ?></h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?grade_id=" . $grade_id); ?>">
            <div class="mb-4">
                <label for="new_grade" class="block">Nuovo Voto:</label>
                <input type="number" name="new_grade" id="new_grade" min="0" max="10" step="0.5" value="<?php echo $grade['grade']; ?>" required class="w-full py-2 px-3 rounded border-gray-300 focus:outline-none focus:border-blue-400 text-black">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Salva Modifiche</button>
        </form>

        <p class="mt-4"><a href="edit_students.php?student_name=<?php echo urlencode($grade['student_name']); ?>" class="text-blue-500 hover:text-blue-600">Annulla</a></p>
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
