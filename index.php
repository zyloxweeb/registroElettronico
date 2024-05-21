<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="icon/icon.ico">
    <style>
        .form-container {
            max-width: 400px;
        }
    </style>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
    <div class="form-container bg-gray-800 p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-2xl font-bold mb-4">Benvenuto nel Registro Elettronico</h2>
        <p class="mb-4">Questo è un sito per la gestione di tutti gli utenti di una scuola</p>
        <p class="mb-4">Per iniziare, effettua l'accesso:</p>

        <form action="pages/dashboard.php" method="post" class="space-y-4">
            <div class="flex items-center justify-center">
                <input required id="link-checkbox" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="link-checkbox" class="ml-2 text-sm font-medium text-gray-300">I agree with the <a href="term.php" class="text-blue-600 dark:text-blue-500 hover:underline">terms and conditions</a>.</label>
            </div>

            <button id="submit-button" type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" disabled>Accedi</button>
        </form>
    </div>


    <script>
        document.getElementById('link-checkbox').addEventListener('change', function() {
            document.getElementById('submit-button').disabled = !this.checked;
        });
    </script>
</body>
</html>
