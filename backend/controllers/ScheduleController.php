<?php

require_once __DIR__ . '/../models/Schedule.php';

class ScheduleController {
    
    public function getSchedule($userId, $date = null) {
        $date = $date ?? date('Y-m-d');
        
        // Create sample schedule with suggestions
        $schedule = new Schedule([
            'userId' => $userId,
            'date' => $date
        ]);
        
        // Analyze schedule and add suggestions
        $suggestions = $this->generateSuggestions($date);
        foreach ($suggestions as $suggestion) {
            $schedule->addSuggestion($suggestion);
        }
        
        return [
            'date' => $schedule->getDate(),
            'tasks' => $schedule->getTasks(),
            'suggestions' => $schedule->getSuggestions()
        ];
    }
    
    public function addTask($userId, $date, $task) {
        $schedule = new Schedule([
            'userId' => $userId,
            'date' => $date
        ]);
        
        $schedule->addTask($task);
        
        return [
            'success' => true,
            'message' => 'Task added successfully',
            'task' => $task
        ];
    }
    
    private function generateSuggestions($date) {
        $hour = (int)date('H');
        $suggestions = [];
        
        // Morning suggestions (6 AM - 12 PM)
        if ($hour >= 6 && $hour < 12) {
            $suggestions[] = [
                'time' => '07:00',
                'type' => 'exercise',
                'title' => 'Morning Workout',
                'description' => '30-minute cardio session to boost your energy',
                'duration' => 30
            ];
            $suggestions[] = [
                'time' => '08:00',
                'type' => 'meal',
                'title' => 'Healthy Breakfast',
                'description' => 'High-protein breakfast with fruits and whole grains',
                'calories' => 400
            ];
        }
        
        // Afternoon suggestions (12 PM - 6 PM)
        if ($hour >= 12 && $hour < 18) {
            $suggestions[] = [
                'time' => '13:00',
                'type' => 'meal',
                'title' => 'Balanced Lunch',
                'description' => 'Lean protein with vegetables and complex carbs',
                'calories' => 600
            ];
            $suggestions[] = [
                'time' => '15:00',
                'type' => 'break',
                'title' => 'Productive Break',
                'description' => '15-minute walk or stretching session',
                'duration' => 15
            ];
        }
        
        // Evening suggestions (6 PM - 10 PM)
        if ($hour >= 18 && $hour < 22) {
            $suggestions[] = [
                'time' => '18:00',
                'type' => 'exercise',
                'title' => 'Gym Session',
                'description' => 'Strength training and flexibility exercises',
                'duration' => 60
            ];
            $suggestions[] = [
                'time' => '19:30',
                'type' => 'family',
                'title' => 'Family Time',
                'description' => 'Quality time with loved ones - dinner or activities',
                'duration' => 90
            ];
        }
        
        return $suggestions;
    }
}
