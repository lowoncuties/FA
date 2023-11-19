<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && isset($_POST['complexity'])) {
        $password = $_POST['password'];
        $complexity = $_POST['complexity'];

        $correct_passwords = [
            0 => '5612',
            1 => '32276',
            2 => 'bzcd',
            3 => 'h4b2',
            4 => 'z+1c'
        ];

        if (array_key_exists($complexity, $correct_passwords)) {
            $input_password = $correct_passwords[$complexity];

            if ($password === $input_password) {
                echo "OK";
            } else {
                echo "KO";
            }
        } else {
            echo "Invalid complexity level.";
        }
    } else {
        echo "Missing password or complexity value.";
    }
}
?>
