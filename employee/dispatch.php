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
    switch($command){
        case 'createSession':{
            $localHost = $_SERVER['HTTP_HOST'];
            $loginUrl = "https://www.login.com/login.php";
            $_SESSION['ticket'] = $params['ticket'];
            $_SESSION['validate'] = $params['validate'];
            $_SESSION['EID'] = $params['EID'];
            $_SESSION['DID'] = $params['DID'];
            $_SESSION['Ename'] = $params['Ename'];
            $_SESSION['MEID'] = $params['MEID'];
            $_SESSION['MEname'] = $params['MEname'];
            $_SESSION['Eage'] = $params['Eage'];
            $_SESSION['Eyear'] = $params['Eyear'];
            $_SESSION['Esex'] = $params['Esex'];
            $_SESSION['Eemail'] = $params['Eemail'];
            $_SESSION['Ephone'] = $params['Ephone'];
            $_SESSION['Dname'] = $params['Dname'];
            $_SESSION['Desc'] = $params['Desc'];
            $_SESSION['perm'] = $params['perm'];
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $page\");
                    window.location.href=\"https://$page\";";
            echo "</script>";
            break;
        }
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
            echo "alert(\"redirect to $dispatchUrl\");";
            echo "window.location.href=\"$dispatchUrl?page=$page&command=logout&ticket=$ticket\";";
            echo "</script>";
            break;
        }
        case 'update':{
            break;
        }
    }
?>