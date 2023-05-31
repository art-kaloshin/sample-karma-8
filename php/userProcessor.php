<?php
/*
 * Copyright (c) 2023
 * Artem Kaloshin, artem-developer@v2u.su
 * All Rights Reserved
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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