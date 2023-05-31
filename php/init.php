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

require 'includes/database.php';

$db = getDbConnection();

$initSql = [
    'create table users(
            id        bigint auto_increment PRIMARY KEY,
            username  varchar(100)         null,
            email     varchar(200)         not null,
            validts   bigint     default 0 not null,
            confirmed tinyint(1) default 0 not null,
            checked   tinyint(1) default 0 not null,
            valid     tinyint(1) default 0 not null
        );',
    'create index validts on users (`validts`);',

    'create table send_tasks (
            id        bigint auto_increment PRIMARY KEY,
            user_id   bigint,
            is_done   tinyint(1) default 0 not null,
            days_left tinyint(1) default 1 not null,
            created_at bigint
        );',
    'create index user_id on send_tasks (`user_id`);',
    'create index is_done on send_tasks (`is_done`);',
    'create index created_at on send_tasks (`created_at`);',

    'create table job(
            id          bigint auto_increment PRIMARY KEY,
            is_done     tinyint(1) default 0 not null,
            total       int default 0,
            done        int default 0,
            job_json    longtext
        )',
    'create index is_done on job (`is_done`);',

];

foreach ($initSql as $sql) {
    echo $sql;
    $db->query($sql);
}