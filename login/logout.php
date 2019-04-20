<?php
    session_start();
    var_dump($_SESSION);
    function convertUrlQuery($query){
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
    $localHost = $_SERVER['HTTP_HOST'];
    $loginUrl = "https://www.login.com/login.php";
    $param = $_SERVER['QUERY_STRING'];
    $params = convertUrlQuery($param);
    $page = $params["page"];
    $oldpage = $params['oldpage'];
    $ticket = $params['ticket'];
    if($ticket != null && $page != null){
        var_dump($_SESSION['ticket']);
        if($_SESSION['ticket'][$page] == $ticket){
            foreach($_SESSION['ticket'] as $x => $x_value){
                echo "Key=$x, Value=$x_value";
                echo "<br>";
                unset($_SESSION['ticket'][$x]);
                echo "<script language=\"javascript\">";
                //echo "alert(\"open new window: $x/destory.php\");";
                echo "window.open(\"https://$x/destorySession.php\");";
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
            echo "alert(\"back to $oldpage\")";
            echo "window.location.href = \"$oldpage\";";
            echo "</script>";
        }
    }
?>