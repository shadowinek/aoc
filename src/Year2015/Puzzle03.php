<?php

namespace Shadowinek\AdventOfCode\Year2015;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $directions = [];
    private array $houses = [];
    public function runPart01(): int
    {
        $this->loadData();

        $x = $y = 0;

        $this->houses[$x . '-' . $y] = true;

        foreach ($this->directions as $direction) {
            switch ($direction) {
                case '^':
                    $y++;
                    break;
                case 'v':
                    $y--;
                    break;
                case '>':
                    $x++;
                    break;
                case '<':
                    $x--;
                    break;
            }

            $this->houses[$x . '-' . $y] = true;
        }

        return count($this->houses);
    }

    public function runPart02(): int
    {
        $this->loadData();

        $x = $y = $rx = $ry = 0;

        $santa = true;

        $this->houses[$x . '-' . $y] = true;

        foreach ($this->directions as $direction) {
            switch ($direction) {
                case '^':
                    if ($santa) {
                        $y++;
                    } else {
                        $ry++;
                    }
                    break;
                case 'v':
                    if ($santa) {
                        $y--;
                    } else {
                        $ry--;
                    }
                    break;
                case '>':
                    if ($santa) {
                        $x++;
                    } else {
                        $rx++;
                    }
                    break;
                case '<':
                    if ($santa) {
                        $x--;
                    } else {
                        $rx--;
                    }
                    break;
            }

            if ($santa) {
                $this->houses[$x . '-' . $y] = true;
            } else {
                $this->houses[$rx . '-' . $ry] = true;
            }

            $santa = !$santa;
        }

        return count($this->houses);
    }

    private function loadData(): void
    {
        $this->directions = str_split($this->data[0]);
    }
}
