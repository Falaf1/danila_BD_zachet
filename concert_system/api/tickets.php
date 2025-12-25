<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$page_title = "Билеты";
require_once 'includes/header.php';
require_once 'config/database.php';

$tickets = [];

try {
    $stmt = $pdo->query("SELECT t.*, tt.type_name, c.title as concert_title FROM tickets t
                         LEFT JOIN ticket_types tt ON t.ticket_type_id = tt.id
                         LEFT JOIN concerts c ON tt.concert_id = c.id
                         ORDER BY t.purchase_date DESC");
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки билетов: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление билетами</h1>
    
    <div class="mb-3">
        <a href="tickets_form.php?action=add" class="btn btn-success">Добавить билет</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Концерт</th>
                <th>Тип билета</th>
                <th>Покупатель</th>
                <th>Дата покупки</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo $ticket['id']; ?></td>
                    <td><?php echo htmlspecialchars($ticket['concert_title']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['type_name']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['buyer_name']); ?></td>
                    <td><?php echo date('d.m.Y H:i', strtotime($ticket['purchase_date'])); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $ticket['status'] == 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo $ticket['status'] == 'active' ? 'Активен' : 'Использован'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="tickets_form.php?action=edit&id=<?php echo $ticket['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <a href="tickets_form.php?action=delete&id=<?php echo $ticket['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>