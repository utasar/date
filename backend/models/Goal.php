<?php

class Goal {
    private $id;
    private $userId;
    private $title;
    private $description;
    private $targetDate;
    private $progress;
    private $steps;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->userId = $data['userId'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->targetDate = $data['targetDate'] ?? date('Y-m-d', strtotime('+30 days'));
        $this->progress = $data['progress'] ?? 0;
        $this->steps = $data['steps'] ?? [];
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getTargetDate() {
        return $this->targetDate;
    }

    public function getProgress() {
        return $this->progress;
    }

    public function getSteps() {
        return $this->steps;
    }

    public function updateProgress($progress) {
        $this->progress = max(0, min(100, $progress));
    }

    public function addStep($step) {
        $this->steps[] = $step;
    }
}
