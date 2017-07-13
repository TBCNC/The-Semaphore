<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Functions/Security/SessionManager.php");
$sesMann = new SessionManager();
$sesMann->Logout();
header("Location:/");
?>
