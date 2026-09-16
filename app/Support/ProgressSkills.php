<?php

namespace App\Support;

class ProgressSkills
{
    public const LESSONS = 20;

    /** @return array<int, array{0: string, 1: array<int, string>}> */
    public static function groups(): array
    {
        return [
            ['CORE DRIVING', ['Cockpit Check', 'Safety Check', 'Controls and Instruments', 'Moving Away & Stopping', 'Safe Positioning', 'Mirrors - Vision & Use', 'Signals', 'Anticipation & Planning', 'Use of Speed']],
            ['OTHER TRAFFIC', ['Meeting Traffic', 'Crossing Traffic', 'Overtaking', 'Roundabouts', 'Pedestrian Crossing', 'Dual Carriageways', 'Turning the Vehicle Around']],
            ['REVERSING', ['Reversing', 'Straight Reverse', 'Left Reverse', 'Right Reverse']],
        ];
    }

    /** @return array<int, array{group: string, skill: string}> */
    public static function rows(): array
    {
        $rows = [];
        $row = 0;

        foreach (self::groups() as [$group, $skills]) {
            foreach ($skills as $skill) {
                $rows[$row] = ['group' => $group, 'skill' => $skill];
                $row++;
            }
        }

        return $rows;
    }
}
