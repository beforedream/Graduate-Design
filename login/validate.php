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
    session_start();
    $localHost = $_SERVER['HTTP_HOST'];
    $loginUrl = "https://www.login.com/login.php";
    $param = $_SERVER['QUERY_STRING'];
    $params = convertUrlQuery($param);
    $page = $params["page"];
    $ticket = $params['ticket'];
    $perm = '';
    $session_status = session_status();
    echo "$session_status</br>";
    $session_ticket = $_SESSION['ticket'][$page];
    echo "page ticket: $ticket</br>";
    echo "cas ticket: $session_ticket</br>";
    $flag = false;
    if(session_status() == PHP_SESSION_ACTIVE){
        //TODO add session message into page;
        if($_SESSION['ticket'][$page] == $ticket){
            $flag = true;
            $perm = $_SESSION['perm'];
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $page/createSession.php\");
                    window.location.href=\"https://$page/createSession.php?page=$page&ticket=$ticket&perm=$perm&validate=1\";";
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
?>