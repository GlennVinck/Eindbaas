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
            <?php if(isset($_SESSION['id']) && \PrompTopia\Framework\User::isAdmin()){
                    echo '<li class="topnav-menu-item"><a href="admin.php">Admin</a></li>';
                }
            ?>
            <li class="topnav-menu-item"><a href="marketplace.php">Marketplace</a></li>
            <?php
            // check if user is logged in
            if (isset($_SESSION['id'])) {
                // display "My Profile" and "Log out" link
                echo '<li class="topnav-menu-item"><a href="profile.php">My Profile</a></li>';
                echo '<li class="topnav-menu-item"><a href="logout.php">Log Out</a></li>';
            } else {
                // display "Login" and "Register" links
                echo '<li class="topnav-menu-item"><a href="login.php">Log In</a></li>';
                echo '<li class="topnav-menu-item"><a href="register.php">Register</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>