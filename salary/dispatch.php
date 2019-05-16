<?php
    // header('Access-Control-Allow-Origin:https://www.login.com, https://www.personnel.com. https://www.salary.com');
    header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
    //跨域且使用session时不能使用 *
    header("Access-Control-Allow-Credentials: true" );
    session_start();
    var_dump($_SERVER["REQUEST_METHOD"]);
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $command = $_POST['command'];
        var_dump($command);
        switch($command){
            case 'createSession':{
                $_SESSION['ticket'] = $_POST['ticket'];
                $_SESSION['EID'] = $_POST['EID'];
                $_SESSION['DID'] = $_POST['DID'];
                $_SESSION['Ename'] = $_POST['Ename'];
                $_SESSION['MEID'] = $_POST['MEID'];
                $_SESSION['MEname'] = $_POST['MEname'];
                $_SESSION['Eage'] = $_POST['Eage'];
                $_SESSION['Eyear'] = $_POST['Eyear'];
                $_SESSION['Esex'] = $_POST['Esex'];
                $_SESSION['Eemail'] = $_POST['Eemail'];
                $_SESSION['Ephone'] = $_POST['Ephone'];
                $_SESSION['Dname'] = $_POST['Dname'];
                $_SESSION['Desc'] = $_POST['Desc'];
                // var_dump($_SESSION);
                echo true;
                break;
            }
            case 'logout':{
                $dispatchUrl = "https://www.login.com/dispatch.php";
                $page = $_SERVER['HTTP_HOST'];
                $ticket = $_SESSION['ticket'];
                echo "<script>";
                echo "alert(\"redirect to $dispatchUrl\");";
                echo "window.location.href=\"$dispatchUrl?page=$page&command=logout&ticket=$ticket\";";
                echo "</script>";
                break;
            }
            case 'update':{
                break;
            }
        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "GET"){
        $command = $_GET['command'];
        var_dump($command);
        switch($command){
            case 'destorySession':{
                $_SESSION = array();
                session_destroy();
                echo "<script>";
                echo "window.close()";
                echo "</script>";
                break;
            }
            case 'logout':{
                $dispatchUrl = "https://www.login.com/dispatch.php";
                $page = $_SERVER['HTTP_HOST'];
                $ticket = $_SESSION['ticket'];
                echo "<script>";
                echo "window.location.href=\"$dispatchUrl?page=$page&command=logout&ticket=$ticket\";";
                echo "</script>";
                break;
            }
            case 'update':{
                break;
            }
        }
    }
?>