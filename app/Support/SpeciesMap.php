<?php

namespace App\Support;

class SpeciesMap
{
    public static function all(): array
    {
        return [
            'Tyrannosaurus Rex' => true,
            'Velociraptor' => true,
            'Spinosaurus' => true,
            'Carnotaurus' => true,
            'Triceratops' => false,
            'Brachiosaurus' => false,
            'Stegosaurus' => false,
            'Ankylosaurus' => false,
        ];
    }

    public static function isPredator(string $species): ?bool
    {
        $lookup = array_change_key_case(self::all(), CASE_LOWER);
        return $lookup[strtolower(trim($species))] ?? null;
    }

    public static function species(): array
    {
        return array_keys(self::all());
    }
}
