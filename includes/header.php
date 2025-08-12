<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Drop of Life - Blood Donation System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blood donation management system to save lives">
    <meta name="keywords" content="blood donation, donate blood, save lives, blood bank">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="header__brand">
            <h1 class="header__logo">
                <span class="header__logo-icon">❤️</span>
                TheDropOfLife
            </h1>
        </div>
        
        <nav class="header__nav">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="index.php" class="nav__link">Home</a>
                </li>
                <li class="nav__item">
                    <a href="camps.php" class="nav__link">Camps</a>
                </li>
                <li class="nav__item">
                    <a href="manage-camps.php" class="nav__link">Manage</a>
                </li>
                <li class="nav__item">
                    <a href="donor-profile.php" class="nav__link">Profile</a>
                </li>
                <li class="nav__item">
                    <a href="emergency.php" class="nav__link">Emergency</a>
                </li>
                <li class="nav__item">
                    <a href="become_a_doner.php" class="btn btn--primary btn--sm">Become A Donor</a>
                </li>
            </ul>
            
            <!-- Mobile menu button -->
            <button class="header__mobile-toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
            </button>
        </nav>
    </div>
</header>
<main class="main">
