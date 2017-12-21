<?php 
$logInOut = (isset($_SESSION['user'])? '<li style="color: #eeeeee;"><a href="account.php">Welcome: '.$_SESSION['user'].'</a></li><li><a href="logout.php" data-toggle="modal" data-target="#login-modal">Logout</a></li>' : '<li><a href="#" data-toggle="modal" data-target="#login-modal">Login</a></li>');
echo'
    <div id="top">
        <div class="container">
            <div class="col-md-6 offer" data-animate="fadeInDown">
            </div>
            <div class="col-md-6" data-animate="fadeInDown">
                <ul class="menu">
                    '. $logInOut  .'
                    <li><a href="contact.php">Contact</a>
                    </li>
                    <li><a href="#">Recently viewed</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
            <div class="modal-dialog modal-sm">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="Login">Customer login</h4>
                    </div>
                    <div class="modal-body">
                        <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">
                            <div class="form-group">
                                <input type="text" name="user" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" name="pass" placeholder="password">
                            </div>

                            <p class="text-center">
                                <input type="submit">
                            </p>

                        </form>

                        <p class="text-center text-muted">Not registered yet?</p>
                        <p class="text-center text-muted"><a href="register.html"><strong>Register now</strong></a>! It is easy and done in 1&nbsp;minute and gives you access to special discounts and much more!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>';
?>
