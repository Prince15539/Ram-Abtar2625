<?php 
include 'db.php';
include 'auth.php';

$edit_mode = false;
$edit_id = $edit_platform = $edit_link = $edit_icon = $edit_color = "";

// --- 1. HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM social_sidebar WHERE id=$id");
    header("Location: social_sidebar.php"); exit();
}

// --- 2. HANDLE EDIT FETCH (When you click 'Edit' link) ---
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM social_sidebar WHERE id = $id");
    $data = $res->fetch_assoc();
    
    $edit_id = $data['id'];
    $edit_platform = $data['platform'];
    $edit_link = $data['link'];
    $edit_icon = $data['icon_class'];
    $edit_color = $data['bg_color'];
}

// --- 3. HANDLE SAVE (Add OR Update) ---
if (isset($_POST['save_sidebar'])) {
    $id = $_POST['id'];
    $p = mysqli_real_escape_string($conn, $_POST['platform']);
    $l = mysqli_real_escape_string($conn, $_POST['link']);
    $i = mysqli_real_escape_string($conn, $_POST['icon_class']);
    $c = mysqli_real_escape_string($conn, $_POST['bg_color']);

    if (!empty($id)) {
        // UPDATE existing
        $conn->query("UPDATE social_sidebar SET platform='$p', link='$l', icon_class='$i', bg_color='$c' WHERE id=$id");
    } else {
        // INSERT new
        $conn->query("INSERT INTO social_sidebar (platform, link, icon_class, bg_color) VALUES ('$p', '$l', '$i', '$c')");
    }
    header("Location: social_sidebar.php"); exit();
}

$links = $conn->query("SELECT * FROM social_sidebar");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Sidebar</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #081b29; color: white; padding: 40px; font-family: sans-serif; }
        .form-box { background: #112e42; padding: 25px; border: 1px solid #00abf0; border-radius: 10px; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0 20px; background: #081b29; color: white; border: 1px solid #00abf0; border-radius: 5px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; background: #112e42; }
        th, td { border: 1px solid #00abf033; padding: 15px; text-align: left; }
        th { background: #00abf0; color: #081b29; }
        .btn-save { background: #00abf0; color: #081b29; border: none; padding: 12px 25px; border-radius: 30px; font-weight: bold; cursor: pointer; }
        .action-link { text-decoration: none; margin-right: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div style="max-width: 900px; margin: auto;">
        <a href="index.php" style="color: #00abf0; text-decoration: none;">&larr; Back to Dashboard</a>
        <h1>Manage <span>Sticky Sidebar</span></h1>

        <!-- THE FORM -->
        <div class="form-box">
            <h3><?php echo $edit_mode ? "Edit Sidebar Item" : "Add New Item"; ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Platform Name</label>
                        <input type="text" name="platform" value="<?php echo $edit_platform; ?>" placeholder="e.g. TikTok" required>
                    </div>
                    <div>
                        <label>Icon Class</label>
                        <input type="text" name="icon_class" value="<?php echo $edit_icon; ?>" placeholder="e.g. fab fa-tiktok" required>
                    </div>
                </div>

                <label>URL / Link</label>
                <input type="text" name="link" value="<?php echo $edit_link; ?>" placeholder="https://..." required>

                <label>Background Color (Hex or Gradient)</label>
                <input type="text" name="bg_color" value="<?php echo $edit_color; ?>" placeholder="#25D366" required>

                <button type="submit" name="save_sidebar" class="btn-save">
                    <?php echo $edit_mode ? "Update Item" : "Add to Sidebar"; ?>
                </button>

                <?php if($edit_mode): ?>
                    <a href="social_sidebar.php" style="color: #aaa; margin-left: 15px;">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- THE LIST -->
        <table>
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Platform</th>
                    <th>Color</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $links->fetch_assoc()): ?>
                <tr>
                    <td align="center">
                        <div style="width: 40px; height: 40px; background: <?php echo $row['bg_color']; ?>; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                            <i class="<?php echo $row['icon_class']; ?>"></i>
                        </div>
                    </td>
                    <td><?php echo $row['platform']; ?></td>
                    <td><code><?php echo $row['bg_color']; ?></code></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="action-link" style="color: yellow;">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="action-link" style="color: #ff4d4d;" onclick="return confirm('Delete this?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>