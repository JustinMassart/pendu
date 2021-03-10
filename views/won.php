<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Le pendu</title>
</head>
<body>
<div>
    <h1 class="h_win">Tu as trouvé le mot "<i><?= $word ?></i>" avec <?= $trials ?> essais incorrects !</h1>
</div>
<div class="tried">
    <p>Voici les lettres que tu as essayées, dans l’ordre
        alphabétique&nbsp;: <?= $triedLettersStr ?></p>
</div>
<div class="again">
    <p><a href="./index.php">Recommence&nbsp;!</a></p>
</div>
</body>
</html>