<?php include('includes/header.php'); ?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Please Register</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="User Name" name="username" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Mobile" name="mobile" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Repeat Password" name="passwordr" type="password" value="">
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <a href="index.html" class="btn btn-lg btn-success btn-block">Register</a>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>