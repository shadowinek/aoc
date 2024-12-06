<?php

namespace Shadowinek\AdventOfCode\Year2018;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle04 extends AbstractPuzzle
{
    private array $timeline = [];

    private array $sleeping = [];
    private array $guards = [];
    private array $minutes = [];

    private const string AWAKE = 'a';
    private const string ASLEEP = 's';

    public function runPart01(): mixed
    {
        $this->loadData();
        $this->processData();

        $selectedGuard = array_search(max($this->guards), $this->guards);
        $selectedMinute = array_search(max($this->minutes[$selectedGuard]), $this->minutes[$selectedGuard]);

        return $selectedGuard * $selectedMinute;
    }

    private function processData(): void
    {
        $emptyArray = array_fill(0, 60, 0);

        foreach ($this->timeline as $guard => $dates) {
            $this->guards[$guard] = 0;
            $this->minutes[$guard] = $emptyArray;

            foreach ($dates as $date => $minutes) {
                $this->sleeping[$guard][$date] = $emptyArray;

                while ($minute = array_shift($minutes)) {
                    if ($minute[1] === self::ASLEEP) {
                        $next = array_shift($minutes);

                        for ($i = (int)$minute[0]; $i < (int)$next[0]; $i++) {
                            $this->sleeping[$guard][$date][$i] = 1;
                            $this->guards[$guard]++;
                            $this->minutes[$guard][$i]++;
                        }
                    }
                }
            }
        }
    }

    public function runPart02(): mixed
    {
        $this->loadData();
        $this->processData();

        $selectedGuard = 0;
        $selectedMinute = 0;
        $max = 0;

        foreach ($this->minutes as $guard => $minutes) {
            $newMax = max($minutes);

            if ($newMax > $max) {
                $max = $newMax;
                $selectedGuard = $guard;
                $selectedMinute = array_search($max, $minutes);
            }
        }

        return $selectedGuard * $selectedMinute;
    }

    private function loadData(): void
    {
        $data = $this->data;

        sort($data);

        foreach ($data as $line) {
            $matches = [];
            preg_match('/\[(\d{4}-(\d{2}-\d{2}) (\d{2}):(\d{2}))\] (.+)/', $line, $matches);

            $date = $matches[2];
            $minute = $matches[4];
            $action = $matches[5];

            switch ($action) {
                case 'falls asleep':
                    $this->timeline[$guard][$date][] = [
                        $minute,
                        self::ASLEEP
                    ];
                    break;
                case 'wakes up':
                    $this->timeline[$guard][$date][] = [
                        $minute,
                        self::AWAKE
                    ];
                    break;
                default:
                    $submatches = [];
                    preg_match('/Guard #(\d+) begins shift/', $action, $submatches);
                    $guard = $submatches[1];
                    break;
            }
        }
    }
}
