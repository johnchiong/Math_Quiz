<?php
session_start();

// Helper functions
function calculate_answer($num1, $num2, $operator) {
    switch ($operator) {
        case '+': return $num1 + $num2;
        case '-': return $num1 - $num2;
        case '*': return $num1 * $num2;
    }
    return 0;
}

function generate_choices($correct, $max_difference) {
    $choices = [$correct];
    while (count($choices) < 4) {
        $choice = $correct + rand(-$max_difference, $max_difference);
        if (!in_array($choice, $choices) && $choice >= 0) {
            $choices[] = $choice;
        }
    }
    shuffle($choices);
    return $choices;
}

// Initialize quiz if redirected from `index.php`
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_quiz'])) {
    $level = $_POST['level'];
    $operator = $_POST['operator'];
    $number_of_items = intval($_POST['number_of_items']);
    $max_difference = intval($_POST['max_difference']);

    // Determine number range
    if ($level === '1-10') {
        $min = 1;
        $max = 10;
    } elseif ($level === '11-100') {
        $min = 11;
        $max = 100;
    } elseif ($level === 'custom') {
        $min = intval($_POST['custom_min']);
        $max = intval($_POST['custom_max']);
    } else {
        die("Invalid level setting!");
    }

    // Create questions
    $_SESSION['questions'] = [];
    for ($i = 0; $i < $number_of_items; $i++) {
        $num1 = rand($min, $max);
        $num2 = rand($min, $max);
        $correct_answer = calculate_answer($num1, $num2, $operator);

        $_SESSION['questions'][] = [
            'num1' => $num1,
            'num2' => $num2,
            'operator' => $operator,
            'correct_answer' => $correct_answer,
            'choices' => generate_choices($correct_answer, $max_difference)
        ];
    }

    // Initialize quiz state
    $_SESSION['current_question'] = 0;
    $_SESSION['score'] = 0;
    $_SESSION['answered'] = false;

    // Redirect to prevent form resubmission
    header("Location: quiz.php");
    exit;
}

// Validate session
if (!isset($_SESSION['questions']) || !isset($_SESSION['current_question'])) {
    header("Location: index2.php");
    exit;
}

$current_question_index = $_SESSION['current_question'];
$questions = $_SESSION['questions'];

// End quiz
if ($current_question_index >= count($questions)) {
    $score = $_SESSION['score'];
    session_destroy();
    echo "<h1>Quiz Completed!</h1>";
    echo "<p>Your Score: $score / " . count($questions) . "</p>";
    echo '<a href="index2.php">Restart Quiz</a>';
    exit;
}

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer']) && !$_SESSION['answered']) {
        $user_answer = intval($_POST['answer']);
        $current_question = $questions[$current_question_index];

        if ($user_answer === $current_question['correct_answer']) {
            $_SESSION['score']++;
            $_SESSION['remarks'] = "Correct!";
        } else {
            $_SESSION['remarks'] = "Wrong! The correct answer was " . $current_question['correct_answer'];
        }

        $_SESSION['answered'] = true;
    } elseif (isset($_POST['next'])) {
        if ($_SESSION['answered']) {
            $_SESSION['current_question']++;
            $_SESSION['answered'] = false;
            $_SESSION['remarks'] = null;
        }
        header("Location: quiz.php");
        exit;
    } elseif (isset($_POST['end'])) {
        header("Location: index2.php");
        session_destroy();
        exit;
    }
}
