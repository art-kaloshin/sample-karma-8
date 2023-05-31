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

const MAX_CHECK_DELAY = 10;
const MAX_SEND_DELAY = 10;
const FROM_ADDR = 'info@google.com';

function check_email(string $email): int
{
    echo 'Checking email: ' . $email . PHP_EOL;

    sleep(rand(1, MAX_CHECK_DELAY));
    return rand(0, 1);
}

function send_email(string $from, string $to, string $text ): void
{
    echo 'Send to: ' . $to . ' From: ' . $from . ' // Text: ' . $text;
    sleep(rand(1, MAX_SEND_DELAY));
}

function getEmailText(string $username): string
{
    return sprintf('%s, your subscription is expiring soon".', $username);
}

function getValidEmailConditionsForProcess(): string
{
    return '`confirmed` = 1 or `checked` = 0 or (`checked` = 1 and `valid` = 1)';
}