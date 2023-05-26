<?php

function getFirstNotifyPeriodStartTs(int $now): int
{
    return 3 * 24 * 60 * 60 + $now;
}

function getFirstNotifyPeriodEndTs(int $now): int
{
    return 4 * 24 * 60 * 60 + $now;
}

function getSecontNotifyPeriodStartTs(int $now): int
{
    return 24 * 60 * 60 + $now;
}

function getSecondNotifyPeriodEndTs(int $now): int
{
    return 2 * 24 * 60 * 60 + $now;
}
