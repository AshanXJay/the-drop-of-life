<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Donation System - TheDropOfLife</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Join TheDropOfLife - Blood donation system to save lives. Find camps, register as donor, and help in emergencies.">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <h1><a href="index.php">TheDropOfLife</a></h1>
            <span class="tagline">Every Drop Counts</span>
        </div>
        
        <nav class="main-nav">
            <ul>
                <li><a href="index.php" class="nav-link" data-page="home">🏠 Home</a></li>
                <li><a href="camps.php" class="nav-link" data-page="camps">📍 Camps</a></li>
                <li><a href="manage-camps.php" class="nav-link" data-page="manage">⚙️ Manage</a></li>
                <li><a href="emergency.php" class="nav-link" data-page="emergency">🚨 Emergency</a></li>
                <li><a href="become_a_doner.php" class="donor-btn">❤️ Become A Donor</a></li>
            </ul>
        </nav>
        
        <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    
    <div class="mobile-nav" id="mobileNav">
        <ul>
            <li><a href="index.php" onclick="closeMobileMenu()">🏠 Home</a></li>
            <li><a href="camps.php" onclick="closeMobileMenu()">📍 Find Camps</a></li>
            <li><a href="manage-camps.php" onclick="closeMobileMenu()">⚙️ Manage Camps</a></li>
            <li><a href="emergency.php" onclick="closeMobileMenu()">🚨 Emergency</a></li>
            <li><a href="become_a_doner.php" class="mobile-donor-btn" onclick="closeMobileMenu()">❤️ Become A Donor</a></li>
        </ul>
    </div>
</header>

<script>
// Mobile menu functionality
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    mobileNav.classList.toggle('active');
    toggle.classList.toggle('active');
    document.body.classList.toggle('mobile-menu-open');
}

function closeMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    mobileNav.classList.remove('active');
    toggle.classList.remove('active');
    document.body.classList.remove('mobile-menu-open');
}

// Active page highlighting
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
});

// Close mobile menu when clicking outside
document.addEventListener('click', function(e) {
    const mobileNav = document.getElementById('mobileNav');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (!mobileNav.contains(e.target) && !toggle.contains(e.target)) {
        closeMobileMenu();
    }
});
</script>

<main>
