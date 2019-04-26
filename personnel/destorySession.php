<?php
    session_start();
    var_dump($_SESSION);
    $_SESSION = array();
    session_destroy();
    echo "<script>";
    echo "window.close()";
    echo "</script>";
?>