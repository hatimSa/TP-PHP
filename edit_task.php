<?php
session_start();

if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: login.php');
    exit;
}

include('db.php');

$task = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $taskId = $_POST['id'];

    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);

    if ($stmt->rowCount() > 0) {
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: tasks_list.php");
        exit;
    }

    if (!$task) {
        header("Location: tasks_list.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateTask'])) {
    $taskId = $_POST['taskId'];
    $newTitle = $_POST['newTaskTitle'];
    $newDescription = $_POST['newTaskDescription'];

    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([ $taskId, $newTitle, $newDescription]);

    header("Location: edit_task.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modifier la tâche</title>
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
        }

        .task-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
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
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            color: #4caf50;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #333;
            border: none;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #4caf50;
        }
    </style>
</head>

<body>
    <div class="header">
        <p>Bienvenue, Hatim !</p>
    </div>
    <div class="task-container">
        <form method="post" action="">
            <input type="hidden" name="updateTask" value="update"> <!-- Ajout de cet élément -->
            <input type="hidden" name="taskId" value="<?php echo isset($taskId) ? $taskId : ''; ?>">
            <label for="newTaskTitle">Nouveau titre de la tâche:</label>
            <input type="text" id="newTaskTitle" name="newTaskTitle" value="<?php echo isset($task['title']) ? $task['title'] : ''; ?>" required><br>

            <label for="newTaskDescription">Nouvelle description de la tâche:</label>
            <textarea id="newTaskDescription" name="newTaskDescription"><?php echo isset($task['description']) ? $task['description'] : ''; ?></textarea>

            <button type="submit" name="updateTask">Mettre à jour la tâche</button>
        </form>


    </div>
    <a href="logout.php">Déconnexion</a>
</body>

</html>