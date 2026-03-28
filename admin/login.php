<?php 
// 1. Enable error reporting to catch any issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Include database connection
include 'db.php'; 

// 3. Redirect to dashboard if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

// 4. Handle Login Form Submission
if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // Check if user exists
    $res = $conn->query("SELECT * FROM admin_users WHERE username='$user'");
    
    if ($res && $res->num_rows > 0) {
        $admin = $res->fetch_assoc();
        
        // Verify the Hashed Password
        if (password_verify($pass, $admin['password'])) {
            // SUCCESS: Set Session Variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            
            // Redirect to Dashboard
            header("Location: index.php");
            exit();
        } else {
            $error = "Wrong password! Try again.";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | PRINCE</title>
    <!-- Connecting to your main CSS if you have one, or using internal styles -->
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #081b29; /* Match your portfolio background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #112e42;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 171, 240, 0.3);
            width: 350px;
            border: 2px solid #00abf0;
            text-align: center;
        }

        .login-container h2 {
            color: #fff;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .login-container h2 span {
            color: #00abf0;
        }

        .error-msg {
            color: #ff4d4d;
            background: rgba(255, 77, 77, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            color: #fff;
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #081b29;
            color: #fff;
            box-sizing: border-box;
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            background: #00abf0;
            color: #081b29;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            box-shadow: 0 0 15px #00abf0;
            background: #fff;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #00abf0;
            text-decoration: none;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin <span>Login</span></h2>

        <?php if($error != ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="input-group">
                <label>Username / Email</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <a href="../index.php" class="back-link">← Back to Portfolio</a>
        <a href="signup.php" class="back-link">Create an Account</a>
    </div>

</body>
</html>