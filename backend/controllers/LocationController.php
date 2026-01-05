<?php

class LocationController {
    
    public function getGymRecommendations($location, $preferences = []) {
        // Sample gym recommendations
        return [
            'gyms' => [
                [
                    'name' => 'FitLife Gym & Fitness Center',
                    'location' => $location,
                    'address' => '123 Main Street',
                    'distance' => '0.8 km',
                    'rating' => 4.7,
                    'amenities' => ['Cardio equipment', 'Free weights', 'Group classes', 'Personal training'],
                    'pricing' => '$30/month',
                    'hours' => '6 AM - 10 PM'
                ],
                [
                    'name' => 'PowerHouse Fitness',
                    'location' => $location,
                    'address' => '456 Oak Avenue',
                    'distance' => '1.5 km',
                    'rating' => 4.9,
                    'amenities' => ['Olympic weightlifting', 'CrossFit area', 'Sauna', 'Nutrition coaching'],
                    'pricing' => '$45/month',
                    'hours' => '5 AM - 11 PM'
                ],
                [
                    'name' => 'Wellness 360 Gym',
                    'location' => $location,
                    'address' => '789 Park Road',
                    'distance' => '2.2 km',
                    'rating' => 4.6,
                    'amenities' => ['Swimming pool', 'Yoga studio', 'Spin classes', 'Childcare'],
                    'pricing' => '$40/month',
                    'hours' => '6 AM - 9 PM'
                ],
                [
                    'name' => '24/7 Fitness Hub',
                    'location' => $location,
                    'address' => '321 Fitness Lane',
                    'distance' => '3.0 km',
                    'rating' => 4.5,
                    'amenities' => ['24-hour access', 'Modern equipment', 'Personal lockers', 'Smoothie bar'],
                    'pricing' => '$35/month',
                    'hours' => '24 hours'
                ]
            ]
        ];
    }
    
    public function getFitnessResources($location, $type = 'all') {
        $resources = [
            'personal_trainers' => [
                [
                    'name' => 'Mike Johnson',
                    'specialization' => 'Weight loss and conditioning',
                    'experience' => '8 years',
                    'rating' => 4.9,
                    'location' => $location,
                    'pricing' => '$60/session'
                ],
                [
                    'name' => 'Sarah Williams',
                    'specialization' => 'Strength training and bodybuilding',
                    'experience' => '6 years',
                    'rating' => 4.8,
                    'location' => $location,
                    'pricing' => '$55/session'
                ]
            ],
            'yoga_studios' => [
                [
                    'name' => 'Zen Yoga Studio',
                    'style' => 'Hatha and Vinyasa',
                    'location' => $location,
                    'rating' => 4.8,
                    'pricing' => '$15/class'
                ],
                [
                    'name' => 'Power Yoga Center',
                    'style' => 'Power and Hot Yoga',
                    'location' => $location,
                    'rating' => 4.7,
                    'pricing' => '$18/class'
                ]
            ],
            'sports_clubs' => [
                [
                    'name' => 'Community Sports Complex',
                    'activities' => ['Basketball', 'Tennis', 'Swimming'],
                    'location' => $location,
                    'rating' => 4.6,
                    'pricing' => '$25/month'
                ]
            ],
            'running_groups' => [
                [
                    'name' => 'Morning Runners Club',
                    'meetingTime' => 'Weekdays 6 AM',
                    'location' => $location,
                    'level' => 'All levels',
                    'pricing' => 'Free'
                ]
            ]
        ];
        
        if ($type === 'all') {
            return $resources;
        }
        
        return isset($resources[$type]) ? [$type => $resources[$type]] : [];
    }
    
    public function getHealthyRestaurants($location, $dietType = 'all') {
        $restaurants = [
            [
                'name' => 'Green Leaf Cafe',
                'location' => $location,
                'cuisine' => 'Vegetarian & Vegan',
                'distance' => '0.5 km',
                'rating' => 4.8,
                'priceRange' => '$$',
                'specialties' => ['Buddha bowls', 'Smoothie bowls', 'Fresh juices']
            ],
            [
                'name' => 'Protein Palace',
                'location' => $location,
                'cuisine' => 'High-protein meals',
                'distance' => '1.0 km',
                'rating' => 4.7,
                'priceRange' => '$$$',
                'specialties' => ['Grilled chicken', 'Lean beef', 'Fish dishes']
            ],
            [
                'name' => 'Balanced Bites',
                'location' => $location,
                'cuisine' => 'Healthy mixed',
                'distance' => '1.2 km',
                'rating' => 4.6,
                'priceRange' => '$$',
                'specialties' => ['Salad bar', 'Grain bowls', 'Lean wraps']
            ],
            [
                'name' => 'Organic Harvest',
                'location' => $location,
                'cuisine' => 'Organic & Local',
                'distance' => '2.0 km',
                'rating' => 4.9,
                'priceRange' => '$$$',
                'specialties' => ['Farm-to-table', 'Seasonal menu', 'Superfoods']
            ]
        ];
        
        return [
            'restaurants' => $restaurants
        ];
    }
}
