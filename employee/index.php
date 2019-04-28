<?php
    function convertUrlQuery($query){
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    function logout($logoutUrl, $ticket, $localHost){
        echo "<script language=\"javascript\">";
        echo "alert(\"redirect to $logoutUrl\");
                window.location.href=\"$logoutUrl?page=$localHost&ticket=$ticket\";";
        echo "</script>";
        //header("Location: " . $loginUrl);
    }
    
    $userID = 100;
    session_start();
    var_dump($_SESSION);
    $servername = "localhost";
    $username = "root";
    $passwd = "root";
    $dbname = "EMS";
    $conn = new mysqli($servername, $username, $passwd, $dbname);
    if ($conn->connect_error) {
        echo "连接失败: " . $conn->connect_error;
    }
    $localHost = $_SERVER['HTTP_HOST'];
    $loginUrl = "https://www.login.com/login.php";
    $logoutUrl = "https://www.login.com/logout.php";
    $dispatchUrl = "https://www.login.com/dispatch.php";
    $param = $_SERVER['QUERY_STRING'];
    $params = convertUrlQuery($param);
    $ticket = $params["ticket"];
    $perm = '';
    $session_status = session_status();
    if($_SESSION['validate'] == true){
        //TODO add session message into page;
        $info = $_SESSION['info'];
        // $perm = $_SESSION['perm'];
        // $EID = $_SESSION['EID'];
        // $Ename = $_SESSION['Ename'];
    }
    else{
        if($ticket == null){  ///< ticket not exit;
            echo "session none & ticket = null";
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $loginUrl\");
                    window.location.href=\"$loginUrl?page=$localHost\";";
            echo "</script>";
            exit;
        }
        else{
            echo "session none & ticket = $ticket";
            echo "$param";
            echo "</br>";
            //echo $params['ticket'];
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $dispatchUrl\");
                    window.location.href=\"$dispatchUrl?page=$localHost&command=validate&ticket=$ticket\";";
            echo "</script>";
            //header("Location: " . $loginUrl);
            exit;
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carbon - Admin Template</title>
    <link rel="stylesheet" href="./com/vendor/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="./com/vendor/font-awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./com/css/styles.css">
    <script src="./com/vendor/vue/vue.min.js"></script>
    <style> 
        .class_1{ 
            display: none; 
            position: absolute; 
            top: 0%; 
            left: 0%; 
            width: 100%; 
            height: 100%; 
            background-color: black; 
            z-index:1001; 
            -moz-opacity: 0.8; 
            opacity:.80; 
            filter: alpha(opacity=88); 
        } 
        .class_2 { 
            display: none; 
            position: absolute; 
            top: 25%; 
            left: 25%; 
            width: 55%; 
            height: 55%; 
            padding: 20px; 
            border: 2px solid orange; 
            background-color: white; 
            z-index:1002; 
            overflow: auto; 
        } 

        .title_box{
            width:100%;
            overflow:hidden;
            display=flex;
        }
        .title_box input{
            width:100%;
            border:none;
        }
        .title_box select{
            border:none;
        }
        .title_box option{
            border:none;
        }
    </style> 
</head>
<body class="sidebar-fixed header-fixed">
<div class="page-wrapper">

    <div id = 'message' class = 'class_2'>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="title_box">
                            <span class="h4 d-block font-weight-normal mb-2">姓名</span>
                            <input class="font-weight-light" id='Ename' type='text' value="<?php echo $_SESSION['Ename']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">性别</span>
                            <select class="font-weight-light" id='Esex' disabled required>
                            <option value=1 <?php if($_SESSION['Esex']) echo 'selected'?>>男</option>
                            <option value=0 <?php if(!$_SESSION['Esex']) echo 'selected'?>>女</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">年龄</span>
                            <input class="font-weight-light" id='Eage' type='text' value="<?php echo $_SESSION['Eage']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">主管</span>
                            <input class="font-weight-light" id='MEname' type='text' value="<?php echo $_SESSION['MEname']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">部门</span>
                            <input class="font-weight-light" id='Dname' type='text' value="<?php echo $_SESSION['Dname']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">邮件</span>
                            <input class="font-weight-light" id='Eemail' type='text' value="<?php echo $_SESSION['Eemail']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">手机</span>
                            <input class="font-weight-light" id='Ephone' type='text' value="<?php echo $_SESSION['Ephone']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="postion:fixed; bottom:5px; float:right;">
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').readOnly=false;
                document.getElementById('Esex').disabled=false;
                document.getElementById('Eage').readOnly=false;
                document.getElementById('Eemail').readOnly=false;
                document.getElementById('Ephone').readOnly=false;
            ">修改</button>
            <button type='button' style="width:50px" onclick="
                if(confirm('确认修改么？')){
                    var Ename = document.getElementById('Ename').value;
                    var Esex = document.getElementById('Esex').value;
                    var Eage = document.getElementById('Eage').value;
                    var Eemail = document.getElementById('Eemail').value;
                    var Ephone = document.getElementById('Ephone').value;
                    sql = 'update employee set Ename = \'' + Ename + 
                    '\', Esex = ' + Esex + ', Eage = ' + Eage + 
                    ', Eemail = \'' + Eemail + '\', Ephone = \'' + 
                    Ephone + '\' where EID = \'<?php echo $_SESSION['EID']?>\'';
                    window.location.href = 'https:\\\\' + window.location.host + '\\dispatch.php?command=update&sql=' + sql;
                    document.getElementById('Ename').readOnly=true;
                    document.getElementById('Esex').disabled=true;
                    document.getElementById('Eage').readOnly=true;
                    document.getElementById('Eemail').readOnly=true;
                    document.getElementById('Ephone').readOnly=true;
                }
                else{

                }
            ">确认</button>
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').value = '<?php echo $_SESSION['Ename']?>';
                document.getElementById('Esex').value = '<?php echo $_SESSION['Esex']?>';
                document.getElementById('Eage').value = '<?php echo $_SESSION['Eage']?>';
                document.getElementById('Eemail').value = '<?php echo $_SESSION['Eemail']?>';
                document.getElementById('Ephone').value = '<?php echo $_SESSION['Ephpne']?>';
            ">取消</button>
            <button type='button' style="width:50px" onclick="document.getElementById('message').style.display='none'">退出</button>
        </div>
    </div>
    
    <nav class="navbar page-header">
        <a href="#" class="btn btn-link sidebar-mobile-toggle d-md-none mr-auto">
            <i class="fa fa-bars"></i>
        </a>

        <a class="navbar-brand" href="#">
            <img src="./imgs/logo.png" alt="logo">
        </a>

        <a href="#" class="btn btn-link sidebar-toggle d-md-down-none">
            <i class="fa fa-bars"></i>
        </a>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-md-down-none">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span class="badge badge-pill badge-danger">5</span>
                </a>
            </li>

            <li class="nav-item d-md-down-none">
                <a href="#">
                    <i class="fa fa-envelope-open"></i>
                    <span class="badge badge-pill badge-danger">5</span>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="./com/imgs/avatar-1.png" class="avatar avatar-sm" alt="logo">
                    <span class="small ml-1 d-md-down-none"><?php echo ''.$_SESSION['EID'].'@'.$_SESSION['Ename']?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header">Account</div>

                    <a href="#" class="dropdown-item">
                        <i class="fa fa-user"></i> Profile
                    </a>
                    <div class="dropdown-item" id='Messages' onclick="document.getElementById('message').style.display='block'" >
                        <i class="fa fa-envelope"></i> Messages
                    </div>

                    <div class="dropdown-header">Settings</div>

                    <a href="#" class="dropdown-item">
                        <i class="fa fa-bell"></i> Notifications
                    </a>

                    <a href="#" class="dropdown-item">
                        <i class="fa fa-wrench"></i> Settings
                    </a>

                    <a href="dispatch.php?page=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'&'.'command=logout';?>" class="dropdown-item">
                        <i class="fa fa-lock"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="main-container">
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-title">Navigation</li>

                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                            <i class="icon icon-speedometer"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <i class="icon icon-target"></i> Layouts <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="layouts-normal.html" class="nav-link">
                                    <i class="icon icon-target"></i> Normal
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="layouts-fixed-sidebar.html" class="nav-link">
                                    <i class="icon icon-target"></i> Fixed Sidebar
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="layouts-fixed-header.html" class="nav-link">
                                    <i class="icon icon-target"></i> Fixed Header
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="layouts-hidden-sidebar.html" class="nav-link">
                                    <i class="icon icon-target"></i> Hidden Sidebar
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <i class="icon icon-energy"></i> UI Kits <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="alerts.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Alerts
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="buttons.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Buttons
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="cards.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Cards
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="modals.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Modals
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="tabs.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Tabs
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="progress-bars.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Progress Bars
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="widgets.html" class="nav-link">
                                    <i class="icon icon-energy"></i> Widgets
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <i class="icon icon-graph"></i> Charts <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="chartjs.html" class="nav-link">
                                    <i class="icon icon-graph"></i> Chart.js
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="forms.html" class="nav-link">
                            <i class="icon icon-puzzle"></i> Forms
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="./com/tables.html" class="nav-link">
                            <i class="icon icon-grid"></i> Tables
                        </a>
                    </li>

                    <li class="nav-title">More</li>

                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <i class="icon icon-umbrella"></i> Pages <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="blank.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Blank Page
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="login.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Login
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="register.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Register
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="invoice.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Invoice
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="404.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> 404
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="500.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> 500
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="settings.html" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 d-block font-weight-normal mb-2">
                                        <?php
                                            $sql = "select count(EID) as Colleagues from belong group by DID having DID = ".$_SESSION['DID'].";";
                                            $result = $conn->query($sql);
                                            $Colleagues = '';
                                            if($result->num_rows > 0){
                                                $row = $result->fetch_assoc();
                                                $Colleagues = $row['Colleagues'];
                                            }
                                            else{
                                                $Colleagues = '0';
                                            }
                                            echo  $Colleagues;
                                        ?>
                                    </span>
                                    <span class="font-weight-light">Colleagues</span>
                                </div>

                                
                                <div class="h2 text-muted">
                                    <i class="icon icon-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 d-block font-weight-normal mb-2">
                                        <?php
                                            $sql = "select sum(Amount) as income from salary group by EID having EID = ".$_SESSION['EID'].";";
                                            $result = $conn->query($sql);
                                            $income = '';
                                            if($result->num_rows > 0){
                                                $row = $result->fetch_assoc();
                                                $income = $row['income'];
                                            }
                                            else{
                                                $income = '0';
                                            }
                                            echo '￥'. $income;
                                        ?>
                                    </span>
                                    <span class="font-weight-light">Income</span>
                                </div>

                                <div class="h2 text-muted">
                                    <i class="icon icon-wallet"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 d-block font-weight-normal mb-2">
                                        <?php
                                            echo ucfirst($_SESSION['Dname']);
                                        ?>
                                    </span>
                                    <span class="font-weight-light">Department</span>
                                </div>

                                <div class="h2 text-muted">
                                    <i class="icon icon-home"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 d-block font-weight-normal mb-2"><?php echo $_SESSION['Eyear'].' years';?></span>
                                    <span class="font-weight-light">Working Age</span>
                                </div>

                                <div class="h2 text-muted">
                                    <i class="icon icon-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                工资走势图
                            </div>  

                            <div class="card-body p-0">
                                <div class="p-4">
                                    <?php
                                        $sql = "select Amount, PayTime from salary where EID = ".$_SESSION['EID']." order by PayTime asc;";
                                        $result = $conn->query($sql);
                                        $income = '';
                                        $time = array();
                                        $amount = array();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                array_push($time, $row['PayTime']);
                                                array_push($amount, $row['Amount']);
                                            }
                                        }
                                    ?> 
                                    <input id = amount style='display:none' value=<?php echo json_encode($amount)?>>
                                    <input id = time style='display:none' value=<?php echo json_encode($time)?>>
                                    <input style="display:none" type="text" id="ticket" value="<?php echo $_SESSION['ticket']?>">
                                    <canvas id="line-chart" width="100%" height="20"></canvas>
                                </div>

                                <div class="justify-content-around mt-4 p-4 bg-light d-flex border-top d-md-down-none">
                                    <div class="text-center">
                                        <div class="text-muted small">Average Amount(during passed 12 months)</div>
                                        <div>
                                            <?php
                                                $time12 = array_slice($time, -12);
                                                $amount12 = array_slice($amount, -12); 
                                                $amountAvg = array_sum($amount12) / count($amount12);
                                                echo $amountAvg;
                                            ?>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="text-muted small">Max Amount(during passed 12 months)</div>
                                        <div><?php echo max($time12) . ": " . max($amount12);?></div>   
                                    </div>

                                    <div class="text-center">
                                        <div class="text-muted small">Min Amount(during passed 12 months)</div>
                                        <div><?php echo min($time12) . ": " . min($amount12);?></div>
                                    </div>

                                    <!-- <div class="text-center">
                                        <div class="text-muted small">Total Downloads</div>
                                        <div>957,565 Files (100 TB)</div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./com/vendor/jquery/jquery.min.js"></script>
<script src="./com/vendor/popper.js/popper.min.js"></script>
<script src="./com/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="./com/vendor/chart.js/chart.min.js"></script>
<script src="./com/js/carbon.js"></script>
<script src="./com/js/employee.js"></script>
</body>
</html>
