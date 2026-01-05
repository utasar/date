<?php

require_once __DIR__ . '/../models/Goal.php';

class GoalController {
    
    public function createGoal($userId, $goalData) {
        $goal = new Goal([
            'userId' => $userId,
            'title' => $goalData['title'] ?? '',
            'description' => $goalData['description'] ?? '',
            'targetDate' => $goalData['targetDate'] ?? date('Y-m-d', strtotime('+30 days')),
            'steps' => $goalData['steps'] ?? []
        ]);
        
        return [
            'success' => true,
            'message' => 'Goal created successfully',
            'goal' => [
                'title' => $goal->getTitle(),
                'description' => $goal->getDescription(),
                'targetDate' => $goal->getTargetDate(),
                'progress' => $goal->getProgress(),
                'steps' => $goal->getSteps()
            ]
        ];
    }
    
    public function getGoals($userId, $status = 'active') {
        // Sample goals for demonstration
        $goals = [
            [
                'id' => 1,
                'title' => 'Lose 5kg in 2 months',
                'description' => 'Healthy weight loss through diet and exercise',
                'targetDate' => date('Y-m-d', strtotime('+60 days')),
                'progress' => 35,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'title' => 'Run 5km without stopping',
                'description' => 'Build endurance for continuous 5km run',
                'targetDate' => date('Y-m-d', strtotime('+45 days')),
                'progress' => 60,
                'status' => 'active'
            ],
            [
                'id' => 3,
                'title' => 'Practice yoga daily',
                'description' => 'Establish a daily yoga practice for flexibility',
                'targetDate' => date('Y-m-d', strtotime('+30 days')),
                'progress' => 80,
                'status' => 'active'
            ]
        ];
        
        return [
            'goals' => $goals,
            'total' => count($goals)
        ];
    }
    
    public function updateProgress($userId, $goalId, $progress) {
        return [
            'success' => true,
            'message' => 'Goal progress updated',
            'goalId' => $goalId,
            'progress' => $progress
        ];
    }
    
    public function getTodayGoals($userId) {
        $today = date('Y-m-d');
        
        return [
            'date' => $today,
            'goals' => [
                [
                    'title' => 'Morning workout',
                    'type' => 'exercise',
                    'completed' => false,
                    'priority' => 'high'
                ],
                [
                    'title' => 'Track meals',
                    'type' => 'nutrition',
                    'completed' => true,
                    'priority' => 'medium'
                ],
                [
                    'title' => 'Drink 8 glasses of water',
                    'type' => 'hydration',
                    'completed' => false,
                    'priority' => 'medium'
                ],
                [
                    'title' => 'Evening walk',
                    'type' => 'exercise',
                    'completed' => false,
                    'priority' => 'low'
                ]
            ]
        ];
    }
    
    public function getUpcomingGoals($userId, $days = 7) {
        $upcoming = [];
        
        for ($i = 1; $i <= $days; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} days"));
            $upcoming[] = [
                'date' => $date,
                'goals' => [
                    [
                        'title' => 'Daily exercise routine',
                        'type' => 'exercise',
                        'estimatedDuration' => 45
                    ],
                    [
                        'title' => 'Meal prep',
                        'type' => 'nutrition',
                        'estimatedDuration' => 60
                    ]
                ]
            ];
        }
        
        return [
            'period' => $days . ' days',
            'upcoming' => $upcoming
        ];
    }
}
