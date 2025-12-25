<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: auth.php');
    exit();
}

$page_title = "Пользователи";
require_once 'includes/header.php';
require_once 'config/database.php';

$users = [];

try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Ошибка загрузки пользователей: " . $e->getMessage();
}
?>

<div class="container">
    <h1>Управление пользователями</h1>
    
    <div class="mb-3">
        <a href="users_form.php?action=add" class="btn btn-success">Добавить пользователя</a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя пользователя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                            <?php echo $user['role'] == 'admin' ? 'Администратор' : 'Пользователь'; ?>
                        </span>
                    </td>
                    <td><?php echo date('d.m.Y H:i', strtotime($user['created_at'])); ?></td>
                    <td>
                        <a href="users_form.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Редактировать</a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="users_form.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">Удалить</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>