<?php
    session_start();
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
    $command = $params["command"];
    var_dump($command);
    switch($command){
        case 'validate':{
            var_dump($_SESSION);
            $localHost = $_SERVER['HTTP_HOST'];
            $loginUrl = "https://www.login.com/login.php";
            $ticket = $params['ticket'];
            $flag = false;
            if(session_status() == PHP_SESSION_ACTIVE){
                //TODO add session message into page;
                if($_SESSION['ticket'][$page] == $ticket){
                    $flag = true;
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
                    $host = parse_url($page)['host'];
                    echo "<script language=\"javascript\">";
                    echo "alert(\"redirect to $page/dispatch.php\");
                            window.location.href=\"https://$page/dispatch.php?page=$page&command=createSession".
                            "&ticket=$ticket&validate=1&EID=$EID&DID=$DID&Ename=$Ename&MEID=$MEID".
                            "&MEname=$MEname&Eage=$Eage&Eyear=$Eyear&Esex=$Esex&Eemail=$Eemail".
                            "&Ephone=$Ephone&Dname=$Dname&Desc=$Desc&perm=$perm\";";
                    echo "</script>";
                }
                
            }
            if(flag == false){
                echo "<script language=\"javascript\">";
                echo "alert(\"redirect to $loginUrl\");
                        window.location.href=\"https://$loginUrl?page=$page\";";
                echo "</script>";
                //header("Location: " . $loginUrl);
                exit;
            }
            break;
        }
        case 'update':{
            $sql = $params['sql'];
            break;
        }
        case 'logout':{
            if(isset($_SESSION['ticket']) == false){
                echo "<script language=\"javascript\">";
                echo "window.open(\"https://$page/dispatch.php?command=destorySession\");";
                echo "</script>";
                echo "<script language=\"javascript\">";
                echo "window.location.href = \"$loginUrl?page=$page\";";
                echo "</script>";
                exit;
            }
            $localHost = $_SERVER['HTTP_HOST'];
            $loginUrl = "https://www.login.com/login.php";
            $ticket = $params['ticket'];
            var_dump($page);
            var_dump($_SESSION['ticket']);
            var_dump($ticket);
            if($ticket != null && $page != null){
                if($_SESSION['ticket'][$page] == $ticket){
                    foreach($_SESSION['ticket'] as $x => $x_value){
                        echo "Key=$x, Value=$x_value";
                        echo "<br>";
                        unset($_SESSION['ticket'][$x]);
                        echo "<script language=\"javascript\">";
                        //echo "alert(\"open new window: $x/destory.php\");";
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
                    echo "alert(\"back to $page\")";
                    echo "window.location.href = \"$page\";";
                    echo "</script>";
                }
            }
            break;
        }
    }
?>