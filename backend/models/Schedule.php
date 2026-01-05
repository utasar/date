<?php

class Schedule {
    private $id;
    private $userId;
    private $date;
    private $tasks;
    private $suggestions;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->userId = $data['userId'] ?? null;
        $this->date = $data['date'] ?? date('Y-m-d');
        $this->tasks = $data['tasks'] ?? [];
        $this->suggestions = $data['suggestions'] ?? [];
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

    public function getTasks() {
        return $this->tasks;
    }

    public function getSuggestions() {
        return $this->suggestions;
    }

    public function addTask($task) {
        $this->tasks[] = $task;
    }

    public function addSuggestion($suggestion) {
        $this->suggestions[] = $suggestion;
    }
}
