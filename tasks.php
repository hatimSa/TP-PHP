<?php
session_start();

if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: login.php');
    exit;
}

include('db.php');

// Ajouter une nouvelle tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
    $title = $_POST['taskTitle'];
    $description = $_POST['taskDescription'];

    if (!empty($title)) {
        // Utilisez une requête préparée pour éviter les injections SQL
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);

        header('Location: tasks_list.php');
        exit;
    }
}

// Récupérer la liste des tâches après l'ajout
$stmt = $pdo->query("SELECT * FROM tasks");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed;
            top: 0;
        }

        .task-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            margin-top: 60px;
            /* Ajustez la marge pour laisser de l'espace sous la barre de navigation fixe */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #333;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #fff;
        }

        strong {
            font-size: 18px;
        }

        p {
            color: #333;
        }

        a {
            color: #4caf50;
            text-decoration: none;
            display: block;
            margin-top: 10px;
            position: fixed;
            bottom: 10px;
            /* Ajustez la position verticale du bouton */
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <p>Bienvenue, Hatim !</p>
    </div>

    <div class="task-container">
        <!-- Formulaire pour ajouter une nouvelle tâche -->
        <form method="post" action="">
            <label for="taskTitle">Titre de la tâche:</label>
            <input type="text" id="taskTitle" name="taskTitle" required><br><br>
            <label for="taskDescription">Description de la tâche:</label>
            <textarea id="taskDescription" name="taskDescription"></textarea>
            <button type="submit" name="addTask">Ajouter une tâche</button>
        </form>
    </div>

    <a href="logout.php">Déconnexion</a>

</body>

</html>