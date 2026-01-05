<?php

require_once __DIR__ . '/../models/User.php';

class HealthController {
    
    // Macro nutrient percentages
    private const PROTEIN_PERCENTAGE = 0.30;
    private const CARBS_PERCENTAGE = 0.40;
    private const FATS_PERCENTAGE = 0.30;
    
    // Calories per gram
    private const PROTEIN_CALORIES_PER_GRAM = 4;
    private const CARBS_CALORIES_PER_GRAM = 4;
    private const FATS_CALORIES_PER_GRAM = 9;
    
    // Meal breakdown percentages
    private const BREAKFAST_PERCENTAGE = 0.25;
    private const LUNCH_PERCENTAGE = 0.35;
    private const DINNER_PERCENTAGE = 0.30;
    private const SNACKS_PERCENTAGE = 0.10;
    
    public function getCalorieGuide($userId, $activityLevel = 'moderate') {
        $baseCalories = $this->calculateBaseCalories($activityLevel);
        
        return [
            'dailyCalories' => $baseCalories,
            'breakdown' => [
                'breakfast' => round($baseCalories * self::BREAKFAST_PERCENTAGE),
                'lunch' => round($baseCalories * self::LUNCH_PERCENTAGE),
                'dinner' => round($baseCalories * self::DINNER_PERCENTAGE),
                'snacks' => round($baseCalories * self::SNACKS_PERCENTAGE)
            ],
            'macros' => [
                'protein' => round($baseCalories * self::PROTEIN_PERCENTAGE / self::PROTEIN_CALORIES_PER_GRAM),
                'carbs' => round($baseCalories * self::CARBS_PERCENTAGE / self::CARBS_CALORIES_PER_GRAM),
                'fats' => round($baseCalories * self::FATS_PERCENTAGE / self::FATS_CALORIES_PER_GRAM)
            ]
        ];
    }
    
    public function getDietitianRecommendations($location) {
        // Sample recommendations based on location
        return [
            'dietitians' => [
                [
                    'name' => 'Healthy Living Nutrition Center',
                    'location' => $location,
                    'specialization' => 'Weight management and sports nutrition',
                    'rating' => 4.8,
                    'distance' => '0.5 km'
                ],
                [
                    'name' => 'Wellness Dietitian Clinic',
                    'location' => $location,
                    'specialization' => 'General health and disease management',
                    'rating' => 4.6,
                    'distance' => '1.2 km'
                ],
                [
                    'name' => 'Peak Performance Nutrition',
                    'location' => $location,
                    'specialization' => 'Athletic performance and fitness',
                    'rating' => 4.9,
                    'distance' => '2.0 km'
                ]
            ]
        ];
    }
    
    public function getFoodSuggestions($healthGoal, $mealType = 'all') {
        $suggestions = [];
        
        if ($healthGoal === 'weight_loss' || $mealType === 'all') {
            $suggestions['weight_loss'] = [
                'breakfast' => ['Oatmeal with berries', 'Greek yogurt with nuts', 'Veggie omelet'],
                'lunch' => ['Grilled chicken salad', 'Quinoa bowl with vegetables', 'Turkey wrap with greens'],
                'dinner' => ['Baked salmon with broccoli', 'Lean beef stir-fry', 'Grilled fish with asparagus'],
                'snacks' => ['Apple slices', 'Carrot sticks', 'Almonds']
            ];
        }
        
        if ($healthGoal === 'muscle_gain' || $mealType === 'all') {
            $suggestions['muscle_gain'] = [
                'breakfast' => ['Protein pancakes', 'Scrambled eggs with toast', 'Protein smoothie'],
                'lunch' => ['Chicken breast with rice', 'Tuna sandwich', 'Beef and sweet potato'],
                'dinner' => ['Grilled steak with quinoa', 'Chicken pasta', 'Salmon with brown rice'],
                'snacks' => ['Protein bar', 'Cottage cheese', 'Peanut butter']
            ];
        }
        
        if ($healthGoal === 'maintenance' || $mealType === 'all') {
            $suggestions['maintenance'] = [
                'breakfast' => ['Whole grain cereal', 'Toast with avocado', 'Fruit smoothie'],
                'lunch' => ['Mixed salad with protein', 'Whole grain sandwich', 'Soup and bread'],
                'dinner' => ['Balanced plate with protein and vegetables', 'Pasta with lean meat', 'Rice bowl'],
                'snacks' => ['Fresh fruit', 'Yogurt', 'Trail mix']
            ];
        }
        
        return $suggestions;
    }
    
    public function generateHealthPlan($userId, $goal, $duration = 30) {
        $plan = [
            'goal' => $goal,
            'duration' => $duration . ' days',
            'steps' => []
        ];
        
        if ($goal === 'weight_loss') {
            $plan['steps'] = [
                [
                    'week' => 1,
                    'title' => 'Foundation Week',
                    'tasks' => [
                        'Track daily calorie intake',
                        'Start 20-minute walks daily',
                        'Drink 8 glasses of water',
                        'Cut sugary drinks'
                    ]
                ],
                [
                    'week' => 2,
                    'title' => 'Building Momentum',
                    'tasks' => [
                        'Increase cardio to 30 minutes',
                        'Add strength training 2x per week',
                        'Meal prep for the week',
                        'Reduce portion sizes by 15%'
                    ]
                ],
                [
                    'week' => 3,
                    'title' => 'Intensifying',
                    'tasks' => [
                        'Cardio 40 minutes, 5x per week',
                        'Strength training 3x per week',
                        'Track macros daily',
                        'Add HIIT sessions 2x per week'
                    ]
                ],
                [
                    'week' => 4,
                    'title' => 'Sustaining Progress',
                    'tasks' => [
                        'Maintain exercise routine',
                        'Review and adjust calorie intake',
                        'Measure progress and celebrate wins',
                        'Plan for the next month'
                    ]
                ]
            ];
        } elseif ($goal === 'muscle_gain') {
            $plan['steps'] = [
                [
                    'week' => 1,
                    'title' => 'Foundation Building',
                    'tasks' => [
                        'Increase protein intake to 1.6g per kg body weight',
                        'Start resistance training 3x per week',
                        'Focus on compound exercises',
                        'Get 8 hours of sleep'
                    ]
                ],
                [
                    'week' => 2,
                    'title' => 'Progressive Overload',
                    'tasks' => [
                        'Increase training volume by 10%',
                        'Add post-workout protein shake',
                        'Track strength gains',
                        'Optimize meal timing'
                    ]
                ],
                [
                    'week' => 3,
                    'title' => 'Muscle Development',
                    'tasks' => [
                        'Increase training to 4x per week',
                        'Add isolation exercises',
                        'Increase calorie surplus by 200',
                        'Focus on mind-muscle connection'
                    ]
                ],
                [
                    'week' => 4,
                    'title' => 'Recovery and Assessment',
                    'tasks' => [
                        'Deload week - reduce volume by 40%',
                        'Measure muscle growth',
                        'Adjust nutrition plan',
                        'Plan next training cycle'
                    ]
                ]
            ];
        } else {
            $plan['steps'] = [
                [
                    'week' => 1,
                    'title' => 'Establishing Routine',
                    'tasks' => [
                        'Exercise 3-4 times per week',
                        'Maintain balanced diet',
                        'Track daily activities',
                        'Set specific health goals'
                    ]
                ],
                [
                    'week' => 2,
                    'title' => 'Consistency Building',
                    'tasks' => [
                        'Continue exercise routine',
                        'Add variety to workouts',
                        'Prepare healthy meals',
                        'Monitor energy levels'
                    ]
                ],
                [
                    'week' => 3,
                    'title' => 'Habit Formation',
                    'tasks' => [
                        'Maintain exercise consistency',
                        'Try new healthy recipes',
                        'Include active recovery',
                        'Reflect on progress'
                    ]
                ],
                [
                    'week' => 4,
                    'title' => 'Long-term Planning',
                    'tasks' => [
                        'Review monthly achievements',
                        'Adjust goals as needed',
                        'Plan for sustainable habits',
                        'Celebrate consistency'
                    ]
                ]
            ];
        }
        
        return $plan;
    }
    
    private function calculateBaseCalories($activityLevel) {
        $baseCalories = [
            'sedentary' => 1800,
            'light' => 2000,
            'moderate' => 2200,
            'active' => 2500,
            'very_active' => 2800
        ];
        
        return $baseCalories[$activityLevel] ?? $baseCalories['moderate'];
    }
}
