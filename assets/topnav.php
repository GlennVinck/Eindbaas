<nav>
    <div class="topnav">
        <ul class="topnav-title" onclick="location.href='index.php';">
            <li class="website-title">
                <img class="logo" src="images/Logo.png" alt="logo">
                <span class="website-name">PrompTopia</span>
            </li>
        </ul>
		<div class="search-bar">
			<input class="search-input" type="text" placeholder="Search Prompts">
			<button class="search-btn" type="submit">
                <img class="search-icon" src="images/search.svg" alt="search">
            </button>
        </div>
        <ul class="topnav-menu">
            <li class="topnav-menu-item"><a href="marketplace.php">Marketplace</a></li>
            <?php
            // check if user is logged in
            if (isset($_SESSION['username'])) {
                // display "My Profile" link
                echo '<li class="topnav-menu-item"><a href="profile.php">My Profile</a></li>';
            } else {
                // display "Login" and "Register" links
                echo '<li class="topnav-menu-item"><a href="login.php">Login</a></li>';
                echo '<li class="topnav-menu-item"><a href="register.php">Register</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>