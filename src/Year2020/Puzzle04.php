<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle04 extends AbstractPuzzle
{
    private array $passports = [];

    public function runPart01(): int
    {
        $this->loadData();

        $valid = 0;

        foreach ($this->passports as $passport) {
            if ($this->isValid($passport)) {
                $valid++;
            }
        }

        return $valid;
    }

    private function isValid($passport): bool
    {
        return count($passport) === 8 || (count($passport) === 7 && !isset($passport['cid']));
    }

    public function runPart02(): int
    {
        $this->loadData();

        $valid = 0;

        foreach ($this->passports as $passport) {
            if ($this->isValid($passport) && $this->isMoreValid($passport)) {
                $valid++;
            }
        }

        return $valid;
    }

    private function isMoreValid($passport): bool
    {
        foreach ($passport as $key => $value) {
            if (!$this->isValidField($key, $value)) {
                return false;
            }
        }

        return true;
    }

    private function loadData(): void
    {
        $i = 0;

        foreach ($this->data as $line) {
            if ($line === '') {
                $i++;
            } else {
                $fields = explode(' ', $line);

                foreach ($fields as $field) {
                    [$key, $value] = explode(':', $field);

                    $this->passports[$i][$key] = $value;
                }
            }
        }
    }

    private function isValidField(string $key, string $value): bool
    {
        switch ($key) {
            case 'byr':
                return $this->isBetween($value, 1920, 2002);
            case 'iyr':
                return $this->isBetween($value, 2010, 2020);
            case 'eyr':
                return $this->isBetween($value, 2020, 2030);
            case 'hgt':
                return $this->isValidHeight($value);
            case 'hcl':
                return $this->isValidHairColor($value);
            case 'ecl':
                return $this->isValidEyeColor($value);
            case 'pid':
                return $this->isValidPassportId($value);
            case 'cid':
            default:
                return true;
        }
    }

    private function isBetween(string $value, int $min, int $max): bool
    {
        return $value >= $min && $value <= $max;
    }

    private function isValidHeight(string $value): bool
    {
        if (preg_match('/^(\d+)(cm|in)$/', $value, $matches)) {
            if ($matches[2] === 'cm') {
                return $this->isBetween($matches[1], 150, 193);
            } else {
                return $this->isBetween($matches[1], 59, 76);
            }
        }

        return false;
    }

    private function isValidHairColor(string $value): bool
    {
        return preg_match('/^#[0-9a-f]{6}$/', $value);
    }

    private function isValidEyeColor(string $value): bool
    {
        return in_array($value, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
    }

    private function isValidPassportId(string $value): bool
    {
        return preg_match('/^\d{9}$/', $value);
    }
}
