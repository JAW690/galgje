<?php

session_start();

$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$WON = false;

$guess = "GALGJE";
$maxLetters = strlen($guess) - 1;
$responses = ["H", "G", "A"];

$bodyParts = ["nohead", "head", "body", "hand", "hands", "leg", "legs"];

$words = [
    "HANGMAN",
    "BUTTERFLY",
    "APPLE",
    "INSIDIOUSLY",
    "DUPLICATE",
    "CASUALTY",
    "GLOOMFUL",
];

function CurrentPicture($part)
{
    return "./images/hangman_" . $part . ".png";
}

function startGame()
{
}

function restartGame()
{
    header("location: word_input.php");
    session_destroy();
    session_start();
}

function getPart()
{
    global $bodyParts;
    return isset($_SESSION["parts"]) ? $_SESSION["parts"] : $bodyParts;
}

function addParts()
{
    $parts = getPart();
    array_shift($parts);
    $_SESSION["parts"] = $parts;
}

function Hangmanpicture()
{
    $parts = getPart();
    return $parts[0];
}

function CurrentWord()
{
    global $words;
    if (!isset($_SESSION["word"]) && empty($_SESSION["word"])) {
        $key = array_rand($words);
        $_SESSION["word"] = $words[$key];
    }
    return $_SESSION["word"];
}

function CurrentResponses()
{
    return isset($_SESSION["responses"]) ? $_SESSION["responses"] : [];
}

function addResponse($letter)
{
    $responses = CurrentResponses();
    array_push($responses, $letter);
    $_SESSION["responses"] = $responses;
}

function isLetterCorrect($letter)
{
    $word = CurrentWord();
    $max = strlen($word) - 1;
    for ($i = 0; $i <= $max; $i++) {
        if ($letter == $word[$i]) {
            return true;
        }
    }
    return false;
}

function WordCorrect()
{
    $guess = CurrentWord();
    $responses = CurrentResponses();
    $max = strlen($guess) - 1;
    for ($i = 0; $i <= $max; $i++) {
        if (!in_array($guess[$i], $responses)) {
            return false;
        }
    }
    return true;
}

function BodyComplete()
{
    $parts = getPart();
    if (count($parts) <= 1) {
        return true;
    }
    return false;
}

function gameComplete()
{
    return isset($_SESSION["gamecomplete"]) ? $_SESSION["gamecomplete"] : false;
}

function markGameAsComplete()
{
    $_SESSION["gamecomplete"] = true;
}

function markGameAsNew()
{
    $_SESSION["gamecomplete"] = false;
}

if (isset($_GET["start"])) {
    restartGame();
}

if (isset($_GET["kp"])) {
    $currentPressedKey = isset($_GET["kp"]) ? $_GET["kp"] : null;

    if (
        $currentPressedKey &&
        isLetterCorrect($currentPressedKey) &&
        !BodyComplete() &&
        !gameComplete()
    ) {
        addResponse($currentPressedKey);
        if (WordCorrect()) {
            $WON = true;
            markGameAsComplete();
        }
    } else {
        if (!BodyComplete()) {
            addParts();
            if (BodyComplete()) {
                markGameAsComplete();
            }
        } else {
            markGameAsComplete();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>galgje</title>
</head>

<body style="background: deepskyblue">

    <div style="margin: 0 auto; background: #dddddd; width:900px; height:900px; padding:5px; border-radius:3px;">


        <div style="display:inline-block; width: 500px; background:#fff;">
            <img style="width:80%; display:inline-block;" src="<?php echo CurrentPicture(
                Hangmanpicture()
            ); ?>" />


            <?php if (gameComplete()) : ?>
                <h1>GALGJE COMPLETE</h1>
            <?php endif; ?>
            <?php if ($WON && gameComplete()) : ?>
                <p style="color: green; font-size: 30px;">Je hebt gewonnen! nice :)</p>
            <?php elseif (!$WON && gameComplete()) : ?>
                <p style="color: red; font-size: 30px;">Je hebt verloren! oh nee :(</p>
            <?php endif; ?>
        </div>

        <div style="float:right; display:inline; vertical-align:top;">
            <h1>galgje</h1>
            <div style="display:inline-block;">
                <form method="get">
                    <?php
                    $max = strlen($letters) - 1;
                    for ($i = 0; $i <= $max; $i++) {
                        echo "<button type='submit' name='kp' value='" .
                            $letters[$i] .
                            "'>" .
                            $letters[$i] .
                            "</button>";
                        if ($i % 7 == 0 && $i > 0) {
                            echo "<br>";
                        }
                    }
                    ?>
                    <br><br>
                    <button type="submit" name="start">Restart Game</button>
                </form>
            </div>
        </div>

        <div style="margin-top:20px; padding:15px; background: lightseagreen; color: #fcf8e3">
            <?php
            $guess = CurrentWord();
            $maxLetters = strlen($guess) - 1;
            for ($j = 0; $j <= $maxLetters; $j++) :
                $l = CurrentWord()[$j]; ?>
                <?php if (in_array($l, CurrentResponses())) : ?>
                    <span style="font-size: 30px; border-bottom: 2.5px solid #000; margin-right: 3px;"><?php echo $l; ?></span>
                <?php else : ?>
                    <span style="font-size: 30px; border-bottom: 2.5px solid #000; margin-right: 4px;">&nbsp;&nbsp;&nbsp;</span>
                <?php endif; ?>
                <?php
            endfor;
            ?>
        </div>

    </div>



</body>


</html>