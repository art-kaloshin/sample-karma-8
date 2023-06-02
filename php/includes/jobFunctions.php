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

require_once __DIR__ . "/userFunctions.php";

function markJobAsDone(mysqli $mysqli, int $jobId): void
{
    $sql = 'update `job` set `is_done` = 1 where `id` = ' . $jobId;
    $mysqli->query($sql);
}

function markTaskAsDone(mysqli $mysqli, int $taskId): void
{
    $sql = 'update `send_tasks` set `is_done` = 1 where `id` = ' . $taskId;
    $mysqli->query($sql);
}

function createSenderJob(mysqli $mysqli, array $jobList): void
{
    $sql = 'insert into `job` set `job_json` = "' . $mysqli->escape_string(json_encode($jobList)) . '", total = ' . count($jobList);
    $mysqli->query($sql);

    $jobId = $mysqli->insert_id;

    // Запускаем фоновый процесс для выполнения рассылки почты
    // Предполагаем, что сообщения шлются один за другим.
    echo 'Execute job# ' . $jobId . PHP_EOL;
    passthru('(php -f ' . __DIR__ . '/../executeJob.php ' . $jobId . ' &) >> /dev/null 2>&1');
}

function createMailTask(mysqli $mysqli, array $user, int $days): void
{
    $sql = 'insert into `send_tasks` set
             `user_id` = ' . $user['id'] . ',
             `is_done` = 0,
             `days_left` = ' . $days . ',
             `created_at` = ' . time() . '
          ';
    $mysqli->query($sql);
}

function processUser(mysqli $db, array $user, int $days): void
{
    if ((int)$user['checked'] === 0) {
        $checkResult = check_email($user['email']);

        setUserEmailChecked($db, $user['id'], $checkResult);
        $user['checked'] = 1;
        $user['valid'] = $checkResult;
    }

    if (((int)$user['checked'] === 1 && (int)$user['valid'] === 1) || (int)$user['confirmed'] === 1) {
        createMailTask($db, $user, $days);
    }
}