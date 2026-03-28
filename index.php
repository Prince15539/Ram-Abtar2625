<?php 
// 1. Database Connection
include 'admin/db.php'; 

// 2. Fetch Site Content (Name, Title, etc.)
$content = [];
$res = $conn->query("SELECT * FROM site_content");
if ($res) {
    while($row = $res->fetch_assoc()) { 
        $content[$row['meta_key']] = $row['meta_value']; 
    }
}

// Fallback values
$name  = isset($content['home_name'])  ? $content['home_name']  : "Ram Abtar Mandal";
$title = isset($content['home_title']) ? $content['home_title'] : "Full Stack Developer";
$about = isset($content['about_text']) ? $content['about_text'] : "I'm a passionate full stack developer...";

// Helper function to turn a name into animated spans
function animateName($n) {
    $chars = str_split($n);
    $output = "";
    foreach($chars as $char) {
        if($char == " ") $output .= '<span class="space"> </span>';
        else $output .= '<span class="letter">'.htmlspecialchars($char).'</span>';
    }
    return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?> | Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <span></span><span></span><span></span>
    </div>

    <header class="header">
        <a href="#" class="logo"><span class="logo-text">PRINCE</span></a>
        <nav class="navbar" id="navbar">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#skills">Skills</a>
            <a href="#projects">Projects</a>
            <a href="#contact">Contact</a>
            <a href="admin/login.php" style="color: #00abf0;">Login</a>
        </nav>
        <div class="theme-toggle" onclick="toggleTheme()">
            <i class="fas fa-moon" id="theme-icon"></i>
        </div>
    </header>

    <!-- HOME SECTION -->
    <section class="home" id="home">
        <div class="home-content">
            <h3>Hello, It's Me</h3>
            <h1 class="animated-name">
                <?php echo animateName($name); ?>
            </h1>
            <h3>And I'm a <span class="typing-text"><?php echo htmlspecialchars($title); ?></span></h3>
            <p><?php echo nl2br(htmlspecialchars($about)); ?></p>
            
            <div class="home-sci">
                <!-- You can also make these dynamic later -->
             
        
              
            </div>
            <a href="#contact" class="btn">Reach Me</a>
        </div>
        
       

    <!-- ABOUT SECTION -->
     <div class="home-img">
            <div class="img-container">
                <div class="lighting-effect"></div>
                <img src="photo.jpg" alt="Ram Abtar Mandal - Full Stack Developer">
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="about-content">
            <h2 class="heading">About <span>Me</span></h2>
            <h3>Full Stack Developer</h3>
            <p>I am a dedicated and enthusiastic full stack developer with expertise in both frontend and backend technologies. 
               I specialize in creating complete web applications from database design to user interface implementation. 
               My passion lies in building scalable, efficient, and user-friendly digital solutions.</p>
            
            <div class="about-info">
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">Ram Abtar Mandal</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">mandalramabtar@gmail.com</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">9805176795</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Location:</span>
                    <span class="info-value">Kathmandu, Nepal</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Specialization:</span>
                    <span class="info-value">Full Stack Development</span>
                </div>
            </div>
        </div>
    </section>


   
<section class="skills" id="skills">
    <h2 class="heading">My <span>Skills</span></h2>
    
    <div class="skills-container">
        <?php
        // 1. Define Categories to display
        $categories = ['Frontend Development', 'Backend Development'];

        foreach ($categories as $cat):
        ?>
            <div class="skill-category">
                <h3 class="category-title"><?php echo $cat; ?></h3>
                
                <?php
                // 2. Fetch skills from DB for this specific category
                // We use "LIKE" to match 'Frontend' or 'Backend'
                $clean_cat = (strpos($cat, 'Frontend') !== false) ? 'Frontend' : 'Backend';
                $skills_query = $conn->query("SELECT * FROM skills WHERE category = '$clean_cat'");

                if ($skills_query->num_rows > 0):
                    while ($skill = $skills_query->fetch_assoc()):
                        
                        // 3. Logic to pick the right icon based on name
                        $icon = "fas fa-code"; // Default
                        $name = strtolower($skill['skill_name']);
                        
                        if (strpos($name, 'html') !== false) $icon = "fab fa-html5";
                        elseif (strpos($name, 'css') !== false) $icon = "fab fa-css3-alt";
                        elseif (strpos($name, 'js') !== false || strpos($name, 'javascript') !== false) $icon = "fab fa-js-square";
                        elseif (strpos($name, 'react') !== false) $icon = "fab fa-react";
                        elseif (strpos($name, 'php') !== false) $icon = "fab fa-php";
                        elseif (strpos($name, 'python') !== false || strpos($name, 'django') !== false) $icon = "fab fa-python";
                        elseif (strpos($name, 'java') !== false) $icon = "fab fa-java";
                        elseif (strpos($name, 'sql') !== false || strpos($name, 'mysql') !== false) $icon = "fas fa-database";
                ?>
                        <div class="skill-item">
                            <div class="skill-header">
                                <div class="skill-info">
                                    <i class="<?php echo $icon; ?> skill-icon"></i>
                                    <span class="skill-name"><?php echo htmlspecialchars($skill['skill_name']); ?></span>
                                </div>
                                <span class="skill-percentage"><?php echo $skill['percentage']; ?>%</span>
                            </div>
                            <div class="skill-bar">
                                <!-- We use style="width: ..." to make the bar move -->
                                <div class="skill-progress" style="width: <?php echo $skill['percentage']; ?>%"></div>
                            </div>
                        </div>
                <?php 
                    endwhile; 
                else:
                    echo "<p style='color:#ccc; font-size:0.8rem;'>No $cat added yet.</p>";
                endif; 
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<!-- Combined CSS for the White Clean Card Style -->
<style>
    .projects {
        background: #f4f4f4; /* Light grey background for the section to make white cards pop */
        padding: 100px 10%;
    }

    .projects .heading {
        font-size: 3.5rem;
        text-align: center;
        margin-bottom: 5rem;
        color: #333;
    }

    .projects .heading span {
        color: #00abf0;
    }

    /* Grid Layout */
    .projects-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }

    /* Card Style - To match your image */
    .project-box {
        background: #ffffff; /* White Background */
        border: 1px solid #e0e0e0; /* Light border */
        border-radius: 8px; /* Softer rounded corners */
        overflow: hidden;
        transition: 0.3s ease;
        text-align: center; /* Centers all text */
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .project-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    /* Image Container */
    .project-img-container {
        width: 100%;
        height: 220px;
        border-bottom: 1px solid #eee;
        overflow: hidden;
    }

    .project-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures the screenshot fills the area perfectly */
        display: block;
    }

    /* Content Area */
    .project-content {
        padding: 25px 20px;
    }

    .project-content h3 {
        font-size: 1.6rem;
        color: #333; /* Dark text for title */
        margin-bottom: 8px;
        font-weight: 600;
    }

    /* Matches the "CMS" text in your image */
    .project-category {
        font-size: 1.1rem;
        color: #666; /* Grey text for category/description */
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        display: block;
    }

    /* Clean Button */
    .project-btn {
        display: inline-block;
        padding: 10px 25px;
        background: #00abf0;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 500;
        transition: 0.3s;
        margin-top: 10px;
    }

    .project-btn:hover {
        background: #0084ba;
    }
</style>

<!-- Projects Section Structure -->
<section class="projects" id="projects">
    <h2 class="heading">My <span>Projects</span></h2>

    <div class="projects-container">
        <?php
        $projects_query = $conn->query("SELECT * FROM projects ORDER BY id DESC");
        while ($project = $projects_query->fetch_assoc()):
        ?>
            <div class="project-box">
                <!-- Image Container -->
                <div class="project-img-container">
                    <a href="<?php echo $project['link']; ?>" target="_blank">
                        <img src="uploads/<?php echo $project['image']; ?>" alt="Project Image">
                    </a>
                </div>

                <!-- Content Container -->
                <div class="project-content">
                    <!-- Title -->
                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                    
                    <!-- Subtitle / Category (Using your description field) -->
                    <span class="project-category">
                        <?php echo htmlspecialchars($project['description']); ?>
                    </span>
                    
                    <!-- View Link -->
                    <a href="<?php echo $project['link']; ?>" target="_blank" class="project-btn">
                        View Details
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>
  

    <!-- CONTACT SECTION -->
     <section class="contact" id="contact">
        <h2 class="heading">Contact <span>Me</span></h2>
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>mandalramabtar@gmail.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Phone</h4>
                        <p>9805176795</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Location</h4>
                        <p>Kathmandu, Nepal</p>
                    </div>
                </div>
            </div>
            
            <form class="contact-form">
                <input type="text" placeholder="Your Name" required>
                <input type="number" placeholder="Your Mobile number." required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Your Message" rows="6" required></textarea>
                <button type="submit" onclick="sendMail()" class="btn">Send Message</button>
            </form>
        </div>
    </section>

   
    <script src="script.js"></script>
</body>
</html>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $name; ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
<!-- 1. CSS for the Sidebar -->
<style>
    .sticky-sidebar {
        position: fixed;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        z-index: 9999;
        display: flex;
        flex-direction: column;
    }

    .sticky-sidebar a {
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        text-decoration: none;
        font-size: 20px;
        transition: 0.3s;
    }

    .sticky-sidebar a:hover {
        width: 65px; /* Slides out slightly on hover */
        padding-right: 15px;
    }
</style>

<!-- 2. PHP to Display Sidebar -->
<div class="sticky-sidebar">
    <?php
    $sidebar_res = $conn->query("SELECT * FROM social_sidebar");
    while($item = $sidebar_res->fetch_assoc()):
    ?>
        <a href="<?php echo $item['link']; ?>" 
           target="_blank" 
           style="background: <?php echo $item['bg_color']; ?>;" 
           title="<?php echo $item['platform']; ?>">
            <i class="<?php echo $item['icon_class']; ?>"></i>
        </a>
    <?php endwhile; ?>
</div>