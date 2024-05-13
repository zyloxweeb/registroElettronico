<?php
session_start();

// Verifica se l'utente è autenticato come studente
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Include il file di connessione al database
include '../includes/database.php';

// Ottieni l'ID dello studente dalla sessione
$student_id = $_SESSION['user']['id'];

// Verifica se è stata fornita un'ID della materia nella query string
if (!isset($_GET['course_id'])) {
    header("Location: student_dashboard.php");
    exit();
}

$course_id = $_GET['course_id'];

// Ottieni il nome della materia
$sql_course_name = "SELECT name FROM courses WHERE id = ?";
$stmt_course_name = $conn->prepare($sql_course_name);
$stmt_course_name->bind_param("i", $course_id);
$stmt_course_name->execute();
$result_course_name = $stmt_course_name->get_result();
$course_name = ($result_course_name->num_rows > 0) ? $result_course_name->fetch_assoc()['name'] : 'Materia sconosciuta';

// Ottieni i voti dello studente per questa materia
$sql_student_grades = "SELECT grade FROM grades WHERE student_id = ? AND course_id = ?";
$stmt_student_grades = $conn->prepare($sql_student_grades);
$stmt_student_grades->bind_param("ii", $student_id, $course_id);
$stmt_student_grades->execute();
$result_student_grades = $stmt_student_grades->get_result();

// Prepara i dati per il grafico
$labels = [];
$data = [];

while ($row = $result_student_grades->fetch_assoc()) {
    $data[] = $row['grade'];
    // Possibili label
    $labels[] = ''; // Qui puoi inserire eventuali nomi o descrizioni per ciascun voto
}

$chart_data = json_encode([
    'labels' => $labels,
    'data' => $data
]);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voti per <?php echo $course_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
    <style>
        canvas {
            max-width: 400px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Andamento dei Voti per <?php echo $course_name; ?></h2>

        <canvas id="myChart" width="400" height="200"></canvas>

        <h2 class="text-xl font-bold mt-8 mb-4">Voti per <?php echo $course_name; ?></h2>

        <table class="w-full">
            <thead>
                <tr>
                    <th class="py-2">Voto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result_student_grades->data_seek(0); // Riporta l'indice del risultato alla posizione iniziale
                if ($result_student_grades->num_rows > 0) {
                    while ($row = $result_student_grades->fetch_assoc()) {
                        $grade = $row['grade'];
                        $colore = ($grade >= 6) ? 'green' : (($grade >= 5) ? 'yellow' : 'red');
                        echo "<tr>";
                        echo "<td class='py-2'>";
                        echo "<div class='flex items-center justify-center'>";
                        echo "<div class='relative w-12 h-12 mr-4'>";
                        echo "<div class='absolute w-full h-full rounded-full bg-gray-800'></div>";
                        echo "<div class='absolute w-full h-full rounded-full bg-$colore' style='clip-path: circle(calc($grade * 10% - 1px))'></div>";
                        echo "<div class='absolute inset-0 flex items-center justify-center text-xs'>$grade</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td class='py-2'>Nessun voto trovato.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex justify-center items-center">
    <a href="teacher_dashboard.php" class="text-blue-500 hover:text-blue-600 font-semibold text-sm inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
            <path fill-rule="evenodd" d="M10 0a5 5 0 0 1 5 5c0 2.425-1.774 4.428-4.074 4.898A8 8 0 0 1 16 18H4a8 8 0 0 1 5.074-8.102C6.774 9.428 5 7.425 5 5a5 5 0 0 1 5-5zm0 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 2c-2.667 0-8 1.333-8 4v2h16v-2c0-2.667-5.333-4-8-4z" clip-rule="evenodd"/>
        </svg>
        Torna alla Dashboard Studente
    </a>
    </div>

    <footer class="footer mt-8 py-4 bg-gray-800 text-white">
    <div class="container mx-auto text-center">
        <p class="text-sm">&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
    </div>
    </footer>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chartData = <?php echo $chart_data; ?>;
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Andamento Voti',
                    data: chartData.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
// Chiudi la connessione al database
$conn->close();
?>
