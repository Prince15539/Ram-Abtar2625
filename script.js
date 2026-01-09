// Mobile Menu Toggle
function toggleMobileMenu() {
  const navbar = document.getElementById("navbar")
  const toggle = document.querySelector(".mobile-menu-toggle")

  navbar.classList.toggle("active")
  toggle.classList.toggle("active")
}

function closeMobileMenu() {
  const navbar = document.getElementById("navbar")
  const toggle = document.querySelector(".mobile-menu-toggle")

  navbar.classList.remove("active")
  toggle.classList.remove("active")
}

// Theme Toggle Functionality
function toggleTheme() {
  const body = document.body
  const themeIcon = document.getElementById("theme-icon")

  if (body.classList.contains("light-theme")) {
    body.classList.remove("light-theme")
    themeIcon.className = "fas fa-moon"
    localStorage.setItem("theme", "dark")
  } else {
    body.classList.add("light-theme")
    themeIcon.className = "fas fa-sun"
    localStorage.setItem("theme", "light")
  }
}

// Load saved theme and initialize
document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme")
  const themeIcon = document.getElementById("theme-icon")

  if (savedTheme === "light") {
    document.body.classList.add("light-theme")
    themeIcon.className = "fas fa-sun"
  } else {
    themeIcon.className = "fas fa-moon"
  }

  // Close mobile menu when clicking outside
  document.addEventListener("click", (e) => {
    const navbar = document.getElementById("navbar")
    const toggle = document.querySelector(".mobile-menu-toggle")

    if (!navbar.contains(e.target) && !toggle.contains(e.target)) {
      navbar.classList.remove("active")
      toggle.classList.remove("active")
    }
  })

  // Update scroll effect to work with both themes
  window.addEventListener("scroll", () => {
    const header = document.querySelector(".header")
    const isLightTheme = document.body.classList.contains("light-theme")

    if (window.scrollY > 100) {
      if (isLightTheme) {
        header.style.background = "rgba(255, 255, 255, 0.95)"
      } else {
        header.style.background = "rgba(31, 36, 45, 0.95)"
      }
    } else {
      if (isLightTheme) {
        header.style.background = "rgba(255, 255, 255, 0.9)"
      } else {
        header.style.background = "rgba(31, 36, 45, 0.8)"
      }
    }
  })

  // Animate skill bars when they come into view
  const observerOptions = {
    threshold: 0.3,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const skillBars = entry.target.querySelectorAll(".skill-progress")
        skillBars.forEach((bar, index) => {
          setTimeout(() => {
            const width = bar.getAttribute("data-width")
            bar.style.width = width
          }, index * 200)
        })
      }
    })
  }, observerOptions)

  const skillsSection = document.querySelector(".skills")
  if (skillsSection) {
    observer.observe(skillsSection)
  }

  // Smooth scrolling for navigation links
  const navLinks = document.querySelectorAll('.navbar a[href^="#"]')
  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()
      const targetId = this.getAttribute("href")
      const targetSection = document.querySelector(targetId)

      if (targetSection) {
        const headerHeight = document.querySelector(".header").offsetHeight
        const targetPosition = targetSection.offsetTop - headerHeight

        window.scrollTo({
          top: targetPosition,
          behavior: "smooth",
        })
      }
    })
  })

  // Contact form submission
  const contactForm = document.querySelector(".contact-form")
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault()

      const name = this.querySelector('input[type="text"]').value
      const email = this.querySelector('input[type="email"]').value
      const message = this.querySelector("textarea").value

      if (name && email && message) {
        // Show success message
        const successMsg = document.createElement("div")
        successMsg.style.cssText = `
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background: var(--main-color);
          color: var(--bg-color);
          padding: 20px 30px;
          border-radius: 10px;
          z-index: 1000;
          font-weight: 600;
        `
        successMsg.textContent = "Thank you for your message! I will get back to you soon."
        document.body.appendChild(successMsg)

        setTimeout(() => {
          document.body.removeChild(successMsg)
        }, 3000)

        this.reset()
      } else {
        alert("Please fill in all fields.")
      }
    })
  }

  // Enhanced typing effect for the role text
  const typingText = document.querySelector(".typing-text")
  const roles = ["Full Stack Developer", "Frontend Developer", "Backend Developer", "Web Developer", "Problem Solver"]
  let roleIndex = 0
  let charIndex = 0
  let isDeleting = false

  function typeEffect() {
    const currentRole = roles[roleIndex]

    if (isDeleting) {
      typingText.textContent = currentRole.substring(0, charIndex - 1)
      charIndex--
    } else {
      typingText.textContent = currentRole.substring(0, charIndex + 1)
      charIndex++
    }

    if (!isDeleting && charIndex === currentRole.length) {
      setTimeout(() => (isDeleting = true), 2000)
    } else if (isDeleting && charIndex === 0) {
      isDeleting = false
      roleIndex = (roleIndex + 1) % roles.length
    }

    const typingSpeed = isDeleting ? 50 : 100
    setTimeout(typeEffect, typingSpeed)
  }

  // Start typing effect
  setTimeout(typeEffect, 1000)

  // Enhanced parallax effect
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset
    const homeImg = document.querySelector(".home-img")
    const parallaxElements = document.querySelectorAll(".letter")

    if (homeImg && window.innerWidth > 768) {
      homeImg.style.transform = `translateY(${scrolled * 0.3}px)`
    }

    // Parallax effect for animated letters
    parallaxElements.forEach((letter, index) => {
      if (window.innerWidth > 768) {
        letter.style.transform = `translateY(${scrolled * 0.02 * (index + 1)}px)`
      }
    })
  })

  // Enhanced hover effects for social icons
  document.querySelectorAll(".home-sci a").forEach((icon) => {
    icon.addEventListener("mouseenter", function () {
      this.style.transform = "scale(1.3) translateY(-5px) rotate(360deg)"
    })

    icon.addEventListener("mouseleave", function () {
      this.style.transform = "scale(1) translateY(0) rotate(0deg)"
    })
  })

  // Add loading animation for skill bars
  const skillItems = document.querySelectorAll(".skill-item")
  skillItems.forEach((item, index) => {
    item.style.opacity = "0"
    item.style.transform = "translateY(20px)"
    item.style.transition = "all 0.5s ease"

    setTimeout(() => {
      item.style.opacity = "1"
      item.style.transform = "translateY(0)"
    }, index * 100)
  })
})

// Handle window resize
window.addEventListener("resize", () => {
  const navbar = document.getElementById("navbar")
  const toggle = document.querySelector(".mobile-menu-toggle")

  if (window.innerWidth > 768) {
    navbar.classList.remove("active")
    toggle.classList.remove("active")
  }
})

emailjs.sendForm('service_1grrlw4', 'template_hbwegoi', '#contact-form').then(
  (response) => {
    console.log('SUCCESS!', response.status, response.text);
  },
  (error) => {
    console.log('FAILED...', error);
  },
);



