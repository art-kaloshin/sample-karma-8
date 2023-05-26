<?php
require __DIR__ . '/config.php';

function getDbConnection(): bool | mysqli
{
    return mysqli_connect(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME
    );
}