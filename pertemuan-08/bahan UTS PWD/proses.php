<?php
session_start();
$sesnama = $_POST["txtNama"];
$sesemail = $_POST["txtEmail"];
$sespesan = $_POST["txtPesan"];
$_SESSION["sesnama"] = $sesnama;
$_SESSION["sesemail"] = $sesemail;
$_SESSION["sespesan"] = $sespesan;

header("location: index.php");
$nim        = $_SESSION["nim"]        ?? "";
$nama       = $_SESSION["nama"]       ?? "";
$tempat     = $_SESSION["tempat"]     ?? "";
$tgl_lahir  = $_SESSION["tgl_lahir"]  ?? "";
$hobi       = $_SESSION["hobi"]       ?? "";
$pasangan   = $_SESSION["pasangan"]   ?? "";
$pekerjaan  = $_SESSION["pekerjaan"]  ?? "";
$ortu       = $_SESSION["ortu"]       ?? "";
$kakak      = $_SESSION["kakak"]      ?? "";
$adik       = $_SESSION["adik"]       ?? "";

$sesnama  = $_SESSION["sesnama"]  ?? "";
$sesemail = $_SESSION["sesemail"] ?? "";
$sespesan = $_SESSION["sespesan"] ?? "";
?>