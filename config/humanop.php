<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Energy Pool -> Capacity Points
    |--------------------------------------------------------------------------
    | These values are directly aligned to the client docs.
    */
    'energy_capacity' => [
        'fair' => 350,
        'average' => 500,
        'excellent' => 650,
        'above_excellent' => 750,
    ],

    /*
    |--------------------------------------------------------------------------
    | Explicit thresholds from client docs where stated
    |--------------------------------------------------------------------------
    */
    'thresholds' => [

        'panic' => [
            'heart_rate' => 100,
            'steps_per_minute_max' => 10,
        ],

        'noise' => [
            'overload_db' => 80,
            'solitude_target_db' => 45,
        ],

        'stubbornness' => [
            'minutes_low_hrv_sedentary_min' => 90,
            'minutes_low_hrv_sedentary_max' => 120,
            'sedentary_total_steps_max' => 50,
            'hrv_low_threshold' => 20,
        ],

        'volatility' => [
            'window_minutes' => 5,
            'heart_rate_range_delta' => 30,
            'minimum_samples' => 3,
        ],

        'manic' => [
            'sleep_minutes_max' => 300, // < 5 hours
            'steps_next_day_min' => 12000,
        ],

        'immaturity' => [
            'window_hours' => 3,
            'steps_window_max' => 100,
        ],

        'neglect' => [
            'window_days' => 3,
            'total_steps_max' => 3000,
        ],

        'intimidation' => [
            'heart_rate_threshold' => 95,
            'hrv_low_threshold' => 20,
        ],

        'woe_is_me' => [
            'sleep_minutes_min' => 600,
            'steps_today_max' => 2000,
        ],

        'deprivation' => [
            'window_days' => 3,
            'distinct_locations_min' => 2,
        ],

        'self_absorption' => [
            'window_days' => 2,
            'total_steps_max' => 1500,
        ],

        'rigidity' => [
            'heart_rate_threshold' => 95,
            'requires_schedule_deviation' => true,
        ],

        'gluttony' => [
            'evening_hour_start' => 18,
            'hrv_low_threshold' => 20,
        ],
    ],

    'event_cooldowns' => [

        'panic' => 5,           // minutes
        'volatility' => 10,
        'stubbornness' => 30,
        'gluttony' => 60,
        'manic' => 120,
        'immaturity' => 60,
        'neglect' => 180,
        'intimidation' => 20,
        'woe_is_me' => 180,
        'deprivation' => 180,
        'self_absorption' => 180,
        'rigidity' => 20

    ],

    /*
    |--------------------------------------------------------------------------
    | EBS Modifiers from client docs
    |--------------------------------------------------------------------------
    */
    'modifiers' => [
        'trait' => [
            'sympathetic_nap' => 1.5,
            'romantic_solitude' => 1.5,
            'energetic_movement' => 1.5,
            'default' => 1.0,
        ],

        'driver' => [
            'aligned' => 1.2,
            'neutral' => 1.0,
            'opposed' => 0.5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Q_physio
    |--------------------------------------------------------------------------
    | High fidelity mode from client docs:
    | Q_physio = current_HRV / baseline_HRV * coherence_factor
    */
    'q_physio' => [
        'coherence_factor' => [
            'coherent' => 1.5,
            'normal' => 1.0,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed metrics from React Native app ingestion
    |--------------------------------------------------------------------------
    */
    'allowed_metrics' => [
        'heart_rate',
        'hrv_sdnn',
        'steps',
        'audio_db',
        'sleep_stage',
        'resting_hr',
        'schedule_deviation',
    ],

    /*
    |--------------------------------------------------------------------------
    | Boost protocol mapping
    |--------------------------------------------------------------------------
    */
    'protocols' => [
        'panic' => 'resonance_breathing',
        'volatility' => 'resonance_breathing',
        'stubbornness' => 'movement_break',
        'gluttony' => 'resonance_breathing',
        'manic' => 'rest_protocol',
        'immaturity' => 'movement',
        'neglect' => 'movement',
        'intimidation' => 'resonance_breathing',
        'woe_is_me' => 'movement',
        'deprivation' => 'travel',
        'self_absorption' => 'social_activity',
        'rigidity' => 'resonance_breathing',
    ],

    'event_drains' => [

        'panic' => 25, // Points
        'volatility' => 20,
        'stubbornness' => 15,
        'gluttony' => 15,
        'manic' => 40,
        'immaturity' => 10,
        'neglect' => 20,
        'intimidation' => 20,
        'woe_is_me' => 20,
        'deprivation' => 15,
        'self_absorption' => 15,
        'rigidity' => 15

    ],
];
