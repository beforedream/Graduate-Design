<?php
    header('Access-Control-Allow-Origin:https://www.employee.com, https://www.personnel.com. https://www.salary.com');
    //跨域且使用session时不能使用 *
    header("Access-Control-Allow-Credentials: true" );
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
    $params = convertUrlQuery($param);
    $page = $params["page"];

    function Http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            // curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        // echo "<br> errorno:".curl_errno($curl);
        curl_close($curl);
        return $output;
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function getData($page, $account, $password, $conn){
        $sql = "select * from usertable where EID = $account;";
        echo "sql: $sql"."</br>";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            //TODO
            //密码输入部分可以加入次数保护。待选。
            if(!strcmp($row["passwd"], $password)){
                if(!isset($_SESSION['ticket'][$page])){
                    $ticket = createTicket();
                    $_SESSION['ticket'][$page] = $ticket;
                }
                $_SESSION['EID'] = $account;
                $_SESSION['passwd'] = trim($password);
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
                }
                else{
                    echo "some error happend";
                }
                //$info = $_SESSION['info'];
                return;
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

    function createSession($page){
        $EID = $_SESSION['EID'];
        $DID = $_SESSION['DID'];
        $Ename = $_SESSION['Ename'];
        $MEID = $_SESSION['MEID'];
        $MEname = $_SESSION['MEname'];
        $Eage = $_SESSION['Eage'];
        $Eyear = $_SESSION['Eyear'];
        $Esex = $_SESSION['Esex'];
        $Eemail = $_SESSION['Eemail'];
        $Ephone = $_SESSION['Ephone'];
        $Dname = $_SESSION['Dname'];
        $Desc = $_SESSION['Description'];
        $perm = $_SESSION['perm'];
        $ticket = $_SESSION['ticket'][$page];
        echo '<script src="./com/vendor/jquery/jquery.min.js"></script>';
        echo '<script>';
        echo "$.ajax({
            type: 'POST',      //data 传送数据类型。post 传递
            url: 'https://$page/dispatch.php',  // yii 控制器/方法  
            data: {
                'command':'createSession',
                'EID': $EID,
                'DID': $DID,
                'Ename': '$Ename',
                'MEID': $MEID,
                'MEname': '$MEname',
                'Eage': $Eage,
                'Eyear': $Eyear,
                'Esex': $Esex,
                'Eemail': '$Eemail',
                'Ephone': '$Ephone',
                'Dname': '$Dname',
                'Desc': \"$Desc\",
                'ticket': $ticket,
    
            },  //传送的数据   
            xhrFields: {
                withCredentials: true
            },
            success: function (data) {
                window.location.href = 'https://$page';
            },
            error:function(){
                alert('数据传输错误');
            }
        });";
        echo '</script>';
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
            createSession($page);
            exit;
        }
        else{
            $ticket = createTicket();
            $_SESSION['ticket'][$page] = $ticket;
            createSession($page);
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
            getData($page, $account, $password, $conn);
            createSession($page);
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
