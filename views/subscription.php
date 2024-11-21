<?php
include "../db.php";
$query = "SELECT * FROM subscription";
$stmt = $pdo->query($query);
$subs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    </div>
    
</body>
</html>
