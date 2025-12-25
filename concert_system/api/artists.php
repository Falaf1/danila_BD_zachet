<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$page_title = "Артисты";
require_once 'includes/header.php';
require_once 'config/database.php';

$artists = [];

try {
    $stmt = $pdo->query("SELECT * FROM artists ORDER BY name");
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки артистов: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление артистами</h1>
    
    <div class="mb-3">
        <a href="artists_form.php?action=add" class="btn btn-success">Добавить артиста</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Жанр</th>
                <th>Страна</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($artists as $artist): ?>
                <tr>
                    <td><?php echo $artist['id']; ?></td>
                    <td><?php echo htmlspecialchars($artist['name']); ?></td>
                    <td><?php echo htmlspecialchars($artist['genre']); ?></td>
                    <td><?php echo htmlspecialchars($artist['country']); ?></td>
                    <td>
                        <a href="artists_form.php?action=edit&id=<?php echo $artist['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <a href="artists_form.php?action=delete&id=<?php echo $artist['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>