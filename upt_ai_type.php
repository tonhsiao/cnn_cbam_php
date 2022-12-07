<?php
    require '../../phpfunc/func_common.php';
    require("../../phpfunc/db_connect.php");

    if($ai_type){
        $upt = "update nocadm.predict_cott set redefined_type='$ai_type', redefined_by='$cookie_e_name $cookie_c_name', redefined_date=sysdate where cott='$itt_id'";
        $msg = "AI類別修正已收到. 謝謝您的回覆!";
    }elseif($ai_check){
        $upt = "update nocadm.predict_cott set checked_type='$ai_check', checked_by='$cookie_e_name $cookie_c_name', checked_date=sysdate where cott='$itt_id'";
        $msg = "已收到NOC確認. 謝謝您的回覆!";
    }
    $rec = do_query($conn,$upt);

    $ins = "insert into nocadm.predict_cott_rec (cott,redefined_type,redefined_by,redefined_date ) values ('$itt_id','$ai_check','$cookie_e_name $cookie_c_name',sysdate) ";
    $rec = do_query($conn,$ins);
    //echo $upt;
?>

<script>
    alert("<?echo $msg;?>");
    window.close();
</script>