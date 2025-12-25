<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$page_title = "Концерты";
require_once 'includes/header.php';
require_once 'config/database.php';

$concerts = [];

try {
    $stmt = $pdo->query("SELECT * FROM concerts ORDER BY date DESC");
    $concerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки концертов: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление концертами</h1>
    
    <div class="mb-3">
        <a href="concerts_form.php?action=add" class="btn btn-success">Добавить концерт</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Дата</th>
                <th>Артист</th>
                <th>Площадка</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($concerts as $concert): ?>
                <tr>
                    <td><?php echo $concert['id']; ?></td>
                    <td><?php echo htmlspecialchars($concert['title']); ?></td>
                    <td><?php echo date('d.m.Y H:i', strtotime($concert['date'])); ?></td>
                    <td><?php echo htmlspecialchars($concert['artist_id']); ?></td>
                    <td><?php echo htmlspecialchars($concert['venue_id']); ?></td>
                    <td>
                        <a href="concerts_form.php?action=edit&id=<?php echo $concert['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <a href="concerts_form.php?action=delete&id=<?php echo $concert['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>