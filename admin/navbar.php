<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><i class="fa fa-paw"></i> </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><?php echo TITLE_ENG; ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu text-center">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo PATH_UPLOAD . $uprofile['picture']; ?>"
                             onerror="this.src='../dist/img/user2-160x160.jpg'"
                             class="user-image">
                        <span class="hidden-xs"><?php echo $uprofile['name']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo PATH_UPLOAD . $uprofile['picture']; ?>"
                                 onerror="this.src='../dist/img/user2-160x160.jpg'" class="img-circle" alt="User Image">
                            <p class="text-center" style="margin: 0"><?php echo $uprofile['name']; ?></p>
                            <div class="text-left">
                                <small class="text-white">ระดับพนักงาน : <?php echo $uprofile['level_name']; ?></small>
                                <br>
                                <small class="text-white">อีเมล์ : <?php echo $uprofile['email']; ?></small>
                                <br>
                                <small class="text-white">เบอร์โทรศัพท์ : <?php echo $uprofile['tel']; ?></small>
                            </div>
                        </li>
                        <!-- Menu Body -->

                        <!-- Menu Footer-->
                        <li class="user-footer">

                            <div class="pull-right">
                                <!--                                <a href="../index.php" class="btn btn-default btn-flat">หน้าหลัก</a>-->
                                <a href="logout.php" class="btn btn-default btn-flat">ออกจากระบบ</a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>