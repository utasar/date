<?php

require_once __DIR__ . '/../controllers/ScheduleController.php';
require_once __DIR__ . '/../controllers/HealthController.php';
require_once __DIR__ . '/../controllers/ActivityController.php';
require_once __DIR__ . '/../controllers/LocationController.php';
require_once __DIR__ . '/../controllers/GoalController.php';

class Router {
    private $scheduleController;
    private $healthController;
    private $activityController;
    private $locationController;
    private $goalController;
    
    public function __construct() {
        $this->scheduleController = new ScheduleController();
        $this->healthController = new HealthController();
        $this->activityController = new ActivityController();
        $this->locationController = new LocationController();
        $this->goalController = new GoalController();
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/backend/', '', $path);
        
        // Get request data
        $data = [];
        if ($method === 'POST' || $method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
        } elseif ($method === 'GET') {
            $data = $_GET;
        }
        
        // Default userId for demo purposes
        $userId = $data['userId'] ?? 1;
        
        // Route handling
        switch ($path) {
            // Schedule routes
            case 'schedule':
                if ($method === 'GET') {
                    return $this->scheduleController->getSchedule($userId, $data['date'] ?? null);
                } elseif ($method === 'POST') {
                    return $this->scheduleController->addTask($userId, $data['date'] ?? date('Y-m-d'), $data['task'] ?? []);
                }
                break;
                
            // Health routes
            case 'health/calories':
                return $this->healthController->getCalorieGuide($userId, $data['activityLevel'] ?? 'moderate');
                
            case 'health/dietitians':
                return $this->healthController->getDietitianRecommendations($data['location'] ?? 'Your City');
                
            case 'health/food-suggestions':
                return $this->healthController->getFoodSuggestions($data['healthGoal'] ?? 'maintenance', $data['mealType'] ?? 'all');
                
            case 'health/plan':
                return $this->healthController->generateHealthPlan($userId, $data['goal'] ?? 'maintenance', $data['duration'] ?? 30);
                
            // Activity routes
            case 'activities':
                if ($method === 'GET') {
                    return $this->activityController->getActivities($userId, $data['date'] ?? null);
                } elseif ($method === 'POST') {
                    return $this->activityController->logActivity($userId, $data);
                }
                break;
                
            case 'activities/complete':
                return $this->activityController->markCompleted($userId, $data['activityId'] ?? 0);
                
            case 'activities/exercises':
                return $this->activityController->getSuggestedExercises($data['fitnessLevel'] ?? 'beginner');
                
            // Location routes
            case 'location/gyms':
                return $this->locationController->getGymRecommendations($data['location'] ?? 'Your City', $data['preferences'] ?? []);
                
            case 'location/resources':
                return $this->locationController->getFitnessResources($data['location'] ?? 'Your City', $data['type'] ?? 'all');
                
            case 'location/restaurants':
                return $this->locationController->getHealthyRestaurants($data['location'] ?? 'Your City', $data['dietType'] ?? 'all');
                
            // Goal routes
            case 'goals':
                if ($method === 'GET') {
                    return $this->goalController->getGoals($userId, $data['status'] ?? 'active');
                } elseif ($method === 'POST') {
                    return $this->goalController->createGoal($userId, $data);
                }
                break;
                
            case 'goals/today':
                return $this->goalController->getTodayGoals($userId);
                
            case 'goals/upcoming':
                return $this->goalController->getUpcomingGoals($userId, $data['days'] ?? 7);
                
            case 'goals/progress':
                return $this->goalController->updateProgress($userId, $data['goalId'] ?? 0, $data['progress'] ?? 0);
                
            default:
                return ['error' => 'Route not found', 'path' => $path];
        }
        
        return ['error' => 'Invalid request'];
    }
}
