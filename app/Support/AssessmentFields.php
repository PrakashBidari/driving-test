<?php

namespace App\Support;

class AssessmentFields
{
    /** @return array<string, array{title: string, blocks: array<string, array<string, array{label: string, type: string}>>}> */
    public static function tabs(): array
    {
        $sd = fn (string $label) => ['label' => $label, 'type' => 'sd'];
        $check = fn (string $label) => ['label' => $label, 'type' => 'check'];

        return [
            'eyesight' => [
                'title' => 'Eyesight & Manoeuvres',
                'blocks' => [
                    'Eyesight test' => [
                        'eyesight' => $check('Eyesight test completed'),
                    ],
                    'Manoeuvres' => [
                        'reverse_right' => $check('Reverse / Right'),
                        'reverse_park_road' => $check('Reverse park (road)'),
                        'reverse_park_car' => $check('Reverse park (car park)'),
                        'forward_park' => $check('Forward park'),
                    ],
                ],
            ],
            'control' => [
                'title' => 'Vehicle Control',
                'blocks' => [
                    'Control' => [
                        'control' => $sd('Control'),
                        'observation' => $sd('Observation'),
                    ],
                    'Show me / Tell me' => [
                        'showtell' => $sd('Show me / Tell me'),
                    ],
                    'Controlled stop' => [
                        'stop' => $sd('Controlled stop'),
                    ],
                    'Handling controls' => [
                        'accelerator' => $sd('Accelerator'),
                        'clutch' => $sd('Clutch'),
                        'gears' => $sd('Gears'),
                        'footbrake' => $sd('Footbrake'),
                        'parking' => $sd('Parking brake'),
                        'steering' => $sd('Steering'),
                    ],
                    'Precautions' => [
                        'precautions' => $sd('Precautions'),
                    ],
                    'Ancillary Controls' => [
                        'ancillary' => $sd('Ancillary Controls'),
                    ],
                ],
            ],
            'onroad' => [
                'title' => 'On-Road Skills',
                'blocks' => [
                    'Move off' => [
                        'move_safety' => $sd('Safety'),
                        'move_control' => $sd('Control'),
                    ],
                    'Use of Mirrors' => [
                        'mirror_signal' => $sd('Signalling'),
                        'mirror_direction' => $sd('Change direction'),
                        'mirror_speed' => $sd('Change speed'),
                    ],
                    'Signals' => [
                        'signal_necessary' => $sd('Necessary'),
                        'signal_correct' => $sd('Correctly'),
                        'signal_timed' => $sd('Timed'),
                    ],
                    'Junctions' => [
                        'junction_speed' => $sd('Approach speed'),
                        'junction_obs' => $sd('Observation'),
                        'turn_right' => $sd('Turning right'),
                        'turn_left' => $sd('Turning left'),
                        'corners' => $sd('Cutting corners'),
                    ],
                    'Judgement' => [
                        'overtaking' => $sd('Overtaking'),
                        'meeting' => $sd('Meeting'),
                        'crossing' => $sd('Crossing'),
                    ],
                ],
            ],
            'awareness' => [
                'title' => 'Awareness & Progress',
                'blocks' => [
                    'Positioning' => [
                        'normal_driving' => $sd('Normal driving'),
                        'lane' => $sd('Lane discipline'),
                    ],
                    'Pedestrian crossings' => [
                        'pedestrian' => $sd('Pedestrian crossings'),
                    ],
                    'Position / normal stop' => [
                        'normal_stop' => $sd('Position / normal stop'),
                    ],
                    'Awareness planning' => [
                        'awareness' => $sd('Awareness planning'),
                    ],
                    'Clearance' => [
                        'clearance' => $sd('Clearance'),
                    ],
                    'Following distance' => [
                        'following' => $sd('Following distance'),
                    ],
                    'Use of speed' => [
                        'speed' => $sd('Use of speed'),
                    ],
                    'Progress' => [
                        'appropriate_speed' => $sd('Appropriate speed'),
                        'hesitation' => $sd('Undue hesitation'),
                    ],
                    'Response to signs / signals' => [
                        'traffic_signs' => $sd('Traffic signs'),
                        'road_markings' => $sd('Road markings'),
                        'traffic_lights' => $sd('Traffic lights'),
                        'controllers' => $sd('Traffic controllers'),
                        'road_users' => $sd('Other road users'),
                    ],
                ],
            ],
        ];
    }

    public static function footerItems(): array
    {
        return [
            'eta' => 'ETA',
            'physical' => 'Physical',
            'verbal' => 'Verbal',
            'eco' => 'ECO',
            'control_footer' => 'Control',
            'planning' => 'Planning',
        ];
    }
}
