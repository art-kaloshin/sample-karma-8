<?php
    require_once __DIR__ . "/includes/database.php";
    require_once __DIR__ . "/includes/jobFunctions.php";
    require_once __DIR__ . "/includes/mailFunctions.php";

    $db = getDbConnection();
    $sql = 'select * from `job` where `id` = ' . (int)$argv[1] . ' and `is_done` = 0';
    $jobResult = $db->query($sql);

    if ($job = $jobResult->fetch_array()) {
        $addressList = json_decode($job['job_json'], 1);

        foreach ($addressList as $address) {
            send_email(FROM_ADDR, $address['user_email'], getEmailText($address['user_name']));

            $sql = 'update `job` set `done` = `done` + 1 where  `id` = ' . $job['id'];
            $db->query($sql);
        }

        markJobAsDone($db, $job['id']);
    }