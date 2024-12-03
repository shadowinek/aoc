<?php

namespace Shadowinek\AdventOfCode\Year2015;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle04 extends AbstractPuzzle
{
    public function runPart01(): int
    {
        for ($i=1; $i<10000000; $i++) {
            $hash = md5($this->data[0] . $i);

            if (substr($hash, 0, 5) === '00000') {
                return $i;
            }
        }
    }

    public function runPart02(): int
    {
        for ($i=1000000; $i<100000000000; $i++) {
            $hash = md5($this->data[0] . $i);

            if (substr($hash, 0, 6) === '000000') {
                return $i;
            }
        }
    }
}
