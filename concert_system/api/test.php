<?php
session_start();
$page_title = "Тестовая страница";
require_once 'includes/header.php';
?>

<div class="container">
    <h1>Тестовая страница</h1>
    
    <div class="alert alert-info">
        <h4>Информация о системе:</h4>
        <p>PHP версия: <?php echo phpversion(); ?></p>
        <p>Текущий пользователь: 
            <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Не авторизован'; ?>
        </p>
        <p>Время сервера: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Сессия</div>
                <div class="card-body">
                    <pre><?php print_r($_SESSION); ?></pre>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">GET параметры</div>
                <div class="card-body">
                    <pre><?php print_r($_GET); ?></pre>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h3>Быстрые ссылки:</h3>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="auth.php">Авторизация</a></li>
            <li><a href="concerts.php">Концерты</a></li>
            <li><a href="ticket_types.php">Типы билетов</a></li>
            <li><a href="test.php?param1=value1&param2=value2">Тест с параметрами</a></li>
        </ul>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>