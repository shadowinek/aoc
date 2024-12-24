<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle23 extends AbstractPuzzle
{
    private array $connections = [];
    private array $graph = [];
    private array $clique = [];

    public function runPart01(): int
    {
        $this->loadData();

        $found = [];

        foreach ($this->connections as $a => $bs) {
            if (str_starts_with($a, 't')) {
                foreach ($bs as $b => $v) {
                    foreach ($this->connections[$b] as $c => $v) {
                        if ($c !== $a && isset($this->connections[$c][$a])) {
                            $array = [$a, $b, $c];
                            sort($array);
                            $found[implode(',', $array)] = true;
                        }
                    }
                }
            }
        }

        return count($found);
    }

    /**
     * @see https://en.wikipedia.org/wiki/Bron%E2%80%93Kerbosch_algorithm#With_pivoting
     */
    public function runPart02(): string
    {
        $this->loadData();

        foreach ($this->connections as $a => $bs) {
            foreach ($bs as $b => $v) {
                $this->graph[$a][] = $b;
            }
        }

        $this->bronKerbosch([], array_keys($this->graph), []);

        $max = 0;
        $foundId = null;

        foreach ($this->clique as $id => $clique) {
            $count = count($clique);

            if ($count > $max) {
                $max = $count;
                $foundId = $id;
            }
        }

        $found = $this->clique[$foundId];
        sort($found);

        return implode(',', $found);
    }

    /**
     * algorithm BronKerbosch2(R, P, X) is
     * if P and X are both empty then
     * report R as a maximal clique
     * choose a pivot vertex u in P ⋃ X
     * for each vertex v in P \ N(u) do
     * BronKerbosch2(R ⋃ {v}, P ⋂ N(v), X ⋂ N(v))
     * P := P \ {v}
     * X := X ⋃ {v}
     */
    private function bronKerbosch(array $r, array $p, array $x): void
    {
        if (empty($p) && empty($x)) {
            $this->clique[] = $r;
        }

        $pivot = array_merge($p, $x);
        array_shift($pivot);

        foreach ($p as $v) {
            $this->bronKerbosch(
                array_merge($r, [$v]),
                array_intersect($p, $this->graph[$v]),
                array_intersect($x, $this->graph[$v])
            );

            $p = array_diff($p, [$v]);
            $x = array_merge($x, [$v]);
        }
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            [$a, $b] = explode('-', $line);
            $this->connections[$a][$b] = true;
            $this->connections[$b][$a] = true;
        }
    }
}
