<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Mathematics</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Simple Mathematics</h1>

        <form action="quiz.php" method="POST">
            <div class="section settings">
                <h3>Settings</h3>

                <!-- Level Selection -->
                <div class="settings-group">
                    <h4>Level</h4>
                    <div>
                        <input type="radio" id="level1" name="level" value="1-10" checked>
                        <label for="level1">Level 1 (1-10)</label>
                    </div>
                    <div>
                        <input type="radio" id="level2" name="level" value="11-100">
                        <label for="level2">Level 2 (11-100)</label>
                    </div>
                    <div>
                        <input type="radio" id="custom-level" name="level" value="custom">
                        <label for="custom-level">Custom Level</label>
                        <input type="number" name="custom_min" placeholder="Min" min="1">
                        <input type="number" name="custom_max" placeholder="Max" min="1">
                    </div>
                </div>

                <!-- Operator Selection -->
                <div class="settings-group">
                    <h4>Operator</h4>
                    <div>
                        <input type="radio" id="addition" name="operator" value="+" checked>
                        <label for="addition">Addition</label>
                    </div>
                    <div>
                        <input type="radio" id="subtraction" name="operator" value="-">
                        <label for="subtraction">Subtraction</label>
                    </div>
                    <div>
                        <input type="radio" id="multiplication" name="operator" value="*">
                        <label for="multiplication">Multiplication</label>
                    </div>
                </div>

                <!-- Number of Items and Max Difference -->
                <div class="settings-inputs">
                    <label>Number of Items: 
                        <input type="number" name="number_of_items" value="10" min="1">
                    </label>
                    <label>Max Difference: 
                        <input type="number" name="max_difference" value="10" min="1">
                    </label>
                </div>

                <!-- Start Quiz -->
                <button type="submit" name="start_quiz">Start Quiz</button>
            </div>
        </form>
    </div>
</body>
</html>
