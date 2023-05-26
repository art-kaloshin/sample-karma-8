<?php

function setUserChecked(mysqli $mysqli, int $userId, int $result)
{
    $sql = 'update `users` set `checked` = 1, `valid` = ' . $result . ' where id = ' . $userId;
    $mysqli->query($sql);
}