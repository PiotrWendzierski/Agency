<?php
include "../db.php";
//pagination
$records_per_page = 1;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$query = "SELECT 
con.name AS name, 
con.phone AS phone, 
cl.name AS company
FROM 
contacts con
JOIN 
clients cl ON con.client_id = cl.id
LIMIT $records_per_page OFFSET $offset";

$stmt = $pdo->query($query);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count_query = "SELECT COUNT(*) AS total FROM contacts";
$count_stmt = $pdo->query($count_query);
$total_contacts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_contacts / $records_per_page);

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
