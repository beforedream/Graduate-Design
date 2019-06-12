<?php
    header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true" );
    session_start();
    function getData($page, $account, $password, $conn){
        $sql = "select * from usertable where EID = $account;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if(!strcmp($row["passwd"], $password)){
                if(!isset($_SESSION['ticket'][$page])){
                    $ticket = createTicket();
                    $_SESSION['ticket'][$page] = $ticket;
                }
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
                    }
                    else{
                        echo "111some error happend";
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
                    // var_dump($sql);
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
                        echo "222some error happend";
                    }
                }
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

    $password = $_POST['password'];
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
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $command = $_POST['command'];
        switch($command){
            case 'validate':{
                $loginUrl = "https://www.login.com/login.php";
                $ticket = $_POST['ticket'];
                if(isset($_SESSION['ticket'])){
                    if($_SESSION['ticket'][parse_url($_SERVER['HTTP_ORIGIN'])['host']] == $ticket){
                        $sql = "
                            select * from pageperm where pagename = '". parse_url($_SERVER['HTTP_ORIGIN'])['host'] ."' and DID = ".$_SESSION['DID']." and permtype = 1;
                        ";
                        $result = $conn->query($sql);
                        if($result->num_rows == 0){
                            echo 0;
                            exit;
                        }
                        echo 1;
                        exit;
                    }
                }
                echo 0;
                break;
            }
            case 'validatePassword':{
                $password = $_POST['password'];
                if($password == $_SESSION['passwd']){
                    echo 1;
                }
                else{
                    echo 0;
                }
                break;
            }
            case 'updatePassword':{
                $sql = "update usertable set passwd = '$password' where EID = ".$_SESSION['EID'].";";
                $result = $conn->query($sql);
                if($result == false){
                    echo 0;
                }
                else{
                    $_SESSION['passwd'] = $password;
                    echo 1;
                }
                break;
            }
            case 'update':{
                $sql = $_POST['sql'];
                $result = $conn->query($sql);
                echo $result;   
                // var_dump($_SESSION);
                getData(parse_url($_SERVER['HTTP_ORIGIN'])['host'], $_SESSION['EID'], $_SESSION['passwd'], $conn);
                break;
            }
            case 'departmentSalarySelect':{
                $DID = $_POST['DID'];
                $ticket = $_POST['ticket'];
                $sql = "
                SELECT 
                    sum(Amount) as Amount,
                    date_format(PayTime, '%Y %m') as PayTime
                FROM
                    EMS.salary, EMS.belong
                where 
                    date_format(PayTime, '%Y %m') between date_format(DATE_SUB(curdate(), INTERVAL 12 MONTH),'%Y %m')
                        and date_format(DATE_SUB(curdate(), interval 0 month), '%Y %m')
                    and EMS.salary.EID = EMS.belong.EID
                    and EMS.belong.DID = $DID
                group by date_format(PayTime, '%Y %m');";
                $result = $conn->query($sql);
                $time = array();
                $amount = array();
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        array_push($time, $row['PayTime']);
                        array_push($amount, $row['Amount']);
                    }
                }
                // var_dump($_POST);
                // var_dump($result);
                $data = array($time, $amount);
                echo json_encode($data);
                break;
            }
            case 'employeeSalarySelect':{
                $DID = $_POST['DID'];
                $EID = $_POST['EID'];
                $ticket = $_POST['ticket'];
                $sql = "
                select 
                    * 
                from 
                    belong 
                where 
                    DID = $DID 
                and 
                    EID = $EID;";
                $result = $conn->query($sql);
                $time = array();
                $amount = array();
                $belong;
                $Dname = '';
                if($result->num_rows > 0){
                    $belong = 1;
                    $sql = "
                    SELECT 
                        Amount,
                        PayTime
                    FROM
                        salary
                    where 
                        salary.EID = $EID;";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            array_push($time, $row['PayTime']);
                            array_push($amount, $row['Amount']);
                        }
                    }
                    $sql = "
                    SELECT 
                        department.Dname as Dname, 
                        employee.Ename as Ename
                    FROM
                        department, employee
                    where 
                        department.DID = $DID
                    and 
                        employee.EID = $EID;";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $Dname = $row['Dname'];
                        $Ename = $row['Ename'];
                    }
                }
                else{
                    $belong = 0;
                }
                $data = array($belong, $time, $amount, $Dname, $Ename);
                echo json_encode($data);
                break;
            }
            case 'insertSalaryRecord':{
                $DID = $_POST['DID'];
                $EID = $_POST['EID'];
                $ticket = $_POST['ticket'];
                $sql = "
                select 
                    * 
                from 
                    belong 
                where 
                    DID = $DID 
                and 
                    EID = $EID;";
                $result = $conn->query($sql);
                $time = array();
                $amount = array();
                $belong;
                $success;
                $Dname = '';
                if($result->num_rows > 0){
                    $DID = $_POST['DID'];
                    $EID = $_POST['EID'];
                    $time = $_POST['time'];
                    $amount = $_POST['Amount'];
                    $ticket = $_POST['ticket'];
                    $sql = "
                    insert into
                        salary
                    values(
                        $EID,
                        '$time',
                        $amount
                    );";
                    $result = $conn->query($sql);
                    if($result == false){
                        $success = 0;
                    }
                    $success = 1;
                    $belong = 1;
                }
                else{
                    $belong = 0;
                }
                $data = array($belong, $success);
                echo json_encode($data);
                break;
            }
            case 'deleteSalaryRecord':{
                $DID = $_POST['DID'];
                $EID = $_POST['EID'];
                $time = $_POST['time'];
                $ticket = $_POST['ticket'];
                $sql = "
                select 
                    * 
                from 
                    belong 
                where 
                    DID = $DID 
                and 
                    EID = $EID;";
                $result = $conn->query($sql);
                $belong;
                $success = 0;
                $exist;
                $Dname = '';
                if($result->num_rows > 0){
                    $belong = 1;
                    $sql = "
                    select 
                        * 
                    from 
                        salary
                    where 
                        EID = $EID
                    and 
                        PayTime = '$time';";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $exist = 1;
                        $sql = "
                        delete from 
                            salary
                        where 
                            EID = $EID
                        and 
                            PayTime = '$time';";
                        $result = $conn->query($sql);
                        if($result == false){
                            $success = 0;
                        }
                        $success = 1;
                    }
                    else{
                        $exist = 0;
                    }
                }
                else{
                    $belong = 0;
                }
                $data = array($belong, $exist, $success);
                echo json_encode($data);
                break;
            }

            case 'departmentPersonnelSelect':{
                $DID = $_POST['DID'];
                $ticket = $_POST['ticket'];
                $sql = "
                select
                    belong.DID as DID,
                    belong.EID as EID,
                    employee.Ename as Ename,
                    employee.Esex as Esex,
                    employee.Eage as Eage,
                    employee.Eemail as Eemail,
                    employee.Ephone as Ephone
                from 
                    belong,
                    employee
                where 
                    belong.DID = $DID
                and
                    belong.EID = employee.EID;";
                $result = $conn->query($sql);
                $_DID = array();
                $EID = array();
                $Ename = array();
                $Esex = array();
                $Eage = array();
                $Eemail = array();
                $Ephone = array();
                $length = $result->num_rows;
                while($row = $result->fetch_assoc()){
                    array_push($_DID, $row['DID']);
                    array_push($EID, $row['EID']);
                    array_push($Ename, $row['Ename']);
                    array_push($Esex, $row['Esex']);
                    array_push($Eage, $row['Eage']);
                    array_push($Eemail, $row['Eemail']);
                    array_push($Ephone, $row['Ephone']);
                }
                $data = array($length, $_DID, $EID, $Ename, $Esex, $Eage, $Eemail, $Ephone);
                echo json_encode($data);
                break;
            }
            case 'employeePersonnelSelect':{
                $EID = $_POST['EID'];
                $ticket = $_POST['ticket'];
                $sql = "
                select
                    belong.DID as DID,
                    belong.EID as EID,
                    employee.Ename as Ename,
                    employee.Esex as Esex,
                    employee.Eage as Eage,
                    employee.Eemail as Eemail,
                    employee.Ephone as Ephone
                from 
                    belong,
                    employee
                where 
                    belong.EID = $EID
                and
                    belong.EID = employee.EID;";
                $result = $conn->query($sql);
                $_DID = array();
                $EID = array();
                $Ename = array();
                $Esex = array();
                $Eage = array();
                $Eemail = array();
                $Ephone = array();
                $length = $result->num_rows;
                while($row = $result->fetch_assoc()){
                    array_push($_DID, $row['DID']);
                    array_push($EID, $row['EID']);
                    array_push($Ename, $row['Ename']);
                    array_push($Esex, $row['Esex']);
                    array_push($Eage, $row['Eage']);
                    array_push($Eemail, $row['Eemail']);
                    array_push($Ephone, $row['Ephone']);
                }
                $data = array($length, $_DID, $EID, $Ename, $Esex, $Eage, $Eemail, $Ephone);
                echo json_encode($data);
                break;
            }
            case 'insertEmployee':{
                $DID = $_POST['DID'];
                $EID = $_POST['EID'];
                $Ename = $_POST['Ename'];
                $Esex = $_POST['Esex'];
                $Eage = $_POST['Eage'];
                $Eemail = $_POST['Eemail'];
                $Ephone = $_POST['Ephone'];
                $ticket = $_POST['ticket'];
                $sql = "call insert_table($DID, $EID, '$Ename', $Eage, $Esex, '$Eemail', '$Ephone');";
                $result = $conn->query($sql);
                if($result!=false){
                    echo 1;
                }
                else{
                    echo 0;
                }
                break;
            }
            case 'deleteEmployee':{
                $ret = true;
                $EIDArray = json_decode($_POST['EID']);
                for($i=0;$i<count($EIDArray);$i++)
                {
                    $sql = "call delete_Employee(".$EIDArray[$i].");";
                    $result = $conn->query($sql);
                    if($result == false){
                        $ret = false;
                        break;
                    }
                }
                if($ret == false){
                    echo 0;
                }
                else{
                    echo 1;
                }
                break;
            }
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "GET"){
        echo '<script src="./com/vendor/jquery/jquery.min.js"></script>';
        $command = $_GET['command'];
        switch($command){
            case 'update':{
                $sql = $params['sql'];
                break;
            }
            case 'logout':{
                $localHost = $_SERVER['HTTP_HOST'];
                $loginUrl = "https://www.login.com/login.php";
                $ticket = $_GET['ticket'];
                $page = $_GET['page'];
                if(!isset($_SESSION['ticket'])){
                    echo "<script language=\"javascript\">";
                    echo "window.open(\"https://".parse_url($_SERVER['HTTP_REFERER'])['host']."/dispatch.php?command=destorySession\");";
                    echo "</script>";
                    exit;
                }
                if($ticket != null && $page != null){
                    if($_SESSION['ticket'][parse_url($_SERVER['HTTP_REFERER'])['host']] == $ticket){
                        foreach($_SESSION['ticket'] as $x => $x_value){
                            echo "Key=$x, Value=$x_value";
                            echo "<br>";
                            unset($_SESSION['ticket'][$x]);
                            echo "<script language=\"javascript\">";
                            echo "window.open(\"https://$x/dispatch.php?command=destorySession\");";
                            echo "</script>";
                        }
                        if(count($_SESSION['ticket']) == 0){
                            $_SESSION = array();
                            session_destroy();
                            echo "<script language=\"javascript\">";
                            echo "window.location.href = \"$loginUrl?page=$page\";";
                            echo "</script>";
                        }
                    }
                    else{
                        echo "<script language=\"javascript\">";
                        echo "alert(\"back to $page\");";
                        echo "window.location.href = \"https://$page\";";
                        echo "</script>";
                    }
                }
                break;
            }
        }
    }
?>