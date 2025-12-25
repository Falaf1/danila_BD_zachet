<?php
session_start();
$page_title = "Главная";
require_once 'includes/header.php';
?>

<div class="container">
    <h1>Добро пожаловать в систему управления билетами</h1>
    <p>Используйте меню для навигации по разделам.</p>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="alert alert-success">
            Вы вошли как: <strong><?php echo $_SESSION['username']; ?></strong>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Пожалуйста, <a href="auth.php">войдите</a> для доступа ко всем функциям.
        </div>
    <?php endif; ?>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Концерты</h5>
                    <p class="card-text">Просмотр предстоящих концертов и мероприятий.</p>
                    <a href="concerts.php" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Билеты</h5>
                    <p class="card-text">Управление билетами и типами билетов.</p>
                    <a href="tickets.php" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Заказы</h5>
                    <p class="card-text">Просмотр и управление заказами билетов.</p>
                    <a href="orders.php" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>