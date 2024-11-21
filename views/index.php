<?php
include '../db.php';


$query = "
SELECT 
    c.id, 
    c.name AS client_name, 
    s.name AS subscription_name, 
    GROUP_CONCAT(e.name SEPARATOR ', ') AS employee_names,
    con.name AS contact_name,
    con.phone AS contact_phone
FROM 
    clients c
JOIN 
    client_subscriptions cs ON c.id = cs.client_id
JOIN    
    subscription s ON cs.subscription_id = s.id
JOIN 
    client_employees ce ON c.id = ce.client_id
JOIN 
    employees e ON ce.employee_id = e.id
JOIN 
    contacts con ON c.id = con.client_id
GROUP BY 
    c.id, c.name, s.name, con.name, con.phone
";

$stmt = $pdo->query($query);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klienci i Pracownicy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include "menu.html"; ?>
    <div class="container" >
        <h1>Clients list</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Client name</th>
                    <th>Subscription</th>
                    <th>Contact name</th>
                    <th>Contact telephone</th>
                    <th>Protectors</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($clients as $client): ?>
                <tr>
                    <td><?= $client['client_name'] ?></td>
                    <td><?= $client['subscription_name'] ?></td>
                    <td><?= $client['contact_name']?></td>
                    <td><?= $client['contact_phone']?></td>
                    <td><?= $client['employee_names'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>
