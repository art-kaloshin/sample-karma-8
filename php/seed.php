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

require './includes/database.php';
require './includes/stringFunctions.php';
$db = getDbConnection();

const USER_AMOUNT = 100;

for ($x = 0; $x < USER_AMOUNT; $x++) {
    $sql = 'insert into users set
              `username` = "' . generateUserName() . '",
              `email` = "' . generateUserEmail() . '",
              `validts` = ' . ($x < 0.8 * USER_AMOUNT ? 0 : time() + 3 * 24 * 60 * 60) . ',
              `confirmed` = ' . rand(0, 1) . '
            ';
    $db->query($sql);
}