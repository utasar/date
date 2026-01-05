<?php

require_once __DIR__ . '/../models/Activity.php';

class ActivityController {
    
    public function logActivity($userId, $activityData) {
        $activity = new Activity([
            'userId' => $userId,
            'date' => $activityData['date'] ?? date('Y-m-d'),
            'type' => $activityData['type'] ?? '',
            'duration' => $activityData['duration'] ?? 0,
            'caloriesBurned' => $this->calculateCalories($activityData['type'], $activityData['duration'])
        ]);
        
        return [
            'success' => true,
            'message' => 'Activity logged successfully',
            'activity' => [
                'type' => $activity->getType(),
                'duration' => $activity->getDuration(),
                'caloriesBurned' => $activity->getCaloriesBurned(),
                'date' => $activity->getDate()
            ]
        ];
    }
    
    public function getActivities($userId, $date = null) {
        $date = $date ?? date('Y-m-d');
        
        // Sample activities for demonstration
        $activities = [
            [
                'type' => 'Running',
                'duration' => 30,
                'caloriesBurned' => 300,
                'completed' => true,
                'date' => $date
            ],
            [
                'type' => 'Strength Training',
                'duration' => 45,
                'caloriesBurned' => 200,
                'completed' => true,
                'date' => $date
            ],
            [
                'type' => 'Yoga',
                'duration' => 20,
                'caloriesBurned' => 80,
                'completed' => false,
                'date' => $date
            ]
        ];
        
        return [
            'date' => $date,
            'activities' => $activities,
            'totalCaloriesBurned' => array_sum(array_column($activities, 'caloriesBurned')),
            'totalDuration' => array_sum(array_column($activities, 'duration'))
        ];
    }
    
    public function markCompleted($userId, $activityId) {
        return [
            'success' => true,
            'message' => 'Activity marked as completed',
            'activityId' => $activityId
        ];
    }
    
    public function getSuggestedExercises($fitnessLevel = 'beginner') {
        $exercises = [
            'beginner' => [
                [
                    'name' => 'Walking',
                    'duration' => 20,
                    'intensity' => 'Low',
                    'caloriesPerMin' => 5,
                    'instructions' => 'Brisk walk at a comfortable pace, maintain good posture'
                ],
                [
                    'name' => 'Bodyweight Squats',
                    'duration' => 10,
                    'intensity' => 'Low',
                    'caloriesPerMin' => 7,
                    'instructions' => '3 sets of 10 reps, focus on form over speed'
                ],
                [
                    'name' => 'Wall Push-ups',
                    'duration' => 10,
                    'intensity' => 'Low',
                    'caloriesPerMin' => 6,
                    'instructions' => '3 sets of 8-10 reps, keep core engaged'
                ]
            ],
            'intermediate' => [
                [
                    'name' => 'Jogging',
                    'duration' => 30,
                    'intensity' => 'Medium',
                    'caloriesPerMin' => 10,
                    'instructions' => 'Maintain steady pace, focus on breathing rhythm'
                ],
                [
                    'name' => 'Regular Squats',
                    'duration' => 15,
                    'intensity' => 'Medium',
                    'caloriesPerMin' => 8,
                    'instructions' => '4 sets of 12 reps, add weights for more challenge'
                ],
                [
                    'name' => 'Regular Push-ups',
                    'duration' => 15,
                    'intensity' => 'Medium',
                    'caloriesPerMin' => 9,
                    'instructions' => '3 sets of 10-15 reps, maintain proper form'
                ]
            ],
            'advanced' => [
                [
                    'name' => 'Running',
                    'duration' => 45,
                    'intensity' => 'High',
                    'caloriesPerMin' => 12,
                    'instructions' => 'Include intervals for maximum benefit'
                ],
                [
                    'name' => 'Jump Squats',
                    'duration' => 20,
                    'intensity' => 'High',
                    'caloriesPerMin' => 12,
                    'instructions' => '4 sets of 15 reps, explosive movement'
                ],
                [
                    'name' => 'Burpees',
                    'duration' => 15,
                    'intensity' => 'High',
                    'caloriesPerMin' => 15,
                    'instructions' => '3 sets of 10-12 reps, full body engagement'
                ]
            ]
        ];
        
        return $exercises[$fitnessLevel] ?? $exercises['beginner'];
    }
    
    // Default calories per minute for unknown activities (moderate intensity)
    private const DEFAULT_CALORIES_PER_MINUTE = 7;
    
    private function calculateCalories($type, $duration) {
        $caloriesPerMinute = [
            'Walking' => 5,
            'Jogging' => 10,
            'Running' => 12,
            'Cycling' => 8,
            'Swimming' => 11,
            'Strength Training' => 6,
            'Yoga' => 4,
            'HIIT' => 15,
            'Dancing' => 7,
            'Sports' => 10
        ];
        
        $rate = $caloriesPerMinute[$type] ?? self::DEFAULT_CALORIES_PER_MINUTE;
        return round($rate * $duration);
    }
}
