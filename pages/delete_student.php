<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Verifica se è stato fornito l'ID dello studente
if (!isset($_GET['student_id'])) {
    header("Location: students_list.php");
    exit();
}

$student_id = $_GET['student_id'];

// Query per eliminare lo studente dal database
$sql_delete_student = "DELETE FROM students WHERE id = ?";
$stmt_delete_student = $conn->prepare($sql_delete_student);
$stmt_delete_student->bind_param("i", $student_id);
$stmt_delete_student->execute();

// Redirect alla pagina degli studenti dopo l'eliminazione
header("Location: students_list.php");
exit();
?>
