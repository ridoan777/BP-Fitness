<?php

return [
    database_path('migrations'),
    database_path('migrations/Defaults'),
    
    // --------------- PACKAGES ---------------
    database_path('migrations/Packages'),
    database_path('migrations/Packages/laravel_cashier'),
    database_path('migrations/Packages/Ai'),

    // --------------- SETTINGS ---------------
    database_path('migrations/Settings'),
    database_path('migrations/Settings/Options'),
    database_path('migrations/Settings/Notifications'),
    database_path('migrations/Settings/Role_Managements'),

    // --------------- MANAGEMENTS ---------------
    database_path('migrations/Managements'),
    
    // --------------- COMMUNICATIONS ---------------
    database_path('migrations/Communications'),

    // --------------- WORKFLOWS ---------------
    database_path('migrations/Workflows/Projects'),
    database_path('migrations/Workflows/Journeys'),
    database_path('migrations/Workflows/Meals'),
    database_path('migrations/Workflows/Products'),
    database_path('migrations/Workflows/Product_orders'),
    database_path('migrations/Workflows/My_stats'),
    database_path('migrations/Workflows/Workout_courses'),
    database_path('migrations/Workflows/Workout_metrices'),
    database_path('migrations/Workflows/Community_forums'),
    database_path('migrations/Workflows/Motivations'),
    database_path('migrations/Workflows/Achievements'),

    // --------------- BILLING ---------------
    database_path('migrations/Billings/Subscriptions'),
    database_path('migrations/Billings/Payments'),

    // must remain inactive
    // database_path('migrations/database/migrations/MISC'),
];

