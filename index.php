<?php

session_start();


//$start = microtime(true); // Test de vitesse

require 'validation.php';

define('MAX_TRIALS', 8);
define('PAGE_TITLE', 'Le pendu');
define('REPLACEMENT_CHAR', '*');


$gameState = 'start';
$serializedLetters = '';
$data = [];


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $words = file('datas/words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // FILE_USE_INCLUDE_PATH pas obligé si fichier est dans root
    $wordsCount = count($words);
    $wordIndex = rand(0, $wordsCount - 1);
    $_SESSION['word'] = strtolower($words[$wordIndex]);
    $lettersCount = strlen($_SESSION['word']);
    $trials = 0;
    $triedLetters = [];
    $_SESSION['letters'] = [
        'a' => true,
        'b' => true,
        'c' => true,
        'd' => true,
        'e' => true,
        'f' => true,
        'g' => true,
        'h' => true,
        'i' => true,
        'j' => true,
        'k' => true,
        'l' => true,
        'm' => true,
        'n' => true,
        'o' => true,
        'p' => true,
        'q' => true,
        'r' => true,
        's' => true,
        't' => true,
        'u' => true,
        'v' => true,
        'w' => true,
        'x' => true,
        'y' => true,
        'z' => true,
    ];
    $replacementString = str_pad('', $lettersCount, REPLACEMENT_CHAR);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieving datas from the request (To validate)
    $lettersCount = strlen($_SESSION['word']);
    $triedLetter = $_POST['triedLetter'];
    $replacementString = str_pad('', $lettersCount, REPLACEMENT_CHAR);
    // Setting new values
    $data = validated($triedLetter);
    if (!isset($data['error'])) {
        $_SESSION['letters'][$triedLetter] = false;
        // Checking if the letter is in the word
        $letterFound = false;
        $triedLetters = array_filter($_SESSION['letters'], fn($b) => !$b);
        $trials = count(array_filter(array_keys($triedLetters), fn($l) => !str_contains($_SESSION['word'], $l)
        ));
        $triedLettersStr = implode(',', array_keys($triedLetters));
        for ($i = 0; $i < $lettersCount; $i++) {
            $replacementString[$i] = array_key_exists($_SESSION['word'][$i], $triedLetters) ? $_SESSION['word'][$i] : REPLACEMENT_CHAR;
            if ($triedLetter === substr($_SESSION['word'], $i, 1)) {
                $letterFound = true;
            }
        }
        if (!$letterFound) {
            if (MAX_TRIALS <= $trials) {
                $gameState = 'lost';
            }
        } else {
            if ($_SESSION['word'] === $replacementString) {
                $gameState = 'win';
            }
        }
    }

} else {
    header('HTTP/1.1 405 Not Allowed');
    exit('Vous n’avez pas le droit d’exécuter cette requête');
}

echo $_SESSION['word'];

include './views/start.php';

//$end = microtime(true); // Test de vitesse
//
//$renderTime = $end - $start; // Test de vitesse
//
//printf('Rendu de la page en %.6f millisecondes', $renderTime * 1000); // Test de vitesse





