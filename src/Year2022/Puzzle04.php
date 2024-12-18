<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle04 extends AbstractPuzzle
{
    public function runPart01(): int
    {
        $result = 0;

        foreach ($this->data as $data) {
            $pairs = explode(',', $data);

            $ids_1 = explode('-', $pairs[0]);
            $ids_2 = explode('-', $pairs[1]);

            if (
                ($ids_1[0] <= $ids_2[0] && $ids_1[1] >= $ids_2[1])
                || ($ids_2[0] <= $ids_1[0] && $ids_2[1] >= $ids_1[1])
            ) {
                $result++;
            }
        }

        return $result;
    }

    public function runPart02(): int
    {
        $result = 0;

        foreach ($this->data as $data) {
            $pairs = explode(',', $data);

            $ids_1 = explode('-', $pairs[0]);
            $ids_2 = explode('-', $pairs[1]);

            $array_1 = array_keys(array_fill($ids_1[0], $ids_1[1] - $ids_1[0] + 1, true));
            $array_2 = array_keys(array_fill($ids_2[0], $ids_2[1] - $ids_2[0] + 1, true));

            if (!empty(array_intersect($array_1, $array_2))) {
                $result++;
            }
        }

        return $result;
    }
}
