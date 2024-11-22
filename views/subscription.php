<?php
include "../db.php";
//pagination
$records_per_page = 1;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$query = "SELECT * FROM subscription LIMIT $records_per_page OFFSET $offset";
$stmt = $pdo->query($query);
$subs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count_query = "SELECT COUNT(*) AS total FROM subscription";
$count_stmt = $pdo->query($count_query);
$total_subscriptions = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_subscriptions / $records_per_page);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "menu.html"; ?>
    <div class="container" >
        <h1>Contacts info</h1>
        <table class="table">
        <thead>
        <tr>
            <th>Subscription name</th>
            <th>Subscription description</th>
            <th>Price ($/month)</th>
        </tr>
        </thead>
        <tbody>
                <?php 
                foreach ($subs as $sub): ?>
                <tr>
                <td><?= $sub['name']?></td>
                <td><?= $sub['description'] ?></td>
                <td><?= $sub['price'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    
</body>
</html>
