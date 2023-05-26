<?php

require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/jobFunctions.php";
require_once __DIR__ . "/includes/mailFunctions.php";
require_once __DIR__ . "/includes/timeFunctions.php";

$db = getDbConnection();

$jobTime = time();

// Выбираем пользователей для первого периода уведомления. В нашем случае 1 день
$sql = 'select * from `users` where
          `validts` > ' . getFirstNotifyPeriodStartTs($jobTime) . '
          and `validts` <= ' . getFirstNotifyPeriodEndTs($jobTime) . '
          and (' . getValidEmailConditionsForProcess() . ')
       ';

$userResult = $db->query($sql);
echo 'Users in first notify period ' . $userResult->num_rows . PHP_EOL;

while ($user = mysqli_fetch_array($userResult)) {
    // Обрабатываем пользователей на возможность отправки и создаём задачи для отправки
    processUser($db, $user, 1);
}

// Выбираем пользователей для первого периода уведомления. В нашем случае 3 дня
$sql = 'select * from users where
          `validts` > ' . getSecontNotifyPeriodStartTs($jobTime) . '
          and `validts` <= ' . getSecondNotifyPeriodEndTs($jobTime) . '
          and (' . getValidEmailConditionsForProcess() . ')
       ';

$userResult = $db->query($sql);
echo 'Users in second notify period ' . $userResult->num_rows . PHP_EOL;

while ($user = mysqli_fetch_array($userResult)) {
    // Обрабатываем пользователей на возможность отправки и создаём задачи для отправки
    processUser($db, $user, 3);
}