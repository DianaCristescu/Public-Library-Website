</head>
<body>
    <nav class="menu">
        <div class="menu-toggle">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?> 
            <div class="user-card" id="menu-top">
                <img src="./resources/images/default_avatar.png" class="profile-pic">
                <h2><?= htmlspecialchars($userInfo['first_name']) ?> <?= htmlspecialchars($userInfo['last_name']) ?></h2>
                <h3><?= htmlspecialchars($userInfo['email']) ?></h3>
            </div>
        <?php else: ?>
            <div id="menu-top" class="logo-card">
                <img src="./resources/images/logo.png" class="logo">
                <a  href="/biblioteca/browse"><h2>Biblioteca</h2></a>
            </div>
        <?php endif; ?>
        <div id="menu-generic" class="menu-categories">
            <ul>
                <li><a href="/biblioteca/browse">
                    <i class="fa-solid fa-house"></i>
                    Browse
                </a></li>
            </ul>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div id="menu-user" class="menu-categories">
                <ul>
                    <li><a href="/biblioteca/schedule">
                        <i class="fa-solid fa-calendar"></i>
                        Schedule
                    </a></li>
                    <li><a href="/biblioteca/my_books">
                        <i class="fa-solid fa-book"></i>
                        My Books
                    </a></li>
                    <li><a href="/biblioteca/wishlist">
                        <i class="fa-solid fa-star"></i>
                        Wishlist
                    </a></li>
                </ul>
            </div>
            <div id="menu-utility" class="menu-categories">
                <ul>
                    <li><a href="/biblioteca/settings">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                    </a></li>
                    <li><a href="/biblioteca/help">
                        <i class="fa-solid fa-circle-question"></i>
                        Help
                    </a></li>
                    <li id="logout"><a href="/biblioteca/logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Log Out
                    </a></li>
                </ul>
            </div>
        <?php else: ?>
            <form action="./api/submit_login" method="POST" id="menu-user" class="menu-login-form">
                <h3>Email</h3>
                <input type="email" name="email">
                <div class="password-input">
                    <div>
                        <h3>Password</h3>
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                    <input type="password" name="password">
                </div>
                <input type="submit" value="Log In" id="menu-login-submit">
                <div class="other-login-options">
                    <p>OR</p>
                    <div class="google-sign-in-button">
                        <i class="fa-brands fa-google"></i>
                    </div>
                    <div class="apple-sign-in-button">
                        <i class="fa-brands fa-apple"></i>
                    </div>
                    <div class="facebook-sign-in-button">
                        <i class="fa-brands fa-facebook-f"></i>
                    </div>
                </div>
            </form>
            <div id="menu-utility" class="menu-categories">
                <ul>
                    <li><a href="/biblioteca/help">
                        <i class="fa-solid fa-circle-question"></i>
                        Help
                    </a></li>
                    <li id="signup"><a href="/biblioteca/signup">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Sign Up
                    </li></a>
                </ul>
            </div>
        <?php endif; ?>
    </nav>
    <main class="">