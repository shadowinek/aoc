<?php

ini_set("memory_limit", "-1");
set_time_limit(0);

require_once 'vendor/autoload.php';

use Shadowinek\AdventOfCode\AoC;

if ($argc > 4) {
    $aoc = new AoC();
    $aoc->execute((int) $argv[1], (int) $argv[2], (int) $argv[3], (bool) $argv[4], $argv[5] ?? false);
} else {
    echo 'Too few arguments!' . PHP_EOL;
}
