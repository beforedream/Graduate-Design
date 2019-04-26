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
    $ticket = $params['ticket'];
    $perm = $params['perm'];
    $_SESSION['perm'] = $perm;
    $_SESSION['ticket'] = $ticket;
    $_SESSION['validate'] = $params['validate'];
    echo "<script language=\"javascript\">";
    echo "alert(\"redirect to $page\");
            window.location.href=\"https://$page\";";
    echo "</script>";
?>