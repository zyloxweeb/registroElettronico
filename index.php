<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="flex flex-col justify-center items-center h-screen">
        <div class="container mx-auto p-4 text-center">
            <h2 class="text-2xl font-bold mb-4">Benvenuto nel Registro Elettronico</h2>
            <p class="mb-4">Questo è un sito per la gestione di tutti gli utenti di una scuola</p>
            <p class="mb-4">Per iniziare, effettua l'accesso:</p>
            <button onclick="location.href='pages/login.php'" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Accedi</button>
        </div>
    </div>

    <footer class="footer bg-gray-800 py-4 text-center">
        <div class="container mx-auto">
            <p class="text-sm text-gray-400">&copy; <?php echo date("Y"); ?> Registro Elettronico. Tutti i diritti riservati.</p>
        </div>
    </footer>
</body>
</html>
