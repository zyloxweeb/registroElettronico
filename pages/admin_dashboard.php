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

// Verifica se l'utente è un amministratore, altrimenti reindirizza alla dashboard appropriata
if ($user['role'] !== 'administration') {
    header("Location: dashboard.php");
    exit();
}

// Verifica se è stato inviato il modulo per l'aggiunta di uno studente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $student_name = $_POST['student_name'];
    $student_class = $_POST['student_class'];
    $password = $_POST['password']; // Password scelta dall'utente

    // Hash della password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserisci lo studente nella tabella users
    $sql_insert_user = "INSERT INTO users (username, password, role) VALUES ('$student_name', '$hashed_password', 'student')";
    if ($conn->query($sql_insert_user) === TRUE) {
        echo "Studente aggiunto con successo.";
    } else {
        echo "Errore durante l'aggiunta dello studente: " . $conn->error;
    }

    // Ottieni l'ID dello studente appena inserito
    $student_id = $conn->insert_id;

    // Inserisci lo studente nella tabella students
    $sql_insert_student = "INSERT INTO students (id, name, class) VALUES ('$student_id', '$student_name', '$student_class')";
    if ($conn->query($sql_insert_student) === TRUE) {
        echo "Studente inserito nella tabella students con successo.";
    } else {
        echo "Errore durante l'inserimento dello studente nella tabella students: " . $conn->error;
    }
}

// Verifica se è stato inviato il modulo per l'aggiunta di un insegnante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $teacher_name = $_POST['teacher_name'];
    $teacher_subject = $_POST['teacher_subject'];
    $password = $_POST['password']; // Password scelta dall'utente

    // Hash della password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserisci l'insegnante nella tabella users
    $sql_insert_user = "INSERT INTO users (username, password, role) VALUES ('$teacher_name', '$hashed_password', 'teacher')";
    if ($conn->query($sql_insert_user) === TRUE) {
        echo "Insegnante aggiunto con successo.";
    } else {
        echo "Errore durante l'aggiunta dell'insegnante: " . $conn->error;
    }

    // Ottieni l'ID dell'insegnante appena inserito
    $teacher_id = $conn->insert_id;

    // Inserisci l'insegnante nella tabella teachers
    $sql_insert_teacher = "INSERT INTO teachers (id, name, subject) VALUES ('$teacher_id', '$teacher_name', '$teacher_subject')";
    if ($conn->query($sql_insert_teacher) === TRUE) {
        echo "Insegnante inserito nella tabella teachers con successo.";
    } else {
        echo "Errore durante l'inserimento dell'insegnante nella tabella teachers: " . $conn->error;
    }
}

// Verifica se è stato inviato il modulo per assegnare una classe a un insegnante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_class_to_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    $class_id = $_POST['class_id'];

    // Esegui l'assegnazione della classe all'insegnante
    $sql_assign_class = "INSERT INTO teacher_classes (teacher_id, class_id) VALUES ('$teacher_id', '$class_id')";
    if ($conn->query($sql_assign_class) === TRUE) {
        echo "Classe assegnata con successo all'insegnante.";
    } else {
        echo "Errore durante l'assegnazione della classe all'insegnante: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Amministrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../icon/icon.ico">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-8">
        <div class="mb-8">
        <h2 class="text-3xl font-bold mb-2">Benvenuto, <?php echo $user['username']; ?>!</h2>
        <p class="text-lg">Ti trovi nell'area di amministrazione del Registro Elettronico.</p>
        <p class="text-sm text-gray-400">Qui puoi gestire gli studenti, gli insegnanti e le classi.</p>
    </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-800 p-6 rounded-lg">
                <h3 class="text-xl mb-4">Aggiungi Studente</h3>
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="student_name" class="block text-sm font-bold mb-2">Nome studente:</label>
                        <input type="text" id="student_name" name="student_name" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="student_class" class="block text-sm font-bold mb-2">Classe:</label>
                        <input type="text" id="student_class" name="student_class" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-bold mb-2">Password:</label>
                        <input type="password" id="password" name="password" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <button type="submit" name="add_student" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Aggiungi Studente</button>
                </form>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg">
                <h3 class="text-xl mb-4">Aggiungi Insegnante</h3>
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="teacher_name" class="block text-sm font-bold mb-2">Nome insegnante:</label>
                        <input type="text" id="teacher_name" name="teacher_name" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="teacher_subject" class="block text-sm font-bold mb-2">Materia:</label>
                        <input type="text" id="teacher_subject" name="teacher_subject" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-bold mb-2">Password:</label>
                        <input type="password" id="password" name="password" required class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <button type="submit" name="add_teacher" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Aggiungi Insegnante</button>
                </form>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg">
                <h3 class="text-xl mb-4">Assegna Classe a Insegnante</h3>
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="teacher_id" class="block text-sm font-bold mb-2">Seleziona Insegnante:</label>
                        <select name="teacher_id" id="teacher_id" class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                            <?php
                            // Query per ottenere l'elenco degli insegnanti
                            $sql_teachers_list = "SELECT * FROM teachers";
                            $result_teachers_list = $conn->query($sql_teachers_list);
                            
                            // Genera le opzioni per selezionare un insegnante
                            if ($result_teachers_list->num_rows > 0) {
                                while ($row = $result_teachers_list->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="class_id" class="block text-sm font-bold mb-2">Seleziona Classe:</label>
                        <select name="class_id" id="class_id" class="w-full bg-gray-700 text-white rounded border border-gray-700 py-2 px-3 focus:outline-none focus:border-blue-500">
                            <?php
                            // Query per ottenere l'elenco delle classi
                            $sql_classes_list = "SELECT * FROM classes";
                            $result_classes_list = $conn->query($sql_classes_list);
                            
                            // Genera le opzioni per selezionare una classe
                            if ($result_classes_list->num_rows > 0) {
                                while ($row = $result_classes_list->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="assign_class_to_teacher" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Assegna Classe</button>
                </form>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg">
                <h3 class="text-xl mb-4">Elenco Studenti e Insegnanti</h3>
                <div>
                    <h4 class="text-lg mb-2">Elenco Studenti</h4>
                    <a href="students_list.php" target="_blank" class="text-blue-500 hover:text-blue-600">Visualizza Elenco Studenti</a>
                </div>
                <div class="mt-4">
                    <h4 class="text-lg mb-2">Elenco Insegnanti</h4>
                    <a href="teachers_list.php" target="_blank" class="text-blue-500 hover:text-blue-600">Visualizza Elenco Insegnanti</a>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <p><a href="logout.php" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Logout</a></p>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-8">
            <p>&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </footer>
    </div>
</body>
</html>



