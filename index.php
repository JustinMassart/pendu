<?php

//$start = microtime(true); // Test de vitesse

require 'validation.php';

define('MAX_TRIALS', 8);
define('PAGE_TITLE', 'Le pendu');
define('REPLACEMENT_CHAR', '*');

$words = file('datas/words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // FILE_USE_INCLUDE_PATH pas obligé si fichier est dans root
$wordsCount = count($words);
$gameState = 'start';
$serializedLetters = '';
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $wordIndex = rand(0, $wordsCount - 1);
    $word = $words[$wordIndex];
    $lettersCount = strlen($word);
    $trials = 0;
    $triedLetters = [];
    $letters = [
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
    setcookie('wordIndex', $wordIndex);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieving datas from the request (To validate)
    $wordIndex = $_COOKIE['wordIndex'];
    $letters = json_decode($_COOKIE['letters'], true);
    $word = strtolower($words[$wordIndex]);
    $lettersCount = strlen($word);
    $triedLetter = $_POST['triedLetter'];
    $replacementString = str_pad('', $lettersCount, REPLACEMENT_CHAR);
    // Setting new values
    $data = validated($triedLetter);
    if (!isset($data['error'])) {
        $letters[$triedLetter] = false;
        // Checking if the letter is in the word
        $letterFound = false;
        $triedLetters = array_filter($letters, fn($b) => !$b);
        $trials = count(array_filter(array_keys($triedLetters), fn($l) => !str_contains($word, $l)
        ));
        $triedLettersStr = implode(',', array_keys($triedLetters));
        for ($i = 0; $i < $lettersCount; $i++) {
            $replacementString[$i] = array_key_exists($word[$i], $triedLetters) ? $word[$i] : REPLACEMENT_CHAR;
            if ($triedLetter === substr($word, $i, 1)) {
                $letterFound = true;
            }
        }
        if (!$letterFound) {
            if (MAX_TRIALS <= $trials) {
                $gameState = 'lost';
            }
        } else {
            if ($word === $replacementString) {
                $gameState = 'win';
            }
        }
    }

} else {
    header('HTTP/1.1 405 Not Allowed');
    exit('Vous n’avez pas le droit d’exécuter cette requête');
}

setcookie('letters', json_encode($letters));


echo $word;

include './views/start.php';

//$end = microtime(true); // Test de vitesse
//
//$renderTime = $end - $start; // Test de vitesse
//
//printf('Rendu de la page en %.6f millisecondes', $renderTime * 1000); // Test de vitesse





