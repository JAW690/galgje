<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $word = $_POST['word'];

    if (!empty($word)) {
        $_SESSION['word'] = strtoupper($word);
    }
    header("Location: galgje.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>galgje word input</title>
</head>
<body style="background: deepskyblue">
    <div style="margin: 0 auto; background: #dddddd; width: 400px; height: 200px; padding: 20px; border-radius: 3px;">
        <h1>voer word in voor galgje</h1>
        <form method="POST">
            <input type="text" name="word" placeholder="Enter a word">
            <br><br>
            <button type="submit">start spel met eigen word</button>
            <br>
        <form action="galgje.php" method="post">
            <input type="hidden" name="use_default" value="1">
            <input type="submit" value="Start Game met random word">
        
        </form>
    </div>
</body>
</html>
