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

ini_set('max_execution_time', 0); // иначе рассылка умрёт по таймауту раньше времени

    require_once __DIR__ . "/includes/database.php";
    require_once __DIR__ . "/includes/jobFunctions.php";
    require_once __DIR__ . "/includes/mailFunctions.php";

    $db = getDbConnection();
    $sql = 'select * from `job` where `id` = ' . (int)$argv[1] . ' and `is_done` = 0';
    $jobResult = $db->query($sql);

    // В целом можно дописать, что бы если выполнение отваливается, то оно продолжалось с точки где прервалось.
    // Для этого считаем количество отправленных сообщений
    if ($job = $jobResult->fetch_array()) {
        $addressList = json_decode($job['job_json'], 1);

        foreach ($addressList as $address) {
            send_email(FROM_ADDR, $address['user_email'], getEmailText($address['user_name']));

            $sql = 'update `job` set `done` = `done` + 1 where  `id` = ' . $job['id'];
            $db->query($sql);
        }

        markJobAsDone($db, $job['id']);
    }