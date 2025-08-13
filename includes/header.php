<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Drop of Life - Blood Donation System</title>
    <meta name="description" content="Blood donation management system to save lives">
    <meta name="keywords" content="blood donation, donate blood, save lives, blood bank">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="header__logo">🩸 TheDropOfLife</h1>
        
        <nav class="header__nav">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="index.php" class="nav__link">Home</a>
                </li>
                <li class="nav__item">
                    <a href="camps.php" class="nav__link">Blood Camps</a>
                </li>
                <li class="nav__item">
                    <a href="donors.php" class="nav__link">Donors</a>
                </li>
            </ul>
            
            <!-- Mobile menu button -->
            <button class="header__mobile-toggle" aria-label="Toggle navigation">
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
            </button>
        </nav>
    </div>
</header>

<script>
// Mobile menu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.header__mobile-toggle');
    const navList = document.querySelector('.nav__list');
    
    if (mobileToggle && navList) {
        mobileToggle.addEventListener('click', function() {
            navList.classList.toggle('nav__list--mobile-active');
        });
        
        // Close mobile menu when clicking on a nav link
        const navLinks = document.querySelectorAll('.nav__link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navList.classList.remove('nav__list--mobile-active');
            });
        });
    }
});
</script>
