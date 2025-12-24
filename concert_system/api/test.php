<?php
// Простейший тест
echo "Hello World from PHP!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";

// Проверка, что файл выполняется
if (isset($_SERVER['REQUEST_METHOD'])) {
    echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
}
?>