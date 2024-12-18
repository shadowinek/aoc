<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle05 extends AbstractPuzzle
{
    private array $stacks = [];
    private array $steps = [];

    public function runPart01(): string
    {
        $result = '';

        $this->loadData();

        foreach ($this->steps as $step) {
            for ($i = 0; $i < $step[0]; $i++) {
                $this->stacks[$step[2]][] = array_pop($this->stacks[$step[1]]);
            }
        }

        foreach ($this->stacks as $stack) {
            $result .= array_pop($stack);
        }

        return $result;
    }

    public function runPart02(): string
    {
        $result = '';

        $this->loadData();

        foreach ($this->steps as $step) {
            $boxes = [];

            for ($i = 0; $i < $step[0]; $i++) {
                $boxes[] = array_pop($this->stacks[$step[1]]);
            }

            $this->stacks[$step[2]] = array_merge($this->stacks[$step[2]], array_reverse($boxes));
        }

        foreach ($this->stacks as $stack) {
            $result .= array_pop($stack);
        }

        return $result;
    }

    private function loadData(): void
    {
        $steps = false;

        foreach ($this->data as $data) {
            if (empty($data)) {
                $steps = true;
                continue;
            }

            if ($steps) {
                $matches = [];
                preg_match_all('/(\d+)/', $data, $matches);
                $this->steps[] = $matches[0];
            } else {
                $input = str_split($data);

                if ($input[1] != 1) {
                    $stack = 0;
                    $space = 0;
                    while ($char = array_shift($input)) {
                        switch ($char) {
                            case ' ':
                                $space++;
                                if ($space >= 4) {
                                    $space = 0;
                                    $stack++;
                                }
                                break;
                            case '[':
                            case ']':
                                // ignore
                                break;
                            default:
                                $space = 0;
                                $stack++;
                                $this->stacks[$stack][] = $char;
                                break;
                        }
                    }
                }
            }
        }

        ksort($this->stacks);

        foreach ($this->stacks as $index => $stack) {
            $this->stacks[$index] = array_reverse($stack);
        }

//        print_r($this->stacks);
    }
}
