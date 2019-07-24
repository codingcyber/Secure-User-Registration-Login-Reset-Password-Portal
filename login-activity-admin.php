<?php 
require_once('includes/connect.php');
require_once('check-admin.php');
include('includes/header.php');
include('includes/navigation.php');
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">User Login Activity Log</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    User Login Activity 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Log In Time</th>
                                    <th>Log Out Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $actsql = "SELECT ll.loggedin, ll.loggedout, ui.fname, ui.lname FROM login_log ll JOIN user_info ui WHERE ui.uid=ll.uid";
                                    $actresult = $db->prepare($actsql);
                                    $actresult->execute();
                                    $actres = $actresult->fetchAll(PDO::FETCH_ASSOC);
                                    $i = 0;
                                    foreach ($actres as $activity) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $activity['fname'] . " " . $activity['lname']; ?></td>
                                    <td><?php echo $activity['loggedin']; ?></td>
                                    <td><?php echo $activity['loggedout']; ?></td>
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<?php include('includes/footer.php'); ?>