<?php
// Configurazione del database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registro";

// Crea una connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Query per aggiornare l'immagine del profilo
$sql = "UPDATE users SET profile_image = '../images/CANDE.jpg' WHERE id = 11";

// Esegui la query
if ($conn->query($sql) === TRUE) {
    echo "Immagine del profilo aggiornata con successo";
} else {
    echo "Errore durante l'aggiornamento dell'immagine del profilo: " . $conn->error;
}

// Chiudi la connessione al database
$conn->close();
?>
