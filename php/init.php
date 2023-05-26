<?php

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