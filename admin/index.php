<?php 
include 'db.php';
include 'auth.php';

// Update Content Logic
if(isset($_POST['update_site'])) {
    foreach($_POST['content'] as $key => $value) {
        $val = mysqli_real_escape_string($conn, $value);
        $conn->query("UPDATE site_content SET meta_value='$val' WHERE meta_key='$key'");
    }
}

// Fetch Content
$res = $conn->query("SELECT * FROM site_content");
$content = [];
while($row = $res->fetch_assoc()) { $content[$row['meta_key']] = $row['meta_value']; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .nav { background: #333; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .nav a { color: white; margin-right: 15px; text-decoration: none; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
        button { background: #00abf0; color: white; border: none; padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">General Settings</a>
        <a href="skills.php">Manage Skills</a>
        <a href="projects.php">Manage Projects</a>
           <a href="socials.php">Social</a>

        <a href="logout.php" style="float:right; color:red;">Logout</a>
    </div>

    <div class="card">
        <h2>Site Content Settings</h2>
        <form method="POST">
            <label>Full Name:</label>
            <input type="text" name="content[home_name]" value="<?php echo $content['home_name']; ?>">
            
            <label>Job Title:</label>
            <input type="text" name="content[home_title]" value="<?php echo $content['home_title']; ?>">
            
            <label>About Me Text:</label>
            <textarea name="content[about_text]" rows="5"><?php echo $content['about_text']; ?></textarea>
            
            <button type="submit" name="update_site">Save Changes</button>
        </form>
    </div>
</body>
</html>