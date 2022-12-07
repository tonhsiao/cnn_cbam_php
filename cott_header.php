<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/bootstrap_5.2.1/bootstrap.min.css" rel="stylesheet" >
    <SCRIPT language=javascript src="../../phpfunc/common.js"></SCRIPT>
    <script>
    function open_win(msg,e){
        if (confirm(msg) == true) {
        window.open(e, "_new", "width= 500, height= 150, left=400, top=200, resizable=yes, toolbar=no, location=yes, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no").blur();
        }
    }
    function open_win2(e, w, h, l, t){
        window.open(e, "_new", "width="+ w +", height="+ h +", left="+ l +", top="+ t +", resizable=yes, toolbar=no, location=yes, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no").blur();
    }
    </script>
<?php
    require '../../phpfunc/func_common.php';
    require("../../phpfunc/db_connect.php");
    require '../../phpfunc/get_user.php';

    //偵測瀏覽器
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(strpos($agent,"MSIE")){
        echo "<script>alert('很抱歉, 此功能無法使用IE瀏覽器, 需以CHROME開啟. 謝謝！');</script>";
        exit;
    }
?>