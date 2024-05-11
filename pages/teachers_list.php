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


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Insegnanti</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Elenco Insegnanti</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border border-gray-300">ID</th>
                    <th class="py-2 px-4 border border-gray-300">Nome</th>
                    <th class="py-2 px-4 border border-gray-300">Materia</th>
                    <!-- Aggiungi altre colonne se necessario (come classi insegnate, ecc.) -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_teachers->num_rows > 0) {
                    while ($row = $result_teachers->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='py-2 px-4 border border-gray-300'>" . $row['id'] . "</td>";
                        echo "<td class='py-2 px-4 border border-gray-300'>" . $row['name'] . "</td>";
                        echo "<td class='py-2 px-4 border border-gray-300'>" . $row['subject'] . "</td>";
                        // Aggiungi altre colonne per altri dettagli se necessario
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='py-2 px-4 border border-gray-300'>Nessun insegnante trovato</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="mt-4"><a href="admin_dashboard.php" class="text-blue-500 hover:text-blue-600">Torna alla Dashboard</a></p>
    </div>

    <footer class="footer absolute bottom-0 w-full bg-gray-800 py-4 text-center">
        <div class="container mx-auto">
            <p class="text-sm text-gray-400">&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>

