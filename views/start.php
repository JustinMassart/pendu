<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= PAGE_TITLE ?></title>
</head>
<?php if ($gameState === 'start'): ?>
<body>
<div class="first_div">
    <h1>Trouve le mot en moins de <?= MAX_TRIALS ?> essais !</h1>
</div>
<div class="second_div">
    <p>Le mot à deviner compte <?= $lettersCount ?> lettres&nbsp;:</p>
    <p><?= $replacementString ?></p>
</div>
<div class="image">
    <img src="images/pendu<?= $trials ?>.gif"
         alt="pendu niveau 0">
</div>
<div class="tried">
    <?php if ($triedLetters):
        ?>
        <p>Les lettres que tu as essayées sont <?= $triedLettersStr ?></p>
    <?php else: ?>
        <p>Tu n’as encore essayé aucune lettre</p>
    <?php endif; ?>
</div>
<form action="index.php"
      method="post">
    <fieldset>
        <legend>Il te reste <?= MAX_TRIALS - $trials ?> essais pour sauver ta peau</legend>
        <div class="select">
            <label for="triedLetter">Choisis ta lettre</label>
            <select name="triedLetter"
                    id="triedLetter">
                <?php foreach ($_SESSION['letters'] as $letter => $available): ?>
                    <?php if ($available): ?>
                        <option value="<?= $letter ?>"><?= $letter ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <input type="submit"
                   class="pointer"
                   value="essayer cette lettre">
        </div>
    </fieldset>
</form>
<?php if (isset($data['error'])): ?>
    <p class="error_msg">La valeur entrée n'est pas une lettre de l'alphabet</p>
<?php endif; ?>
</body>
</html>
<?php endif; ?>
<?php
if ($gameState === 'lost') {
    $triedLetterAsArray = str_split($triedLettersStr);
    sort($triedLetterAsArray);
    include 'views/lost.php';
}
?>
<?php
if ($gameState === 'win') {
    $triedLetterAsArray = str_split($triedLettersStr);
    sort($triedLetterAsArray);
    include 'views/won.php';
} ?>