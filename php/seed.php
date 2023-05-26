<?php
require './includes/database.php';
require './includes/stringFunctions.php';
$db = getDbConnection();

const USER_AMOUNT = 100;

for ($x = 0; $x < USER_AMOUNT; $x++) {
    $sql = 'insert into users set
              `username` = "' . generateUserName() . '",
              `email` = "' . generateUserEmail() . '",
              `validts` = ' . ($x < 0.8 * USER_AMOUNT ? 0 : time() + 3 * 24 * 60 * 60) . ',
              `confirmed` = ' . rand(0, 1) . '
            ';
    $db->query($sql);
}