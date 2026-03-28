// AJAX Form Submission
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button');
    btn.innerText = "Sending...";
    btn.disabled = true;

    fetch('process.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') this.reset();
    })
    .catch(() => alert("Error connecting to server."))
    .finally(() => {
        btn.innerText = "Send Message";
        btn.disabled = false;
    });
});

// Theme Toggle
function toggleTheme() {
    document.body.classList.toggle('light-theme');
    const icon = document.getElementById('theme-icon');
    icon.classList.toggle('fa-sun');
}