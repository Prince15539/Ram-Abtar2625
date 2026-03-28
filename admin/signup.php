<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; 

$message = "";

if (isset($_POST['signup'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Check if passwords match
    if ($pass !== $confirm_pass) {
        $message = "<p style='color:red;'>Passwords do not match!</p>";
    } else {
        // 2. Check if username already exists
        $check = $conn->query("SELECT * FROM admin_users WHERE username='$user'");
        if ($check->num_rows > 0) {
            $message = "<p style='color:red;'>Username already taken!</p>";
        } else {
            // 3. HASH the password (Security)
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // 4. Insert into database
            $sql = "INSERT INTO admin_users (username, password) VALUES ('$user', '$hashed_pass')";
            
            if ($conn->query($sql)) {
                $message = "<p style='color:green;'>Registration successful! <a href='login.php'>Login here</a></p>";
            } else {
                $message = "<p style='color:red;'>Error: " . $conn->error . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Signup</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; background:#081b29; font-family: sans-serif; color: white;">

    <form method="POST" style="background:#112e42; padding:30px; border-radius:10px; width:350px; border: 1px solid #00abf0;">
        <h2 style="text-align: center; margin-bottom: 20px;">Create Admin</h2>
        
        <?php echo $message; ?>

        <label>Username (Email)</label>
        <input type="text" name="username" placeholder="e.g. mandalprince12@gmail.com" required 
               style="width:100%; margin: 10px 0 20px; padding:12px; box-sizing: border-box; border-radius: 5px; border: none;">
        
        <label>Password</label>
        <input type="password" name="password" placeholder="Create Password" required 
               style="width:100%; margin: 10px 0 20px; padding:12px; box-sizing: border-box; border-radius: 5px; border: none;">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required 
               style="width:100%; margin: 10px 0 20px; padding:12px; box-sizing: border-box; border-radius: 5px; border: none;">
        
        <button type="submit" name="signup" 
                style="width:100%; padding: 12px; background: #00abf0; border: none; border-radius: 25px; color: #081b29; font-weight: bold; cursor: pointer;">
            Register Admin
        </button>

        <p style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="login.php" style="color: #00abf0; text-decoration: none;">Login</a>
        </p>
    </form>

</body>
</html>