<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle09 extends AbstractPuzzle
{
    private array $disk = [];
    private const string EMPTY = '.';

    public function runPart01(): int
    {
        $this->loadData01();

        $sorted = [];

        while (true) {
            $block = array_shift($this->disk);

            if (is_null($block)) {
                break;
            }

            if ($block === self::EMPTY) {
                while (true) {
                    $newBlock = array_pop($this->disk);

                    if ($newBlock !== self::EMPTY) {
                        $sorted[] = $newBlock;
                        break;
                    }
                }
            } else {
                $sorted[] = $block;
            }
        }

        $total = 0;

        foreach ($sorted as $pos => $value) {
            $total += $pos * $value;
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData02();

        $sorted = [];

        while (true) {
            $block = array_shift($this->disk);

            if (is_null($block)) {
                break;
            }

            [$size, $id] = $block;

            if ($id === self::EMPTY) {
                $copy = $this->disk;
                while (true) {
                    $newBlock = array_pop($copy);

                    if (is_null($newBlock)) {
                        $sorted[] = [$size, $id];
                        break;
                    }

                    [$newSize, $newId] = $newBlock;

                    if ($newId !== self::EMPTY && $newSize <= $size) {
                        $found = array_search($newBlock, $this->disk);
                        $this->disk[$found] = [$newSize, self::EMPTY];
                        $sorted[] = [$newSize, $newId];
                        if ($size - $newSize > 0) {
                            array_unshift($this->disk, [$size - $newSize, $id]);
                        }
                        break;
                    }
                }
            } else {
                $sorted[] = [$size, $id];
            }
        }

        $total = 0;
        $i = 0;

        foreach ($sorted as $block) {
            if ($block[1] === self::EMPTY) {
                $i += $block[0];
                continue;
            } else {
                for ($j = $i; $j < $i + $block[0]; $j++) {
                    $total += $j * $block[1];
                }
            }
            $i += $block[0];
        }

        return $total;
    }

    private function loadData01(): void
    {
        $id = 0;
        $isFile = true;

        foreach (str_split($this->data[0]) as $input) {
            if ((int)$input !== 0) {
                if ($isFile) {
                    $this->disk = array_merge($this->disk, array_fill(0, $input, $id));
                    $id++;
                } else {
                    $this->disk = array_merge($this->disk, array_fill(0, $input, self::EMPTY));
                }
            }

            $isFile = !$isFile;
        }
    }

    private function loadData02(): void
    {
        $id = 0;
        $isFile = true;

        foreach (str_split($this->data[0]) as $input) {
            if ((int)$input !== 0) {
                if ($isFile) {
                    $this->disk[] = [$input, $id];
                    $id++;
                } else {
                    $this->disk[] = [$input, self::EMPTY];
                }
            }

            $isFile = !$isFile;
        }
    }
}
