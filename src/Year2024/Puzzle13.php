<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle13 extends AbstractPuzzle
{
    private array $games = [];
    private const int ADD = 10000000000000;

    public function runPart01(): int
    {
        $this->loadData();
        $total = 0;

        foreach ($this->games as $game) {
            $total += $this->playGame($game);
        }

        return $total;
    }

    private function playGame(array $game): int
    {
        [$ax, $ay] = $game['A'];
        [$bx, $by] = $game['B'];
        [$px, $py] = $game['prize'];

        $a = 0;

        while (true) {
            $rx = $px - ($ax * $a);
            $ry = $py - ($ay * $a);

            if ($rx < 0 || $ry < 0) {
                break;
            }

            if ($rx % $bx === 0 && $ry % $by === 0) {
                $b = $rx / $bx;
                $b2 = $ry / $by;

                if ($b > 0 && $b <= 100 && $b === $b2) {
                    return $a * 3 + $b;
                }
            }

            $a++;
        }

        return 0;
    }

    private function playGame02(array $game): int
    {
        [$ax, $ay] = $game['A'];
        [$bx, $by] = $game['B'];
        [$px, $py] = $game['prize'];

        $px = bcadd($px, self::ADD);
        $py = bcadd($py, self::ADD);

        $a = bcdiv(bcsub(bcmul($px, $by), bcmul($py, $bx)), bcsub(bcmul($ax, $by), bcmul($ay, $bx)));
        $b = bcdiv(bcsub(bcmul($py, $ax), bcmul($px, $ay)), bcsub(bcmul($ax, $by), bcmul($ay, $bx)));

        if ((int)$a == $a && (int)$b == $b && $a > 0 && $b > 0) {
            return (int)$a * 3 + (int)$b;
        }

        return 0;
    }

    public function runPart02(): int
    {
        bcscale(2);
        $this->loadData();
        $total = 0;

        foreach ($this->games as $game) {
            $total += $this->playGame02($game);
        }

        return $total;
    }

    private function loadData(): void
    {
        $gameId = 0;

        foreach ($this->data as $line) {
            if (empty($line)) {
                $gameId++;
                continue;
            }

            if (str_contains($line, 'Button A')) {
                $this->games[$gameId]['A'] = $this->parseNumbers($line);
                continue;
            }

            if (str_contains($line, 'Button B')) {
                $this->games[$gameId]['B'] = $this->parseNumbers($line);
                continue;
            }

            if (str_contains($line, 'Prize')) {
                $this->games[$gameId]['prize'] = $this->parseNumbers($line);
            }
        }
    }
}
