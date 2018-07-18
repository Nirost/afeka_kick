<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afeka KickStarter</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/app.css" />
</head>
<body>
<?php include('php/loginHeader.php')
?>

<div class="container">
    <div class="page-header">
        <h1>Afeka KickStarter Login</h1>
    </div>
    <div class="well bs-component">
        <form class="form-horizontal" method="post" action="php/loginLogic.php">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="loginEmail" id="inputEmail3" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="loginPassword" id="inputPassword3" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Remember me
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <p>Click <a href="newUser.php">here</a> to sign up.</p>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>