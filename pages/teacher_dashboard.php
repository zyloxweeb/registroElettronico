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

// Verifica se l'utente è un insegnante, altrimenti reindirizza alla dashboard appropriata
if ($user['role'] !== 'teacher') {
    header("Location: dashboard.php");
    exit();
}

// Ottieni le classi in cui l'insegnante insegna
$teacher_id = $user['id'];
$sql_classes = "SELECT tc.class_id, c.name FROM teacher_classes tc INNER JOIN classes c ON tc.class_id = c.id WHERE tc.teacher_id = $teacher_id";
$result_classes = $conn->query($sql_classes);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Insegnante</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white flex justify-center items-center h-screen">
    <div class="container mx-auto p-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-2">Benvenuto, <?php echo $user['username']; ?>!</h2>
            <p class="text-lg">Ti trovi nell'area riservata agli insegnanti.</p>
            <p class="text-sm text-gray-400">Qui puoi visualizzare le classi in cui insegni.</p>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg text-center">
            <h3 class="text-xl mb-4">Classi in cui insegni:</h3>
            <ul>
                <?php
                if ($result_classes->num_rows > 0) {
                    while ($row = $result_classes->fetch_assoc()) {
                        $class_id = $row['class_id'];
                        $class_name = $row['name'];
                        echo "<li class='mb-2'><a href='class_students.php?class_id=$class_id' class='block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded'>$class_name</a></li>";
                    }
                } else {
                    echo "<li>Non insegni ancora nessuna classe.</li>";
                }
                ?>
            </ul>
        </div>

        <div class="mt-8 text-center">
            <p><a href="logout.php" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Logout</a></p>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-8">
            <p>&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </footer>
    </div>
</body>
</html>





<?php
// Chiudi la connessione al database
$conn->close();
?>
