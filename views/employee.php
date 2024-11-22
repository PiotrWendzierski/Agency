<?php
include '../db.php';

//$query = "Select * FROM employees";
$query = "SELECT 
e.id AS employee_id,
e.name AS employee_name,
e.position AS employee_position,
c.id AS client_id,
c.name AS client_name,
c.email AS client_email
FROM 
employees e
LEFT JOIN 
client_employees ce ON e.id = ce.employee_id
LEFT JOIN 
clients c ON ce.client_id = c.id";

$stmt = $pdo->query($query);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>Employees with their clients</h1>
    <table class="table">

            <tbody>
            <?php 
                $current_employee = null;
                $has_clients = false;  
            
                foreach ($employees as $row):
                    if ($current_employee !== $row['employee_id']) 
                    {
                        if ($current_employee !== null) 
                        {
                            if (!$has_clients) 
                            {
                                echo '<p><strong>No clients yet :).</strong></p>';
                            }
                            echo '</tbody></table>';  
                        }
                        $current_employee = $row['employee_id'];
                        $has_clients = false; 
                        echo "<h3>Employee: {$row['employee_name']} ({$row['employee_position']})</h3>";
                        echo '<table class="table">';
                        echo '<thead><tr><th>Client Name</th><th>Client Email</th></tr></thead><tbody>';
                    }
                    if ($row['client_id']) 
                    {
                        $has_clients = true;
                        echo "<tr>
                                <td>{$row['client_name']}</td>
                                <td>{$row['client_email']}</td>
                              </tr>";
                    }
                endforeach;
                if (!$has_clients) {
                    echo '<p><strong>No clients yet :)</strong></p>';
                }
                ?>
            </tbody>
        </table>
        
    </div>
    </body>
</html>