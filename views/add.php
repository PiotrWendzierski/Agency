<?php
//sesion start + connecting with db
session_start();
include '../db.php';
//get all employees
$query3 = "SELECT * FROM employees";
$stmt3 = $pdo->query($query3);
$employees = $stmt3->fetchAll(PDO::FETCH_ASSOC);
//get all subscriptions
$query4 = "SELECT * FROM subscription";
$stmt4 = $pdo->query($query4);
$subscriptions = $stmt4->fetchAll(PDO::FETCH_ASSOC);

//validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{   
    $good = true;
    $email=$_POST['email'];
    $name=$_POST['name'];

    //is email empty
    if($email == "") 
    {
        $good = false;
        $_SESSION['email'] = '<span style="color:red">Write your e-mail!</span></br>';    
    }

    //is this email in db yet?
    $query1 = "SELECT * FROM clients WHERE email = :email";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt1->execute();   
    $user = $stmt1->fetch(PDO::FETCH_ASSOC);
    if ($user)
    {
        $good = false;
        $_SESSION['email2'] = '<span style="color:red">Write another e-mail!</span></br>';    
    }

    //is this name in db yet?
    $query2 = "SELECT * FROM clients WHERE name = :name";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt2->execute();   
    $user = $stmt2->fetch(PDO::FETCH_ASSOC);
    if ($user)
    {
        $good = false;
        $_SESSION['name'] = '<span style="color:red">Write another name!</span></br>';    
    }
    
    //is protector choosen
    if(empty($_POST['employee_id']))
    {
        $good = false;
        $_SESSION['protector'] = '<span style="color:red">Choose your protector! At least 1</span></br>'; 
    }

    // is subscription choosen
    if($_POST['subscription'] == "Choose subscription")
    {
        $good = false;
        $_SESSION['subscription'] = '<span style="color:red">Choose your subscription!</span></br>'; 
    }
    
    //is contact name correct
    if($_POST['contact'] =="") 
    {
        $good = false;
        $_SESSION['contact'] = '<span style="color:red">Write name of your Contact Person!</span></br>'; 
    }

    //is contact name phone number ok
    if($_POST['contact_phone'] =="") 
    {
        $good = false;
        $_SESSION['contact_phone'] = '<span style="color:red">Write phone number  of your Contact Person!</span></br>'; 
    }

    //is contact number correct
    function isPhoneNumber($string) {
        return preg_match("/^[0-9+\-()\s]*$/", $string);
    }
    if(!isPhoneNumber($_POST['contact_phone']))
    {
        $good = false;
        $_SESSION['contact_phone2'] = '<span style="color:red">Write correct phone number!</span></br>';
    }
    
    //connecting with db and update tables if validation is ok
    if($good == true)
    {
        //addind to client table
        $phone = $_POST['phone'];
        $stmt5 = $pdo->prepare("INSERT INTO clients (name, email, phone_number) VALUES (?, ?, ?)");
        $stmt5->execute([$name, $email, $phone]);

        //get new client id
        $query5 = "SELECT id FROM clients WHERE name = :name";
        $stmt6 = $pdo->prepare($query5);
        $stmt6->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt6->execute();
        $client_id = $stmt6->fetchColumn();

        //adding to clients_employee table
        $selected_employees = $_POST['employee_id'];
        $stmt7 = $pdo->prepare("INSERT INTO client_employees(client_id, employee_id) VALUES(?,?)");
        foreach ($selected_employees as $employee_id) 
        {
            $stmt7->execute([$client_id, $employee_id]);
        }
        
        //adding to clients subscriptions table
        //geting today's and end of subscription date
        $start = date('Y-m-d');
        $end = new DateTime();
        $end->modify('+1 month');
        $end = $end->format('Y-m-d');  
        $subscription_id = $_POST['subscription'];
        $stmt8 = $pdo->prepare("INSERT INTO client_subscriptions(client_id, subscription_id, start_date, end_date) VALUES(?,?,?,?)");
        $stmt8->execute([$client_id, $subscription_id, $start, $end]);

        //adding to contacts table
        $contact = $_POST['contact'];
        $contact_phone = $_POST['contact_phone'];
        $stmt9 = $pdo->prepare("INSERT INTO contacts(client_id, name, phone) VALUES(?,?,?)");
        $stmt9->execute([$client_id, $contact, $contact_phone]);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Klienta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "menu.html"; ?>
    <div class="container" style = width:50%>
        <h1>Add new client</h1>
        <form action="add.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Company name</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <?php
                if(isset($_SESSION['name']))
			    {
				    echo $_SESSION['name'];
				    unset($_SESSION['name']);
			    }
                ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email">
                <?php
                if(isset($_SESSION['email']))
			    {
				    echo $_SESSION['email'];
				    unset($_SESSION['email']);
			    }
                if(isset($_SESSION['email2']))
			    {
				    echo $_SESSION['email2'];
				    unset($_SESSION['email2']);
			    }
                ?>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone-number</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="mb-3">
                <?php 
                foreach ($employees as $employee) 
                {
                    echo "<input type='checkbox' name='employee_id[]' value='" . $employee['id'] . "'>" . $employee['name'] . "</input><br>";
                }
                if(isset($_SESSION['protector']))
			    {
				    echo $_SESSION['protector'];
				    unset($_SESSION['protector']);
			    }
                ?>
            </div>
            <div class="mb-3">
                <select name="subscription">
                <option>Choose subscription</option>
                <?php 
                foreach ($subscriptions as $subscription) 
                {
                    echo "<option value='" . $subscription['id'] . "'>" . $subscription['name'] . "</option>";
                }
                ?>
                </select>
                <?php
                if(isset($_SESSION['subscription']))
			    {
				    echo "</br>".$_SESSION['subscription'];
				    unset($_SESSION['subscription']);
			    }
                ?>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Person Name</label>
                <input type="text" class="form-control" id="contact" name="contact">
                <?php
                if(isset($_SESSION['contact']))
			    {
				    echo $_SESSION['contact'];
				    unset($_SESSION['contact']);
			    }
                ?>
            </div>
            <div class="mb-3">
                <label for="contact_phone" class="form-label">Phone Number of Contact Person</label>
                <input type="text" class="form-control" id="contact_phone" name="contact_phone">
            </div>
            <?php
            if(isset($_SESSION['contact_phone']))
		    {
			    echo $_SESSION['contact_phone'];
			    unset($_SESSION['contact_phone']);
            }
            if(isset($_SESSION['contact_phone2']))
		    {
			    echo $_SESSION['contact_phone2'];
			    unset($_SESSION['contact_phone2']);
            }
            ?>
            <button type="submit" class="btn btn-primary">ADD</button>
        </form></br>
        <a href="index.php"><button type="button" class="btn btn-danger">Cancel</button></a>
    </div>
</body>
</html>
