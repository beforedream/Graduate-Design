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
    
    $localHost = $_SERVER['HTTP_HOST'];
    $loginUrl = "https://www.login.com/login.php";
    $logoutUrl = "https://www.login.com/logout.php";
    $validateUrl = "https://www.login.com/validate.php";
    $param = $_SERVER['QUERY_STRING'];
    $params = convertUrlQuery($param);
    $ticket = $params["ticket"];
    $perm = '';
    $session_status = session_status();
    echo "$session_status";
    if(session_status() == PHP_SESSION_ACTIVE){
        //TODO add session message into page;
        echo "session active";
        $perm = $_SESSION['perm'];
    }
    else{
        if($ticket == null){  ///< ticket not exit;
            echo "session none & ticket = null";
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $loginUrl\");
                    window.location.href=\"$loginUrl?page=$localHost\";";
            echo "</script>";
            //header("Location: " . $loginUrl);
            exit;
        }
        else{
            echo "session none & ticket = $ticket";
            echo "$param";
            echo "</br>";
            //echo $params['ticket'];
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $validateUrl\");
                    window.location.href=\"$validateUrl?page=$localHost&ticket=$ticket\";";
            echo "</script>";
            //header("Location: " . $loginUrl);
            exit;
        }
    }
?>