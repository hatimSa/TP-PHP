<?php
session_start();

if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: login.php');
    exit;
}

include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
    $title = $_POST['taskTitle'];
    $description = $_POST['taskDescription'];

    if (!empty($title)) {

        $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
    }
}


$stmt = $pdo->query("SELECT * FROM tasks");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
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
            display: block;
            margin-bottom: 8px;
        }

        p {
            color: #333;
            margin-bottom: 16px;
        }

        form button {
            margin-right: 10px;
        }

        a {
            color: #4caf50;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            position: fixed;
            bottom: 10px;
            right: 10px;
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
        <p>Bienvenue,Hatim!</p>
    </div>

    <div class="task-container">

        <ul>
            <?php foreach ($tasks as $task) : ?>
                <li>
                    <strong><?php echo $task['title']; ?></strong>
                    <p><?php echo $task['description']; ?></p>

                    <form method="post" action="edit_task.php">
                        <input type="hidden" name="taskId" value="<?php echo $task['id']; ?>">
                        <button type="submit" name="action" value="updateTask">Modifier</button>
                        <input type="hidden" name="updateTask" value="update">
                    </form>

                    <form method="post" action="delete_task.php">
                        <input type="hidden" name="taskId" value="<?php echo $task['id']; ?>">
                        <button type="submit" name="action" value="deleteTask">Supprimer</button>
                    </form>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="logout.php">Déconnexion</a>

</body>

</html>