<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle24 extends AbstractPuzzle
{
    private array $wires = [];
    private array $connections = [];

    public function runPart01(): int
    {
        $this->loadData();

        while (!empty($this->connections)) {
            $connection = array_shift($this->connections);

            [$a, $b, $op, $result] = $connection;

            if (isset($this->wires[$a]) && isset($this->wires[$b])) {
                switch ($op) {
                    case 'AND':
                        $this->wires[$result] = (int)$this->wires[$a] & $this->wires[$b];
                        break;
                    case 'OR':
                        $this->wires[$result] = (int)$this->wires[$a] | $this->wires[$b];
                        break;
                    case 'XOR':
                        $this->wires[$result] = (int)$this->wires[$a] ^ $this->wires[$b];
                        break;
                    default:
                        break;
                }
            } else {
                $this->connections[] = $connection;
            }
        }

        krsort($this->wires);

        $result = '';

        foreach ($this->wires as $wire => $value) {
            if (str_starts_with($wire, 'z')) {
                $result .= $value;
            }
        }

        return bindec($result);
    }

    public function runPart02(): string
    {
        $this->loadData();

        return 0;
    }

    private function loadData(): void
    {
        $loadConnections = false;

        foreach ($this->data as $line) {
            if ($line === '') {
                $loadConnections = true;
                continue;
            }

            if ($loadConnections) {
                [$connection, $result] = explode(' -> ', $line);
                [$a, $op, $b] = explode(' ', $connection);

                $this->connections[] = [
                    $a,
                    $b,
                    $op,
                    $result,
                ];
            } else {
                [$wire, $value] = explode(': ', $line);
                $this->wires[$wire] = $value;
            }
        }

//        print_r($this->wires);
//        print_r($this->connections);
    }
}
