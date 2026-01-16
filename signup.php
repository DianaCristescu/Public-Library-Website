<?php include('fragments/head.php') ?>
<link rel="stylesheet" href="./style/signup.css">
    </head>
    <body>
        <main class="signup-page-layout">
            <div class="page-title logo-card">
                <img src="./resources/images/logo.png" class="logo-smaller">
                <a  href="/biblioteca/browse"><h1 class="title1">Biblioteca</h1></a>
            </div>
            <div class="signup">
                <form action="./api/submit_signup" method="POST" class="signup-form">
                    <div class="signup-form-input">
                        <p>First Name</p>
                        <input type="text" name="first_name">
                    </div>
                    <div class="signup-form-input">
                        <p>Last Name</p>
                        <input type="text" name="last_name">
                    </div>
                    <div class="signup-form-input">
                        <p>Email</p>
                        <input type="email" name="email">
                    </div>
                    <div class="signup-form-input password-input">
                        <div>
                            <h3>Password</h3>
                            <i class="fa-solid fa-eye-slash"></i>
                        </div>
                        <input type="password" name="password">
                    </div>
                    <div class="signup-form-input password-input">
                        <div>
                            <h3>Confirm Password</h3>
                            <i class="fa-solid fa-eye-slash"></i>
                        </div>
                        <input type="password" name="confirm_password">
                    </div>
                    <input type="submit" value="Sign Up" id="signup-submit">
                </form>
                <div class="other-signup">
                    <p>OR</p>
                    <div class="google-sign-in-button">
                        <i class="fa-brands fa-google"></i>
                        <p>Continue with Google</p>
                    </div>
                    <div class="apple-sign-in-button">
                        <i class="fa-brands fa-apple"></i>
                        <p>Continue with Apple</p>
                    </div>
                    <div class="facebook-sign-in-button">
                        <i class="fa-brands fa-facebook-f"></i>
                        <p>Continue with Facebook</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="no-footer">
        
        </footer>
        <script src="/biblioteca/javascript/password_show_hide.js"></script>
    </body>
</html>