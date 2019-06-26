<?php include('includes/header.php'); ?>
<?php include('includes/navigation.php'); ?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Profie
                </div>
                <div class="panel-body">
                    <form method="post">
                    <div class="row">
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <input class="form-control" placeholder="First Name">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="E-Mail">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Mobile">
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="Gender" value="male" checked="">Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="female">Female
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option>Select Age</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="file">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" placeholder="Bio"></textarea>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="FaceBook Profile">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Twitter Profile">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Linkedin Profile">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-rss" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Blog">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-globe" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" placeholder="Website">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Repeat Password">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm Present Password">
                                </div>

                                <input type="submit" class="btn btn-success btn-lg btn-block" value="Update Profile" />
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                </div>
            </form>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<?php include('includes/footer.php'); ?>