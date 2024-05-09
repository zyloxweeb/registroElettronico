<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se è stata inviata una richiesta POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se sono stati forniti i dati necessari
    if (isset($_POST['student_id']) && isset($_POST['course_id']) && isset($_POST['grade'])) {
        // Sanitizzazione dei dati del modulo
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $grade = $_POST['grade'];

        // Inserisci il nuovo voto nel database
        $sql_insert_grade = "INSERT INTO grades (student_id, course_id, grade) VALUES (?, ?, ?)";
        $stmt_insert_grade = $conn->prepare($sql_insert_grade);
        $stmt_insert_grade->bind_param("iii", $student_id, $course_id, $grade);

        if ($stmt_insert_grade->execute()) {
            // Voto aggiunto con successo
            header("Location: class_students.php");
            exit();
        } else {
            // Errore durante l'inserimento del voto
            echo "Errore nell'inserimento del voto: " . $stmt_insert_grade->error;
        }
    } else {
        // Dati mancanti nel modulo
        echo "Si è verificato un errore. Assicurati di aver fornito tutti i dati necessari.";
    }
} else {
    // La richiesta non è stata inviata tramite metodo POST
    echo "Metodo di richiesta non consentito.";
}

// Chiudi la connessione al database
$conn->close();
?>
