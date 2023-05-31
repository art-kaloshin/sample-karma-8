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

const MAX_MESSAGE_PER_SENDER = 6000;

require_once __DIR__ . "/includes/database.php";
require_once __DIR__ . "/includes/jobFunctions.php";

$db = getDbConnection();
$sql = 'select `send_tasks`.`id` as `task_id`, `users`.* from `send_tasks` left join `users` on `send_tasks`.`user_id` = `users`.`id` 
                                                 where `send_tasks`.`is_done` = 0';
$activeTaskResult = $db->query($sql);

$jobArray = [];

while ($task = $activeTaskResult->fetch_array()) {
    $jobArray[] = [
        'user_id' => $task['id'],
        'user_name' => $task['username'],
        'user_email' => $task['email']
    ];

    markTaskAsDone($db, $task['task_id']);

    if (count($jobArray) >= MAX_MESSAGE_PER_SENDER) {
        createSenderJob($db, $jobArray);

        $jobArray = [];
    }
}

createSenderJob($db, $jobArray);