<?php
$pdo = new PDO("mysql:host=localhost;dbname=lesson4.2;charset=utf8", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$pdo->exec("SET NAMES utf8;");
$sql = "SELECT * FROM tasks";
if (!empty($_POST["description"])) {
    $description = ($_POST["description"]);
    $is_done = 0;
    $date_added = date("Y-m-d H:i:s");
    $sql = "INSERT INTO `tasks` (`id`, `description`, `is_done`, `date_added`) VALUES (NULL,'$description','$is_done','$date_added') ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    header( 'Location: ./lesson4.2.php');
}
if (!empty($_GET['id']) && !empty($_GET['exo'])) {
    if ($_GET['exo'] == 'delete') {
        $sql = "DELETE FROM `tasks` WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["{$_GET['id']}"]);
        header( 'Location: ./lesson4.2.php');
    }
    if ($_GET['exo'] == 'updvalue'){
        $sql = "UPDATE `tasks` SET is_done = 1 WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["{$_GET['id']}"]);
        header( 'Location: ./lesson4.2.php');

    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<body>
<style>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    table th {
        background: #eee;
    }
</style>
<h1>Список дел на сегодня</h1>
<div style="margin-bottom: 20px;">
    <form method="POST">
        <input type="text" name="description" placeholder="Описание задачи" value="">
        <input type="submit" name="save" value="Добавить">
    </form>
</div>
<table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th>Управление</th>
    </tr>
    <?php
    $sql = "SELECT * FROM `tasks`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
        foreach ($stmt as $obj) { ?>
        <tr>
            <td><?php echo htmlspecialchars($obj['description']); ?></td>
            <td><?php echo htmlspecialchars($obj['date_added']); ?></td>
            <td><?php if ($obj['is_done'] == 0) {
                    echo 'В процессе';
                } else {
                    echo 'Выполнено';
                } ?>
            </td>
            <td>
                <a href="?id=<?php echo $obj['id']; ?>&exo=updvalue">Выполнить</a>
                <a href="?id=<?php echo $obj['id']; ?>&exo=delete">Удалить</a>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>


