<?php 
require_once('includes/connect.php');
include('check-login.php');
include('includes/header.php');
include('includes/navigation.php'); 
$userid = $_SESSION['id'];

?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Activity Log</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    User Activity 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Activity</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $actsql = "SELECT * FROM user_activity WHERE uid=?";
                                    $actresult = $db->prepare($actsql);
                                    $actresult->execute(array($userid));
                                    $actres = $actresult->fetchAll(PDO::FETCH_ASSOC);
                                    $i = 1;
                                    foreach ($actres as $activity) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $activity['activity']; ?></td>
                                    <td><?php echo $activity['created']; ?></td>
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Login Activity 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Logged In Time</th>
                                    <th>Logged Out Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $logsql = "SELECT * FROM login_log WHERE uid=?";
                                    $logresult = $db->prepare($logsql);
                                    $logresult->execute(array($userid));
                                    $logres = $logresult->fetchAll(PDO::FETCH_ASSOC);
                                    $i = 1;
                                    foreach ($logres as $log) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $log['loggedin']; ?></td>
                                    <td><?php echo $log['loggedout']; ?></td>
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