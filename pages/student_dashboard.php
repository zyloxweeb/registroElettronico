<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Ottieni i dettagli dell'utente dalla sessione
$user = $_SESSION['user'];

// Verifica se l'utente è uno studente, altrimenti reindirizza alla dashboard appropriata
if ($user['role'] !== 'student') {
    header("Location: teacher_dashboard.php");
    exit();
}

// Ottieni l'ID dello studente
$student_id = $user['id'];

// Ottieni tutte le materie e le relative medie dei voti dello studente
$sql_courses = "SELECT c.id AS course_id, c.name AS course_name, AVG(g.grade) AS average_grade
                FROM courses c
                LEFT JOIN grades g ON c.id = g.course_id
                WHERE g.student_id = ?
                GROUP BY c.id, c.name
                ORDER BY c.name ASC";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->bind_param("i", $student_id);
$stmt_courses->execute();
$result_courses = $stmt_courses->get_result();
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Studente</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Dashboard Studente</h2>

        <div class="overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="py-2 text-center">Materia</th>
                        <th class="py-2 text-center">Media Voti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_courses->num_rows > 0) {
                        while ($row = $result_courses->fetch_assoc()) {
                            // Calcoliamo il colore in base alla media
                            $media_voti = round($row['average_grade'], 2);
                            $colore = ($media_voti >= 6) ? 'green' : (($media_voti >= 5) ? 'yellow' : 'red');

                            // Stampiamo la riga della tabella con il colore appropriato
                            echo "<tr>";
                            echo "<td class='py-2 text-left'>";
                            echo "<a href='view_grades.php?course_id=" . $row['course_id'] . "' class='text-blue-500 hover:text-blue-600 block text-center'>" . $row['course_name'] . "</a>";
                            echo "</td>";
                            echo "<td class='py-2 text-center'>";
                            echo "<div class='flex items-center justify-center'>";
                            echo "<div class='relative w-12 h-12'>";
                            echo "<div class='absolute w-full h-full rounded-full bg-gray-800'></div>";
                            echo "<div class='absolute w-full h-full rounded-full bg-$colore' style='clip-path: circle(calc($media_voti * 10% - 1px))'></div>";
                            echo "<div class='absolute inset-0 flex items-center justify-center text-xs'>$media_voti</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='py-2'>Nessuna materia trovata.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="mt-8 text-center">
        <p><a href="logout.php" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Logout</a></p>
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
