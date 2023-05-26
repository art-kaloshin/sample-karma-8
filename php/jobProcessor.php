<?php

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