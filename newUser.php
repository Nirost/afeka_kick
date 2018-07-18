<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afeka Book</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/app.css" />
</head>
<body>
<?php include('php/loginHeader.php')
?>

<div class="container">
    <div class="page-header">
        <h1>Welcome to Afeka Book</h1>
    </div>
    <div class="well bs-component">
        <form class="form-horizontal" action="php/newUserLogic.php" method="post" name="newUser">
            <fieldset>
                <legend>Registration</legend>
                <div class="form-group">
                    <label for="inputFirstName" class="col-lg-2 control-label">First Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="inputFirstName" name="regFirstName" placeholder="First Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputLastName" class="col-lg-2 control-label">Last Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="inputLastName" name="regLastName" placeholder="Last Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                        <input type="email" class="form-control" id="inputEmail" name="regEmail" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                        <input type="password" class="form-control" id="inputPassword" name="regPassword" placeholder="Password">
                    </div>
                    <div class="col-lg-2"></div>
                    <div class="col-lg-10">
                        <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Confirm Password">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="reset" class="btn btn-default">Cancel</button>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
</body>
</html>