<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include 'database.php';

// Ottieni i dettagli dell'utente dalla sessione
$user = $_SESSION['user'];

// In base al ruolo dell'utente, reindirizza alla dashboard appropriata
$role = $user['role'];
switch ($role) {
    case 'student':
        header("Location: student_dashboard.php");
        exit();
    case 'teacher':
        header("Location: teacher_dashboard.php");
        exit();
    case 'administration':
        header("Location: admin_dashboard.php");
        exit();
    default:
        header("Location: login.php");
        exit();
}
?>
