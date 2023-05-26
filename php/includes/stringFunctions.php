<?php

function generateRandomString($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateUserName(): string
{
    return ucfirst(generateRandomString(6)) . ' ' . ucfirst(generateRandomString(10));
}

function generateUserEmail(): string
{
    return generateRandomString(8) . '@' . generateRandomString(10) . '.com';
}