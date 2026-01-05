<?php

class User {
    private $id;
    private $name;
    private $email;
    private $location;
    private $healthGoals;
    private $activityLevel;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->location = $data['location'] ?? '';
        $this->healthGoals = $data['healthGoals'] ?? [];
        $this->activityLevel = $data['activityLevel'] ?? 'moderate';
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getHealthGoals() {
        return $this->healthGoals;
    }

    public function getActivityLevel() {
        return $this->activityLevel;
    }
}
