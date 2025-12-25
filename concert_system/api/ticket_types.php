<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$page_title = "Типы билетов";
require_once 'includes/header.php';
require_once 'config/database.php';

$ticket_types = [];

try {
    $stmt = $pdo->query("SELECT * FROM ticket_types ORDER BY concert_id, price");
    $ticket_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки типов билетов: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление типами билетов</h1>
    
    <div class="mb-3">
        <a href="ticket_types_form.php?action=add" class="btn btn-success">Добавить тип билета</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Концерт</th>
                <th>Тип</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ticket_types as $type): ?>
                <tr>
                    <td><?php echo $type['id']; ?></td>
                    <td><?php echo htmlspecialchars($type['concert_id']); ?></td>
                    <td><?php echo htmlspecialchars($type['type_name']); ?></td>
                    <td><?php echo number_format($type['price'], 2, ',', ' '); ?> руб.</td>
                    <td><?php echo $type['quantity']; ?></td>
                    <td>
                        <a href="ticket_types_form.php?action=edit&id=<?php echo $type['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <a href="ticket_types_form.php?action=delete&id=<?php echo $type['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>