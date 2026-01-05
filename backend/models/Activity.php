<?php

class Activity {
    private $id;
    private $userId;
    private $date;
    private $type;
    private $duration;
    private $caloriesBurned;
    private $completed;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->userId = $data['userId'] ?? null;
        $this->date = $data['date'] ?? date('Y-m-d');
        $this->type = $data['type'] ?? '';
        $this->duration = $data['duration'] ?? 0;
        $this->caloriesBurned = $data['caloriesBurned'] ?? 0;
        $this->completed = $data['completed'] ?? false;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getDate() {
        return $this->date;
    }

    public function getType() {
        return $this->type;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function getCaloriesBurned() {
        return $this->caloriesBurned;
    }

    public function isCompleted() {
        return $this->completed;
    }

    public function markCompleted() {
        $this->completed = true;
    }
}
