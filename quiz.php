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
}