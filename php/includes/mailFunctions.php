<?php

const MAX_CHECK_DELAY = 10;
const MAX_SEND_DELAY = 10;
const FROM_ADDR = 'info@google.com';

function check_email(string $email): int
{
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