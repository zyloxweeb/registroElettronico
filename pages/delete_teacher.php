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
if (!isset($_GET['teacher_id'])) {
    header("Location: teacher_list.php");
    exit();
}

$teacher_id = $_GET['teacher_id'];

// Inizia la transazione
$conn->begin_transaction();

try {
    // Elimina lo studente dalle tabelle correlate
    $sql_delete_grades = "DELETE FROM grades WHERE teacher_id = ?";
    $stmt_delete_grades = $conn->prepare($sql_delete_grades);
    $stmt_delete_grades->bind_param("i", $teacher_id);
    $stmt_delete_grades->execute();

    // Aggiungi qui altre query di eliminazione per le tabelle correlate

    // Elimina lo studente dalla tabella degli studenti
    $sql_delete_student = "DELETE FROM teachers WHERE id = ?";
    $stmt_delete_student = $conn->prepare($sql_delete_student);
    $stmt_delete_student->bind_param("i", $teacher_id);
    $stmt_delete_student->execute();

    // Elimina lo studente dalla tabella degli utenti
    $sql_delete_user = "DELETE FROM users WHERE id = ?";
    $stmt_delete_user = $conn->prepare($sql_delete_user);
    $stmt_delete_user->bind_param("i", $teacher_id);
    $stmt_delete_user->execute();

    // Commit della transazione
    $conn->commit();

    // Redirect alla pagina degli studenti dopo l'eliminazione
    header("Location: teachers_list.php");
    exit();
} catch (Exception $e) {
    // Rollback della transazione in caso di errore
    $conn->rollback();

    // Gestisci l'errore
    echo "Si è verificato un errore durante l'eliminazione del professore: " . $e->getMessage();
}
?>
