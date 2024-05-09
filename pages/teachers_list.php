<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Ottieni l'elenco degli insegnanti dal database
$sql_teachers = "SELECT * FROM teachers";
$result_teachers = $conn->query($sql_teachers);

// Ottieni l'elenco delle classi dal database (se necessario)
// $sql_classes = "SELECT * FROM classes";
// $result_classes = $conn->query($sql_classes);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Insegnanti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Elenco Insegnanti</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Materia</th>
                    <!-- Aggiungi altre colonne se necessario (come classi insegnate, ecc.) -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_teachers->num_rows > 0) {
                    while ($row = $result_teachers->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['subject'] . "</td>";
                        // Aggiungi altre colonne per altri dettagli se necessario
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nessun insegnante trovato</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p><a href="admin_dashboard.php">Torna alla Dashboard</a></p>
    </div>
</body>

<footer class="footer">
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
    </div>
</footer>

</html>
