<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$page_title = "Площадки";
require_once 'includes/header.php';
require_once 'config/database.php';

$venues = [];

try {
    $stmt = $pdo->query("SELECT * FROM venues ORDER BY name");
    $venues = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки площадок: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление площадками</h1>
    
    <div class="mb-3">
        <a href="venues_form.php?action=add" class="btn btn-success">Добавить площадку</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Адрес</th>
                <th>Вместимость</th>
                <th>Город</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($venues as $venue): ?>
                <tr>
                    <td><?php echo $venue['id']; ?></td>
                    <td><?php echo htmlspecialchars($venue['name']); ?></td>
                    <td><?php echo htmlspecialchars($venue['address']); ?></td>
                    <td><?php echo number_format($venue['capacity'], 0, ',', ' '); ?></td>
                    <td><?php echo htmlspecialchars($venue['city']); ?></td>
                    <td>
                        <a href="venues_form.php?action=edit&id=<?php echo $venue['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <a href="venues_form.php?action=delete&id=<?php echo $venue['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>