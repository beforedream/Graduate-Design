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
    $info = array();
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
            echo "<script language=\"javascript\">";
            echo "alert(\"redirect to $page/createSession.php\");
                    window.location.href=\"https://$page/createSession.php?page=$page".
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
?>