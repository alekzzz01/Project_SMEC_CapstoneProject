 <?php 

 require 'db.php';

 
 if (isset($_GET['init']) && $_GET['init'] === 'true') {

    $hashed_password = password_hash('Password1', PASSWORD_BCRYPT);
    $query = "INSERT INTO users (email, name, password, role) VALUES ('student', 'student' ,'$hashed_password', 'student')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Admin user created successfully";
    } else {
        echo "Error creating admin user";
    }


 }
 
 
 
 
 ?>