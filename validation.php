<?php

function validated($triedLetter): array|bool
{
    if (!ctype_alpha($triedLetter)) {
        return ['error' => 'La valeur rentrée n’est pas une lettre de l’alphabet'];
    }
    return true;
}