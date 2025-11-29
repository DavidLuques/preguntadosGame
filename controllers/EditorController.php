<?php

class EditorController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    private function checkEditor()
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'editor') {
            header("Location: /");
            exit();
        }
    }

    public function dashboard()
    {
        $this->checkEditor();
        $this->renderer->render("editor/dashboard");
    }

    public function questions()
    {
        $this->checkEditor();
        $questions = $this->model->getAllQuestions();
        $this->renderer->render("editor/questions_list", ["questions" => $questions]);
    }

    public function addQuestion()
    {
        $this->checkEditor();
        $categories = $this->model->getAllCategories();
        $this->renderer->render("editor/question_form", ["categories" => $categories, "action" => "saveQuestion"]);
    }

    public function saveQuestion()
    {
        $this->checkEditor();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $text = $_POST['question_text'];
            $categoryId = $_POST['category_id'];
            // $difficulty = $_POST['difficulty']; // Eliminado
            $answers = $_POST['answers']; // Array of 4 answers
            $correctIndex = $_POST['correct_answer']; // Index 0-3

            $questionId = $this->model->addQuestion($text, $categoryId);

            foreach ($answers as $index => $answerText) {
                $answerId = $this->model->addAnswer($answerText, $questionId);
                if ($index == $correctIndex) {
                    $this->model->setCorrectAnswer($questionId, $answerId);
                }
            }
            header("Location: /editor/questions");
        }
    }

    public function editQuestion()
    {
        $this->checkEditor();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $question = $this->model->getQuestionById($id);
            $answers = $this->model->getAnswersByQuestionId($id);
            $categories = $this->model->getAllCategories();
            
            // Mark correct answer
            foreach ($answers as &$answer) {
                if ($answer['answer_id'] == $question[0]['correct_answer_id']) {
                    $answer['is_correct'] = true;
                }
            }

            $this->renderer->render("editor/question_form", [
                "question" => $question[0],
                "answers" => $answers,
                "categories" => $categories,
                "action" => "updateQuestion",
                "is_edit" => true
            ]);
        }
    }

    public function updateQuestion()
    {
        $this->checkEditor();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['question_id'];
            $text = $_POST['question_text'];
            $categoryId = $_POST['category_id'];
            // $difficulty = $_POST['difficulty']; // Eliminado
            $answers = $_POST['answers']; // Array of answer texts
            $answerIds = $_POST['answer_ids']; // Array of answer IDs
            $correctAnswerId = $_POST['correct_answer']; // ID of correct answer

            $this->model->updateQuestion($id, $text, $categoryId, $correctAnswerId);

            for ($i = 0; $i < 4; $i++) {
                $this->model->updateAnswer($answerIds[$i], $answers[$i]);
            }

            header("Location: /editor/questions");
        }
    }

    public function deleteQuestion()
    {
        $this->checkEditor();
        if (isset($_GET['id'])) {
            $this->model->deleteQuestion($_GET['id']);
            header("Location: /editor/questions");
        }
    }

    public function categories()
    {
        $this->checkEditor();
        $categories = $this->model->getAllCategories();
        $this->renderer->render("editor/categories_list", ["categories" => $categories]);
    }

    public function addCategory()
    {
        $this->checkEditor();
        $this->renderer->render("editor/category_form", ["action" => "saveCategory"]);
    }

    public function saveCategory()
    {
        $this->checkEditor();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['category_name'];
            $this->model->addCategory($name);
            header("Location: /editor/categories");
        }
    }

    public function editCategory()
    {
        $this->checkEditor();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $category = $this->model->getCategoryById($id);
            $this->renderer->render("editor/category_form", [
                "category" => $category[0],
                "action" => "updateCategory",
                "is_edit" => true
            ]);
        }
    }

    public function updateCategory()
    {
        $this->checkEditor();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['category_id'];
            $name = $_POST['category_name'];
            $this->model->updateCategory($id, $name);
            header("Location: /editor/categories");
        }
    }

    public function deleteCategory()
    {
        $this->checkEditor();
        if (isset($_GET['id'])) {
            $this->model->deleteCategory($_GET['id']);
            header("Location: /editor/categories");
        }
    }

    public function reportedQuestions()
    {
        $this->checkEditor();
        $questions = $this->model->getReportedQuestions(3);
        
        // Process report reasons into arrays
        if ($questions) {
            foreach ($questions as &$question) {
                if (isset($question['report_reasons'])) {
                    $question['reasons_array'] = explode('|||', $question['report_reasons']);
                }
            }
        }
        
        $this->renderer->render("editor/reported_questions", ["questions" => $questions]);
    }

    public function dismissReports()
    {
        $this->checkEditor();
        if (isset($_GET['id'])) {
            $this->model->dismissReports($_GET['id']);
            header("Location: /editor/reportedQuestions");
        }
    }
}
