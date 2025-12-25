<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$page_title = "Заказы";
require_once 'includes/header.php';
require_once 'config/database.php';

$orders = [];

try {
    $stmt = $pdo->query("SELECT o.*, u.username, COUNT(oi.id) as items_count 
                         FROM orders o
                         LEFT JOIN users u ON o.user_id = u.id
                         LEFT JOIN order_items oi ON o.id = oi.order_id
                         GROUP BY o.id
                         ORDER BY o.order_date DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки заказов: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление заказами</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Дата заказа</th>
                <th>Кол-во билетов</th>
                <th>Общая сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo date('d.m.Y H:i', strtotime($order['order_date'])); ?></td>
                    <td><?php echo $order['items_count']; ?></td>
                    <td><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> руб.</td>
                    <td>
                        <span class="badge bg-<?php 
                            echo $order['status'] == 'completed' ? 'success' : 
                                 ($order['status'] == 'pending' ? 'warning' : 'secondary'); 
                        ?>">
                            <?php 
                                echo $order['status'] == 'completed' ? 'Завершен' : 
                                     ($order['status'] == 'pending' ? 'В обработке' : 'Отменен'); 
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="orders_view.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="orders_edit.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>