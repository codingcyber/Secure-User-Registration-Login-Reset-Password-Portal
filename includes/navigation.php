<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Login Portal Admin</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="edit-profile.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <!-- <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li> -->
                <li class="divider"></li>
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <li>
                    <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-user fa-fw"></i> Edit Proile<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="edit-profile.php">Edit Profile</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <?php
                    $uid = $_SESSION['id'];
                    $sql = "SELECT * FROM users WHERE id=?";
                    //$sql .= " AND role='admin'";
                    $result = $db->prepare($sql);
                    $result->execute(array($uid));
                    $res = $result->fetch(PDO::FETCH_ASSOC);
                    // check count
                    if($res['role'] == 'admin'){
                ?>
                <li>
                    <a href="#"><i class="fa fa-user fa-fw"></i> Admin<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="users.php">Users</a>
                        </li>
                        <li>
                            <a href="login-activity-admin.php">Login Activity</a>
                        </li>
                        <li>
                            <a href="user-activity-admin.php">User Activity</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <?php } ?>
                <li>
                    <a href="activity.php"><i class="fa fa-dashboard fa-fw"></i> My Activity</a>
                </li>
                <li>
                    <a href="permissions.php"><i class="fa fa-dashboard fa-fw"></i> My Permissions</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>