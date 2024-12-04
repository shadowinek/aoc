<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle04 extends AbstractPuzzle
{
    private array $input = [];
    private array $strings = [];
    public function runPart01(): int
    {
        $this->loadData01();

        $total = 0;

        foreach ($this->strings as $string) {
            $total += substr_count($string, 'XMAS');
            $total += substr_count($string, 'SAMX');
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData02();

        $cols = count($this->input[0]);
        $rows = count($this->input);

        $total = 0;

        for ($x=0; $x<$cols; $x++) {
            for ($y=0; $y<$rows; $y++) {
                if ($this->input[$y][$x] === 'A' && $this->checkX($x, $y)) {
                    $total++;
                }
            }
        }

        return $total;
    }

    private function checkX(int $col, int $row): bool
    {
        $topLeft = $this->input[$row-1][$col-1] ?? '';
        $topRight = $this->input[$row-1][$col+1] ?? '';
        $bottomLeft = $this->input[$row+1][$col-1] ?? '';
        $bottomRight = $this->input[$row+1][$col+1] ?? '';

        $check1 = ($topLeft === 'M' && $bottomRight === 'S') || ($topLeft === 'S' && $bottomRight === 'M');
        $check2 = ($topRight === 'M' && $bottomLeft === 'S') || ($topRight === 'S' && $bottomLeft === 'M');

        return $check1 && $check2;
    }

    private function loadData01(): void
    {
        foreach ($this->data as $line) {
            $this->input[] = str_split($line);
            $this->strings[] = $line;
        }

        $cols = count($this->input[0]);
        $rows = count($this->input);

        // vertical
        for ($x=0; $x<$cols; $x++) {
            $string = '';

            for ($y=0; $y<$rows; $y++) {
                $string .= $this->input[$y][$x];
            }

            $this->strings[] = $string;
        }

        // diagonal
        for ($x=0; $x<$cols; $x++) {
            $string = '';
            $z = $x;

            for ($y=0; $y<$rows; $y++) {
                if (isset($this->input[$y][$z])) {
                    $string .= $this->input[$y][$z];
                }
                $z++;
            }

            $this->strings[] = $string;
        }

        for ($y=1; $y<$rows; $y++) {
            $string = '';
            $z = $y;

            for ($x=0; $x<$cols; $x++) {
                if(isset($this->input[$z][$x])) {
                    $string .= $this->input[$z][$x];
                }
                $z++;
            }

            $this->strings[] = $string;
        }

        // reverse diagonal
        for ($y=$rows-1; $y>=0; $y--) {
            $string = '';
            $z = $y;

            for ($x=0; $x<$cols; $x++) {
                if(isset($this->input[$z][$x])) {
                    $string .= $this->input[$z][$x];
                }
                $z--;
            }

            $this->strings[] = $string;
        }

        for ($x=1; $x<$cols; $x++) {
            $string = '';
            $z = $x;

            for ($y=$rows-1; $y>=0; $y--) {
                if (isset($this->input[$y][$z])) {
                    $string .= $this->input[$y][$z];
                }
                $z++;
            }

            $this->strings[] = $string;
        }
    }

    private function loadData02(): void
    {
        foreach ($this->data as $line) {
            $this->input[] = str_split($line);
        }
    }
}
