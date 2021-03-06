<?php
    header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
    //跨域且使用session时不能使用 *
    header("Access-Control-Allow-Credentials: true" );
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
    
    session_start();
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
    if(isset($_SESSION['ticket'])){
        $ticket = $_SESSION['ticket'];
    }
    else{
        $ticket = $params["ticket"];
    }
    if($ticket == null){
        echo "<script> window.location.href='$loginUrl?page=$localHost'</script>";
    }
?>

<!doctype html>
<html lang="en">
<head>
    <input id='ticket' style='display:none' value=<?php echo $ticket?>>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carbon - Admin Template</title>
    <link rel="stylesheet" href="./com/vendor/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="./com/vendor/font-awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./com/css/styles.css">
    <script src="./com/vendor/jquery/jquery.min.js"></script>
    <script src="./com/vendor/popper.js/popper.min.js"></script>
    <script src="./com/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./com/vendor/chart.js/chart.min.js"></script>
    <script src="./com/js/carbon.js"></script>
    <script src="./com/js/employee.js"></script>
    <script src="./com/vendor/vue/vue.min.js"></script>
    <script>
        $.ajax({
            type: 'POST',      //data 传送数据类型。post 传递
            url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
            data: {
                'command':'validate',
                'ticket': <?php echo $ticket?>,

            },  //传送的数据   
            xhrFields: {
                withCredentials: true
            },
            success: function (data) {
                ret = data;
                if(window.console){
                    console.log(data);
                }
                if(data == 0){
                    // alert(data);
                    alert('没有访问权限, 请重新登录');
                    window.location.href = 'dispatch.php?command=logout';
                    alert(window.location.href);
                }
            },
            error:function(){
                alert('validate数据传输错误');
            }
        });
    </script>
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
                            <span class="h4 d-block font-weight-normal mb-2">邮件</span>
                            <input class="font-weight-light" id='Eemail' type='text' value="<?php echo $_SESSION['Eemail']?>" readonly>
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
                            <span class="h4 d-block font-weight-normal mb-2">主管</span>
                            <input class="font-weight-light" id='MEname' type='text' value="<?php echo $_SESSION['MEname']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">工号</span>
                            <input class="font-weight-light" id='EID' type='text' value="<?php echo $_SESSION['EID']?>" readonly>
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

                document.getElementById('Ename').style.border = '1px solid black';
                document.getElementById('Esex').style.border = '1px solid black';
                document.getElementById('Eage').style.border = '1px solid black';
                document.getElementById('Eemail').style.border = '1px solid black';
                document.getElementById('Ephone').style.border = '1px solid black';
            ">修改</button>
            <script>
                function UpdateDB(sql){
                    $.ajax({
                        type: 'POST',      //data 传送数据类型。post 传递
                        url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                        data: {
                            'command':'update',
                            'sql':sql,
                        },  //传送的数据   
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function (data) {
                            // alert(data);
                            url =  'https://www.login.com/login.php?page='+window.location.host;
                            window.location.href = url;
                        },
                        error:function(){
                            alert('数据库更新失败');
                        }
                    });
                }
            </script>
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
                    UpdateDB(sql);
                    document.getElementById('Ename').readOnly=true;
                    document.getElementById('Esex').disabled=true;
                    document.getElementById('Eage').readOnly=true;
                    document.getElementById('Eemail').readOnly=true;
                    document.getElementById('Ephone').readOnly=true;
                    document.getElementById('Ename').style.border = 'none';
                    document.getElementById('Esex').style.border = 'none';
                    document.getElementById('Eage').style.border = 'none';
                    document.getElementById('Eemail').style.border = 'none';
                    document.getElementById('Ephone').style.border = 'none';
                }
                else{

                }
            ">确认</button>
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').value = '<?php echo $_SESSION['Ename']?>';
                document.getElementById('Esex').value = '<?php echo $_SESSION['Esex']?>';
                document.getElementById('Eage').value = '<?php echo $_SESSION['Eage']?>';
                document.getElementById('Eemail').value = '<?php echo $_SESSION['Eemail']?>';
                document.getElementById('Ephone').value = '<?php echo $_SESSION['Ephone']?>';
                document.getElementById('Ename').readOnly=true;
                document.getElementById('Esex').disabled=true;
                document.getElementById('Eage').readOnly=true;
                document.getElementById('Eemail').readOnly=true;
                document.getElementById('Ephone').readOnly=true;
                document.getElementById('Ename').style.border = 'none';
                document.getElementById('Esex').style.border = 'none';
                document.getElementById('Eage').style.border = 'none';
                document.getElementById('Eemail').style.border = 'none';
                document.getElementById('Ephone').style.border = 'none';
            ">取消</button>
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').value = '<?php echo $_SESSION['Ename']?>';
                document.getElementById('Esex').value = '<?php echo $_SESSION['Esex']?>';
                document.getElementById('Eage').value = '<?php echo $_SESSION['Eage']?>';
                document.getElementById('Eemail').value = '<?php echo $_SESSION['Eemail']?>';
                document.getElementById('Ephone').value = '<?php echo $_SESSION['Ephone']?>';
                document.getElementById('Ename').readOnly=true;
                document.getElementById('Esex').disabled=true;
                document.getElementById('Eage').readOnly=true;
                document.getElementById('Eemail').readOnly=true;
                document.getElementById('Ephone').readOnly=true;
                document.getElementById('Ename').style.border = 'none';
                document.getElementById('Esex').style.border = 'none';
                document.getElementById('Eage').style.border = 'none';
                document.getElementById('Eemail').style.border = 'none';
                document.getElementById('Ephone').style.border = 'none';
                document.getElementById('message').style.display='none';
            ">退出</button>
        </div>
    </div>
    
    <nav class="navbar page-header">
        <a href="#" class="btn btn-link sidebar-mobile-toggle d-md-none mr-auto">
            <i class="fa fa-bars"></i>
        </a>

        <a class="navbar-brand" href="#">
            <span>CS1505_litao</span>
        </a>

        <a href="#" class="btn btn-link sidebar-toggle d-md-down-none">
            <i class="fa fa-bars"></i>
        </a>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-md-down-none">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span class="badge badge-pill badge-danger"></span>
                </a>
            </li>

            <li class="nav-item d-md-down-none">
                <a href="#">
                    <i class="fa fa-envelope-open"></i>
                    <span class="badge badge-pill badge-danger"></span>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="./com/imgs/avatar-1.png" class="avatar avatar-sm" alt="logo">
                    <span class="small ml-1 d-md-down-none"><?php echo ''.$_SESSION['EID'].'@'.$_SESSION['Ename']?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header">Account</div>
                    <script>
                        function modifyPassword(){
                            var password = prompt('请输入旧密码');
                            $.ajax({
                                type: 'POST',      //data 传送数据类型。post 传递
                                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                                data: {
                                    'command':'validatePassword',
                                    'password': password,

                                },  //传送的数据   
                                xhrFields: {
                                    withCredentials: true
                                },
                                success: function (data) {
                                    if(data == 1){
                                        var newPassword = prompt('请输入新密码');
                                        var reg = new RegExp(/^.*(?=.{6,16})(?=.*\d)(?=.*[A-Z]{2,})(?=.*[a-z]{2,})(?=.*[!@#$%^&*?\(\)]).*$/);
                                        if(!reg.test(newPassword)){
                                            alert("密码必须包含：\r\n* 一个数字\r\n* 两个大写字和小写母\r\n* 一个特殊字符");
                                            return;
                                        }
                                        $.ajax({
                                            type: 'POST',      //data 传送数据类型。post 传递
                                            url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                                            data: {
                                                'command':'updatePassword',
                                                'password': newPassword,

                                            },  //传送的数据   
                                            xhrFields: {
                                                withCredentials: true
                                            },
                                            success: function (data) {
                                                if(data == 1){
                                                    alert('密码更改成功');
                                                }
                                                else{
                                                    alert('密码更改失败');
                                                }
                                            },
                                            error:function(){
                                                alert('数据传输错误');
                                            }
                                        });
                                    }
                                    else{
                                        alert('密码输入错误');
                                    }
                                },
                                error:function(){
                                    alert('数据传输错误');
                                }
                            });
                        }
                        function validatePassword(password){
                            var ret;
                            $.ajax({
                                type: 'POST',      //data 传送数据类型。post 传递
                                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                                data: {
                                    'command':'validatePassword',
                                    'password': password,

                                },  //传送的数据   
                                xhrFields: {
                                    withCredentials: true
                                },
                                success: function (data) {
                                    alert(data);
                                    ret = data;
                                },
                                error:function(){
                                    alert('数据传输错误');
                                }
                            });
                            return ret;
                        }
                        function updatePassword(password){
                            var ret;
                            $.ajax({
                                type: 'POST',      //data 传送数据类型。post 传递
                                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                                data: {
                                    'command':'updatePassword',
                                    'password': password,

                                },  //传送的数据   
                                xhrFields: {
                                    withCredentials: true
                                },
                                success: function (data) {
                                    alert(data);
                                    ret = data;
                                },
                                error:function(){
                                    alert('数据传输错误');
                                }
                            });
                            return ret;
                        }
                    </script>
                    <a href="#" class="dropdown-item" onclick="modifyPassword()">
                         <i class="fa fa-user"></i>Modify Password
                    </a>
                    <div class="dropdown-item" id='Messages' onclick="document.getElementById('message').style.display='block'" >
                        <i class="fa fa-envelope"></i> Messages
                    </div>

                    <div class="dropdown-header">Settings</div>
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
                    <li class="nav-title">More</li>

                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <i class="icon icon-umbrella"></i> Pages <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="https://www.personnel.com" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Personnel Page
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="https://www.salary.com" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Salary Page
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
                                    <input id = 'amount' style='display:none' value=<?php echo json_encode($amount)?>>
                                    <input id = 'time' style='display:none' value=<?php echo json_encode($time)?>>
                                    <input style="display:none" type="text" id="ticket" value="<?php echo $_SESSION['ticket']?>">
                                    <canvas id="line-chart" width="100%" height="20"></canvas>
                                </div>

                                <div class="justify-content-around mt-4 p-4 bg-light d-flex border-top d-md-down-none">
                                    <div class="text-center">
                                        <div class="text-muted small">Average Amount(during passed 12 months)</div>
                                        <div id = 'averageAmount'>
                                            <?php
                                                $time12 = array_slice($time, -12);
                                                $amount12 = array_slice($amount, -12); 
                                                if(count($amount12) > 0){
                                                    $amountAvg = array_sum($amount12) / count($amount12);
                                                    echo $amountAvg;
                                                }
                                                else{
                                                    echo 0;
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="text-muted small">Max Amount(during passed 12 months)</div>
                                        <div id = 'maxAmount'>
                                            <?php 
                                                if(count($amount12) > 0){
                                                    echo $time12[array_search(max($amount12),$amount12)] . ": " . max($amount12);
                                                }
                                                else{
                                                    echo 'null : null';
                                                }
                                            ?>
                                        </div>   
                                    </div>

                                    <div class="text-center">
                                        <div class="text-muted small">Min Amount(during passed 12 months)</div>
                                        <div id = 'minAmount'><?php 
                                                if(count($amount12) > 0){
                                                    echo $time12[array_search(min($amount12),$amount12)] . ": " . min($amount12);
                                                }
                                                else{
                                                    echo 'null : null';
                                                }
                                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
