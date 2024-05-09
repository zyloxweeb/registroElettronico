<?php
// Dati di connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registro";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Imposta il set di caratteri della connessione
mysqli_set_charset($conn, 'utf8');

?>
