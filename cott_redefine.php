<!doctype html>
<html lang="en">
  <head>
    <title>AI模型再進化</title>

<? include("cott_header.php"); ?>

  </head>
  <body>
    <!-- <nav class="navbar bg-success navbar-nav p-1 navbar-expand-lg fixed-top ">
        <div class="container">
            <span class="navbar-brand text-light ">AI智能 客訴診斷</span>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item text-light">
                        <a class="nav-link text-light" href="cott_redefine.php">AI再進化</a>
                    </li>
                    <li class="nav-item text-light">
                        <a class="nav-link text-light" href="cott_duo.php">AI雙模機制</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->
    <? include("cott_nav.php"); ?>

    <?php
    if($cookie_nt ==""){
        chk_user("cott_predict.php");
        }

    if($cookie_costcenter=='2512'  || $cookie_nt=='noc'){} else {
    check_access(100);
    }

    if($cookie_nt != 'nhsiao'){
    $log_sel = " INSERT INTO nocadm.cott_predict_login_rec (login_time, login_nt, login_name, login_dept, condition) values (sysdate,'$cookie_nt','$cookie_e_name $cookie_c_name','$cookie_deptcode $cookie_ccname', 'REDEFINED PAGE') ";
    $log_rec = do_query($conn,$log_sel);
    }
    ?>
<FORM NAME="checkform" ACTION="" METHOD=POST>
<div class="p-5 text-center">  <!--mx-auto-->
    <p class="h2 font-weight-bold">AI客訴診斷再進化</p><!--  m-4 -->
</div>

<div class="py-2">
    <div class="container">
        <div class="row g-2 align-items-start justify-content-center">
            <nav class="bg-warning  font-weight-bold" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-weight-bold" aria-current="page">待確認工單</li>
            </ol>
            </nav>
        <?php
            $arr_checked_menu = array("加入再訓練","待確認","不適合訓練");

            $drop_down_sel = "select * from nocadm.predict_type_desc where predict_type not in ('DataInsufficient','Weakarea') order by predict_desc";
            $arr_dropmenu = array();
            $arr_dropmenu_desc = array();
            $rec_drop_menu = do_query($conn,$drop_down_sel);
            while ($row_drop_menu = oci_fetch_array ($rec_drop_menu, OCI_ASSOC+OCI_RETURN_NULLS)) {
                array_push($arr_dropmenu,$row_drop_menu[PREDICT_TYPE]);
                array_push($arr_dropmenu_desc,$row_drop_menu[PREDICT_DESC]);
            }

            $sel = "select a.*, b.*, c2.predict_desc, decode(is_weakarea,'Y','弱訊區',PREDICT_DESC) PREDICT_DESC from nocadm.predict_cott a left join nocadm.itsmrpt_cott b on a.cott=b.itt_id left join nocadm.predict_type_desc c2 on a.predict_type=c2.predict_type where redefined_type is not null and checked_type is null";
            $sel .=  " order by itt_id desc";
            /*
            if($cookie_nt=='nhsiao'){
                echo $sel;
            }
            */
            $rec = do_query($conn,$sel);
            while ($row = oci_fetch_array ($rec, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $CUS_INFO = '';
                if($row[SITE_ID]) {  $CUS_INFO = "(".$row[SITE_ID].") "; }
                if($row[MOBILE_MODEL] && strlen($row[MOBILE_MODEL])>3) {  $CUS_INFO .= $row[MOBILE_MODEL].", "; }
                if($row[CALLER_TEL]) {  $CUS_INFO .= $row[CALLER_TEL].", "; }

            include("cott_redefine_block.php");
            }//while
            ?>
        <!-- <div class="p-2 border-top m-5 "> -->
        <nav class=" bg-secondary text-white font-weight-bold" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page">已確認工單</li>
            </ol>
            </nav>
        <?
        // 己結清單
        while ($row_drop_menu = oci_fetch_array ($rec_drop_menu, OCI_ASSOC+OCI_RETURN_NULLS)) {
            array_push($arr_dropmenu,$row_drop_menu[PREDICT_TYPE]);
            array_push($arr_dropmenu_desc,$row_drop_menu[PREDICT_DESC]);
        }

        $sel = "select a.*, b.*, c2.predict_desc, decode(is_weakarea,'Y','弱訊區',PREDICT_DESC) PREDICT_DESC from nocadm.predict_cott a left join nocadm.itsmrpt_cott b on a.cott=b.itt_id left join nocadm.predict_type_desc c2 on a.predict_type=c2.predict_type where checked_type is not null ";
        $sel .=  " order by itt_id desc";
        /*
        if($cookie_nt=='nhsiao'){
            echo $sel;
        }
        */
        $rec = do_query($conn,$sel);
        while ($row = oci_fetch_array ($rec, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $CUS_INFO = '';
            if($row[SITE_ID]) {  $CUS_INFO = "(".$row[SITE_ID].") "; }
            if($row[MOBILE_MODEL] && strlen($row[MOBILE_MODEL])>3) {  $CUS_INFO .= $row[MOBILE_MODEL].", "; }
            if($row[CALLER_TEL]) {  $CUS_INFO .= $row[CALLER_TEL].", "; }

        include("cott_redefine_block.php");
        }//while
        ?>
        
            </div>
        </div>
    </div>
</form>

    <? include("cott_footer.php"); ?>
  </body>
</html>