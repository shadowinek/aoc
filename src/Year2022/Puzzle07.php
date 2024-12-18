<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle07 extends AbstractPuzzle
{
    private array $dirs = [];

    private Dir $root;

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->dirs as $dir) {
            $size = $dir->getSize();
            if ($size < 100000) {
                $total += $size;
            }
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $toDelete = [];
        $currentSpace = 70000000 - $this->root->getSize();
        $toFreeUp = 30000000 - $currentSpace;

        echo $toFreeUp . PHP_EOL;

        foreach ($this->dirs as $uuid => $dir) {
            $size = $dir->getSize();

            if ($size > $toFreeUp) {
                $toDelete[$uuid] = $size;
            }
        }

        return min($toDelete);

    }

    private function loadData(): void
    {
        $this->root = new Dir('/');
        $this->dirs[$this->root->uuid] = $this->root;

        foreach ($this->data as $data) {
            if (str_starts_with($data, '$ cd ')) {
                $currentDirKey = substr($data, 5);

                if ($currentDirKey === '..') {
                    $currentDir = $currentDir->parent;
                } elseif ($currentDirKey === '/') {
                    $currentDir = $this->root;
                } else {
                    $currentDir = $currentDir->dirs[$currentDirKey];
                }
            } else if (str_starts_with($data, '$ ls')) {
                // do nothing
            } else if (str_starts_with($data, 'dir ')) {
                $newDirKey = substr($data, 4);
                $newDir = new Dir($newDirKey, $currentDir);
                $currentDir->dirs[$newDirKey] = $newDir;
                $this->dirs[$newDir->uuid] = $newDir;
            } else {
                $matches = [];
                preg_match_all('/^(\d+) ([a-z]+\.*[a-z]*)$/', $data, $matches);
                $currentDir->files[$matches[2][0]] = $matches[1][0];
            }
        }
    }
}

class Dir
{
    public array $dirs = [];
    public array $files = [];

    public string $uuid;

    public function __construct(
        public readonly string $name,
        public readonly ?Dir   $parent = null
    )
    {
        $this->uuid = uniqid();
    }

    public function getSize(): int
    {
        $size = 0;

        foreach ($this->dirs as $dir) {
            $size += $dir->getSize();
        }

        foreach ($this->files as $file) {
            $size += $file;
        }

        return $size;
    }

    public function print(int $level): void
    {
        if ($level === 0) {
            echo str_repeat('.', $level) . $this->name . ' - ' . $this->getSize() . PHP_EOL;
        }

        foreach ($this->dirs as $dir) {
            echo str_repeat('.', $level + 1) . $dir->name . ' - ' . $dir->getSize() . PHP_EOL;
            $dir->print($level + 1);
        }
    }
}
