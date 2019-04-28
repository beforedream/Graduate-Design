<?php
    session_start();
    var_dump($_SESSION);
    $param = $_SERVER['QUERY_STRING'];
    function convertUrlQuery($query){
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
    $params = convertUrlQuery($param);
    $page = $params["page"];

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function createTicket(){
        return mt_rand(0, mt_getrandmax());
    }
    $count = count($_SESSION['ticket']);
    echo "<br>";
    echo "count: $count";
    echo "<br>";
    if(count($_SESSION['ticket']) > 0){
        if(isset($_SESSION['ticket'][$page])){
            $ticket = $_SESSION['ticket'][$page];
            echo "<script>";
            echo "alert(\"redirect to $page\");";
            echo "window.location.href = \"https://$page?ticket=$ticket\";";
            echo "</script>";
            exit;
        }
        else{
            $ticket = createTicket();
            $_SESSION['ticket'][$page] = $ticket;
            echo "<script>";
            echo "alert(\"redirect to $page\");";
            echo "window.location.href = \"https://$page?ticket=$ticket\";";
            echo "</script>";
            exit;
        }
    }
    $accountErr = $passwordErr = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST["account"])){
            $accountErr = "请输入帐号（工号）";
        }
        $account = test_input($_POST["account"]);
        if(empty($_POST["password"])){
            $passwordErr = "请输入密码";
        }
        else{
            $password = test_input($_POST["password"]);
            if(!preg_match("/^.*(?=.{6,16})(?=.*\d)(?=.*[A-Z]{2,})(?=.*[a-z]{2,})(?=.*[!@#$%^&*?\(\)]).*$/", $password)){
                $passwordErr = "密码必须包含：\\\r\n" . "* 一个数字\\" . PHP_EOL . "* 两个大写字和小写母\\" . PHP_EOL . "* 一个特殊字符";
            }
        }
        if($accountErr != ""){
            echo "<script>";
            echo "alert(\"$accountErr\")";
            echo "</script>";
        }
        if($passwordErr != ""){
            echo "<script>";
            echo "alert(\"$passwordErr\")";
            echo "</script>";
        }
        if($accountErr == "" && $passwordErr == ""){
            $servername = "localhost";
            $username = "root";
            $passwd = "root";
            $dbname = "EMS";
            $conn = new mysqli($servername, $username, $passwd, $dbname);
            // 检测连接
            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
                //exit;
            }
            echo "console.log('connect successful')</br>";
            $sql = "select * from usertable where EID = $account;";
            echo "sql: $sql"."</br>";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                //TODO
                //密码输入部分可以加入次数保护。待选。
                if(!strcmp($row["passwd"], $password)){
                    echo "$password" . "</br>";
                    $ticket = createTicket();
                    echo "ticket = $ticket</br>";
                    $_SESSION['ticket'][$page] = $ticket;
                    $_SESSION['EID'] = $account;
                    $_SESSION['passwd'] = trim($password);
                    if(!strcmp($page, 'www.personnel.com') || !strcmp($page, 'www.salary.com')){
                        $sql = "select belong.EID as EID,
                                    belong.DID as DID, 
                                    employee1.Ename as Ename,
                                    belong.MEID as MEID, 
                                    employee2.Ename as MEname, 
                                    employee1.Eage as Eage,
                                    employee1.Eyear as Eyear, 
                                    employee1.Esex as Esex, 
                                    employee1.Eemail as Eemail,
                                    employee1.Ephone as Ephone,
                                    department.Dname as Dname,
                                    department.Description as Description,
                                    pageperm.permtype as perm
                                from belong, employee employee1, employee employee2, 
                                    department, pageperm 
                                where belong.EID = $account
                                    and pageperm.pagename = '$page'
                                    and belong.DID = department.DID 
                                    and belong.EID = employee1.EID 
                                    and belong.MEID = employee2.EID
                                    and belong.DID = pageperm.DID;";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $_SESSION['EID'] = trim($row['EID']);
                            $_SESSION['DID'] = trim($row['DID']);
                            $_SESSION['Ename'] = trim($row['Ename']);
                            $_SESSION['MEID'] = trim($row['MEID']);
                            $_SESSION['MEname'] = trim($row['MEname']);
                            $_SESSION['Eage'] = trim($row['Eage']);
                            $_SESSION['Eyear'] = trim($row['Eyear']);
                            $_SESSION['Esex'] = trim($row['Esex']);
                            $_SESSION['Eemail'] = trim($row['Eemail']);
                            $_SESSION['Ephone'] = trim($row['Ephone']);
                            $_SESSION['Dname'] = trim($row['Dname']);
                            $_SESSION['Description'] = trim($row['Description']);
                            $_SESSION['perm'] = trim($row['perm']);
                        }
                        else{
                            echo "some error happend";
                        }
                    }
                    else{
                        $sql = "select belong.EID as EID,
                            belong.DID as DID, 
                            employee1.Ename as Ename,
                            belong.MEID as MEID, 
                            employee2.Ename as MEname, 
                            employee1.Eage as Eage,
                            employee1.Eyear as Eyear, 
                            employee1.Esex as Esex, 
                            employee1.Eemail as Eemail,
                            employee1.Ephone as Ephone,
                            department.Dname as Dname,
                            department.Description as Description
                        from belong, employee employee1, employee employee2, 
                            department
                        where belong.EID = $account
                            and belong.DID = department.DID 
                            and belong.EID = employee1.EID 
                            and belong.MEID = employee2.EID;";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            echo "sql: $sql</br>";
                            $row = $result->fetch_assoc();
                            $_SESSION['EID'] = trim($row['EID']);
                            $_SESSION['DID'] = trim($row['DID']);
                            $_SESSION['Ename'] = trim($row['Ename']);
                            $_SESSION['MEID'] = trim($row['MEID']);
                            $_SESSION['MEname'] = trim($row['MEname']);
                            $_SESSION['Eage'] = trim($row['Eage']);
                            $_SESSION['Eyear'] = trim($row['Eyear']);
                            $_SESSION['Esex'] = trim($row['Esex']);
                            $_SESSION['Eemail'] = trim($row['Eemail']);
                            $_SESSION['Ephone'] = trim($row['Ephone']);
                            $_SESSION['Dname'] = trim($row['Dname']);
                            $_SESSION['Description'] = trim($row['Description']);
                            $_SESSION['perm'] = '1';
                        }
                        else{
                            echo "some error happend";
                        }
                    }
                    //$info = $_SESSION['info'];
                    echo "<script>";
                    echo "alert(\"redirect to $page\");";
                    echo "window.location.href = \"https://$page?ticket=$ticket\";";
                    echo "</script>";
                    exit;
                }
                echo "<script>";
                echo "alert(\"密码输入不正确, 请重新尝试！\");"; 
                echo "</script>";
            }
            else{
                echo "<script>";
                echo "alert(\"没有查询到此帐号！\");";
                echo "</script>";
            }
            
            
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
</head>
<body>
<div class="page-wrapper flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <div class="card-header text-center text-uppercase h4 font-weight-light">
                        Login
                    </div>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF" . $param]);?>"> 
                        <div class="card-body py-5">
                            <div class="form-group">
                                <label class="form-control-label">Account</label>
                                <input type="number" class="form-control" name="account" autofocus>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label">Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="login">
                                <label class="custom-control-label" for="login">Remember password?</label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-5" id = "button_login">Login</button>
                                </div>

                                <div class="col-6">
                                    <a href="#" class="btn btn-link">Forgot password?</a>
                                </div>
                            </div>
                        </div>
                    </form>
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
<script src="./com/js/demo.js"></script>
</body>
</html>
