<?php
include "../db.php";
$query = "SELECT 
con.name AS name, 
con.phone AS phone, 
cl.name AS company
FROM 
contacts con
JOIN 
clients cl ON con.client_id = cl.id";

$stmt = $pdo->query($query);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="container" >
        <h1>Contacts info</h1>
        <table class="table">
        <thead>
        <tr>
            <th>Contact Name</th>
            <th>Phone number</th>
            <th>Working for:</th>
        </tr>
        </thead>
        <tbody>
                <?php 
                foreach ($contacts as $contact): ?>
                <tr>
                <td><?= $contact['name']?></td>
                <td><?= $contact['phone'] ?></td>
                <td><?= $contact['company'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    
</body>
</html>
