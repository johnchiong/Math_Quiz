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