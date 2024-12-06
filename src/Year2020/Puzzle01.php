<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    private const int SEARCH_VALUE = 2020;

    public function runPart01(): mixed
    {
        foreach ($this->data as $number) {
            $needle = self::SEARCH_VALUE - $number;

            if (in_array($needle, $this->data)) {
                return $needle * $number;
            }
        }

        return 0;
    }

    public function runPart02(): mixed
    {
        $data = array_flip($this->data);

        foreach ($data as $number => $value) {
            unset($data[$number]);

            $needle = self::SEARCH_VALUE - $number;

            foreach ($data as $number2 => $value2) {
                $needle2 = $needle - $number2;

                if (isset($data[$needle2])) {
                    return $number * $number2 * $needle2;
                }
            }

        }

        return 0;
    }
}
