<?php

class EditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getAllQuestions()
    {
        return $this->database->query("SELECT q.*, c.category_name FROM question q LEFT JOIN category c ON q.category_id = c.category_id");
    }

    public function getQuestionById($id)
    {
        return $this->database->query("SELECT * FROM question WHERE question_id = '$id'");
    }

    public function addQuestion($text, $categoryId)
    {
        $date = date('Y-m-d H:i:s');
        // Por defecto la dificultad es 'Principiante'
        $difficulty = 'Principiante';
        $sql = "INSERT INTO question (question_text, question_date, category_id, status, difficulty_level, view_count, correct_answer_count) 
                VALUES ('$text', '$date', '$categoryId', 'activa', '$difficulty', 0, 0)";
        $this->database->query($sql);
        return $this->database->insertId();
    }

    public function updateQuestion($id, $text, $categoryId, $correctAnswerId)
    {
        // No actualizamos la dificultad aquí, se calcula automáticamente
        $sql = "UPDATE question SET question_text = '$text', category_id = '$categoryId', correct_answer_id = '$correctAnswerId' WHERE question_id = '$id'";
        $this->database->query($sql);
    }

    public function deleteQuestion($id)
    {
        // First delete answers
        $this->database->query("DELETE FROM answer WHERE question_id = '$id'");
        // Then delete question
        $this->database->query("DELETE FROM question WHERE question_id = '$id'");
    }

    public function getAllCategories()
    {
        return $this->database->query("SELECT * FROM category");
    }

    public function getCategoryById($id)
    {
        return $this->database->query("SELECT * FROM category WHERE category_id = '$id'");
    }

    public function addCategory($name)
    {
        $sql = "INSERT INTO category (category_name) VALUES ('$name')";
        $this->database->query($sql);
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE category SET category_name = '$name' WHERE category_id = '$id'";
        $this->database->query($sql);
    }

    public function deleteCategory($id)
    {
        $this->database->query("DELETE FROM category WHERE category_id = '$id'");
    }

    public function getAnswersByQuestionId($id)
    {
        return $this->database->query("SELECT * FROM answer WHERE question_id = '$id'");
    }

    public function addAnswer($text, $questionId)
    {
        $sql = "INSERT INTO answer (answer_text, question_id) VALUES ('$text', '$questionId')";
        $this->database->query($sql);
        return $this->database->insertId();
    }
    
    public function updateAnswer($id, $text) {
        $sql = "UPDATE answer SET answer_text = '$text' WHERE answer_id = '$id'";
        $this->database->query($sql);
    }

    public function setCorrectAnswer($questionId, $answerId) {
        $sql = "UPDATE question SET correct_answer_id = '$answerId' WHERE question_id = '$questionId'";
        $this->database->query($sql);
    }

    public function getReportedQuestions($minReports = 3)
    {
        $sql = "SELECT q.*, c.category_name, COUNT(r.report_id) as report_count,
                GROUP_CONCAT(r.reason SEPARATOR '|||') as report_reasons
                FROM question q 
                JOIN report r ON q.question_id = r.question_id 
                LEFT JOIN category c ON q.category_id = c.category_id
                GROUP BY q.question_id 
                HAVING report_count > $minReports";
        return $this->database->query($sql);
    }

    public function dismissReports($questionId)
    {
        $this->database->query("DELETE FROM report WHERE question_id = '$questionId'");
    }

    public function getSuggestedQuestions()
    {
        $sql = "SELECT q.*, c.category_name, q.question_date as created_at
                FROM question q 
                LEFT JOIN category c ON q.category_id = c.category_id 
                WHERE q.status = 'sugerida' 
                ORDER BY q.question_id DESC";
        return $this->database->query($sql);
    }

    public function updateQuestionStatus($questionId, $status)
    {
        $status = $this->database->getConnection()->real_escape_string($status);
        $sql = "UPDATE question SET status = '$status' WHERE question_id = '$questionId'";
        $this->database->query($sql);
    }
}
