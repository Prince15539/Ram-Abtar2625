<?php 
include 'db.php';
include 'auth.php';

$edit_mode = false;
$edit_id = $edit_name = $edit_perc = $edit_cat = "";
$edit_icon = "fas fa-code";

// 1. Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM skills WHERE id = $id");
    header("Location: skills.php"); exit();
}

// 2. Handle Edit Fetch
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM skills WHERE id = $id");
    $data = $res->fetch_assoc();
    $edit_id = $data['id'];
    $edit_name = $data['skill_name'];
    $edit_perc = $data['percentage'];
    $edit_cat = $data['category'];
    $edit_icon = $data['icon_class'];
}

// 3. Handle Save (Add or Update)
if (isset($_POST['save_skill'])) {
    $name = mysqli_real_escape_string($conn, $_POST['skill_name']);
    $perc = (int)$_POST['percentage'];
    $cat  = $_POST['category'];
    $icon = mysqli_real_escape_string($conn, $_POST['icon_class']);
    $id   = $_POST['skill_id'];

    if (!empty($id)) {
        $conn->query("UPDATE skills SET skill_name='$name', percentage='$perc', category='$cat', icon_class='$icon' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO skills (skill_name, percentage, category, icon_class) VALUES ('$name', '$perc', '$cat', '$icon')");
    }
    header("Location: skills.php"); exit();
}

$skills = $conn->query("SELECT * FROM skills ORDER BY category DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Skills</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #081b29; color: white; font-family: sans-serif; padding: 40px; }
        .form-box { background: #112e42; padding: 20px; border-radius: 10px; border: 1px solid #00abf0; margin-bottom: 30px; }
        input, select { padding: 10px; margin-bottom: 10px; width: 100%; box-sizing: border-box; background: #081b29; color: white; border: 1px solid #00abf0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; background: #112e42; }
        th, td { border: 1px solid #00abf033; padding: 12px; text-align: left; }
        th { background: #00abf0; color: #081b29; }
        .icon-preview { font-size: 1.5rem; color: #00abf0; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Manage <span>Skills & Icons</span></h1>

    <div class="form-box">
        <form method="POST">
            <input type="hidden" name="skill_id" value="<?php echo $edit_id; ?>">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div>
                    <label>Skill Name</label>
                    <input type="text" name="skill_name" value="<?php echo $edit_name; ?>" required>
                </div>
                <div>
                    <label>Icon Class (FontAwesome)</label>
                    <input type="text" name="icon_class" value="<?php echo $edit_icon; ?>" placeholder="fab fa-php">
                    <small style="color:#aaa;">Find icons at <a href="https://fontawesome.com/icons" target="_blank" style="color:#00abf0;">fontawesome.com</a></small>
                </div>
                <div>
                    <label>Percentage</label>
                    <input type="number" name="percentage" value="<?php echo $edit_perc; ?>" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category">
                        <option value="Frontend" <?php if($edit_cat=='Frontend') echo 'selected'; ?>>Frontend</option>
                        <option value="Backend" <?php if($edit_cat=='Backend') echo 'selected'; ?>>Backend</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="save_skill" class="btn" style="background:#00abf0; border:none; padding:10px 20px; border-radius:20px; cursor:pointer;">Save Skill</button>
        </form>
    </div>

    <table>
        <tr><th>Icon</th><th>Name</th><th>Category</th><th>Action</th></tr>
        <?php while($s = $skills->fetch_assoc()): ?>
        <tr>
            <td><i class="<?php echo $s['icon_class']; ?> icon-preview"></i></td>
            <td><?php echo $s['skill_name']; ?></td>
            <td><?php echo $s['category']; ?></td>
            <td>
                <a href="?edit=<?php echo $s['id']; ?>" style="color:yellow;">Edit</a> | 
                <a href="?delete=<?php echo $s['id']; ?>" style="color:red;" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

    <a href="index.php" style="float:right; color:red;">Logout</a>