<?php 
include 'db.php';
include 'auth.php';

$message = "";
$edit_mode = false;
$edit_id = $edit_title = $edit_desc = $edit_link = "";

// --- 1. HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Optional: Get image name and delete physical file too
    $res = $conn->query("SELECT image FROM projects WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row['image'] != 'default.jpg') {
        @unlink("../uploads/" . $row['image']);
    }

    $conn->query("DELETE FROM projects WHERE id = $id");
    header("Location: projects.php?msg=Deleted Successfully");
    exit();
}

// --- 2. HANDLE EDIT (Fetching data) ---
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM projects WHERE id = $id");
    $data = $res->fetch_assoc();
    
    $edit_id = $data['id'];
    $edit_title = $data['title'];
    $edit_desc = $data['description'];
    $edit_link = $data['link'];
}

// --- 3. HANDLE ADD OR UPDATE ---
if (isset($_POST['save_project'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $id = $_POST['project_id'];

    // Handle Image Upload logic
    $image_query_part = "";
    if (!empty($_FILES['image']['name'])) {
        $file_name = time() . "_" . $_FILES['image']['name'];
        $target = "../uploads/" . $file_name;
        
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_query_part = ", image='$file_name'";
        }
    }

    if (!empty($id)) {
        // UPDATE
        $conn->query("UPDATE projects SET title='$title', description='$desc', link='$link' $image_query_part WHERE id=$id");
        $message = "Project Updated!";
    } else {
        // INSERT
        $img = (!empty($file_name)) ? $file_name : "default.jpg";
        $conn->query("INSERT INTO projects (title, description, link, image) VALUES ('$title', '$desc', '$link', '$img')");
        $message = "Project Added!";
    }
    header("Location: projects.php?msg=$message");
    exit();
}

// Fetch all projects for the table
$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Projects | Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { background: #081b29; color: white; font-family: sans-serif; padding: 40px; }
        .admin-container { max-width: 1000px; margin: auto; }
        .form-box { background: #112e42; padding: 25px; border-radius: 10px; border: 1px solid #00abf0; margin-bottom: 30px; }
        input, textarea { width: 100%; padding: 12px; margin: 10px 0; background: #081b29; color: white; border: 1px solid #00abf0; border-radius: 5px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; background: #112e42; margin-top: 20px; }
        th, td { border: 1px solid #00abf033; padding: 12px; text-align: left; }
        th { background: #00abf0; color: #081b29; }
        .thumb { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
        .btn-action { text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="admin-container">
    <a href="index.php" style="color: #00abf0; text-decoration: none;">&larr; Dashboard</a>
    <h1>Manage <span>Projects</span></h1>

    <?php if(isset($_GET['msg'])) echo "<p style='color: #00ff00;'>".$_GET['msg']."</p>"; ?>

    <!-- FORM SECTION -->
    <div class="form-box">
        <h3><?php echo $edit_mode ? "Edit Project" : "Add New Project"; ?></h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="project_id" value="<?php echo $edit_id; ?>">
            
            <label>Project Title</label>
            <input type="text" name="title" value="<?php echo $edit_title; ?>" required>
            
            <label>Description</label>
            <textarea name="description" rows="3" required><?php echo $edit_desc; ?></textarea>
            
            <label>Project Link (GitHub/Demo URL)</label>
            <input type="url" name="link" value="<?php echo $edit_link; ?>" required>
            
            <label>Project Image (Leave blank to keep current)</label>
            <input type="file" name="image" accept="image/*">
            
            <button type="submit" name="save_project" class="btn" style="background:#00abf0; border:none; padding:10px 20px; border-radius:20px; cursor:pointer; font-weight:bold;">
                <?php echo $edit_mode ? "Update Project" : "Add Project"; ?>
            </button>
            <?php if($edit_mode): ?>
                <a href="projects.php" style="color: #ccc; margin-left: 10px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- LIST SECTION -->
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $projects->fetch_assoc()): ?>
            <tr>
                <td><img src="../uploads/<?php echo $row['image']; ?>" class="thumb"></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo substr(htmlspecialchars($row['description']), 0, 40); ?>...</td>
                <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn-action" style="background:yellow; color:black;">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-action" style="background:red; color:white;" onclick="return confirm('Delete this project?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>