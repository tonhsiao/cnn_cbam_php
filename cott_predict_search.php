
<div class="py-5 text-left">
    <div class="container">
        <div class="row g-4 align-items-start justify-content-center">

    <?php

        require("../../phpfunc/db_connect.php");
    

        if($cookie_nt != 'nhsiao'){
            $log_sel = " INSERT INTO nocadm.cott_predict_login_rec (login_time, login_nt, login_name, login_dept, condition) values (sysdate,'$cookie_nt','$cookie_e_name $cookie_c_name','$cookie_deptcode $cookie_ccname', '$_POST[ttid]') ";
            $log_rec = do_query($conn,$log_sel);
            }

            $drop_down_sel = "select * from nocadm.predict_type_desc where predict_type not in ('DataInsufficient','Weakarea') order by predict_desc";
            $arr_dropmenu = array();
            $arr_dropmenu_desc = array();
            $rec_drop_menu = do_query($conn,$drop_down_sel);
            while ($row_drop_menu = oci_fetch_array ($rec_drop_menu, OCI_ASSOC+OCI_RETURN_NULLS)) {
                array_push($arr_dropmenu,$row_drop_menu[PREDICT_TYPE]);
                array_push($arr_dropmenu_desc,$row_drop_menu[PREDICT_DESC]);
            }

        if(isset($_POST['ttid'])){
            $yesterday  = date("ymd", mktime (0,0,0,date("m"),date("d")-1,date("Y")));   
            if(date("H:i") < "11:00" && strpos($ttid, "M".$yesterday) !== false) {
                echo '<script>alert("昨日工單需等11:00才會產生喔！")</script>';
            }elseif(strpos($ttid, "M".date("ymd")) !== false) {
                echo '<script>alert("今日工單需等明日11:00才會產生喔！")</script>';
            }
            
            $sel = "select a.*, b.*, c2.predict_desc, decode(is_weakarea,'Y','弱訊區',PREDICT_DESC) PREDICT_DESC from nocadm.predict_cott a left join nocadm.itsmrpt_cott b on a.cott=b.itt_id left join nocadm.predict_type_desc c2 on a.predict_type=c2.predict_type where (";
            $cott_arr = explode(",",$_POST['ttid']);
            for($i=0;$i<count($cott_arr);$i++){
                if($cott_arr[$i]){
                $sel .= " itt_id like '%".strtoupper(trim($cott_arr[$i]))."%' or"; 
                }
            }
            $sel =  substr($sel,0,-2). " ) and rownum<100 order by itt_id desc";
        
            $rec = do_query($conn,$sel);
            $nrows = oci_fetch_all($rec,$res);

            $rec = do_query($conn,$sel);
            if($nrows>1) { $grid = "col-lg-6 col-xxl-4"; } else { $grid = "col-lg-10"; }

            while ($row = oci_fetch_array ($rec, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $CUS_INFO = '';
                if($row[SITE_ID]) {  $CUS_INFO = "(".$row[SITE_ID].") "; }
                if($row[MOBILE_MODEL] && strlen($row[MOBILE_MODEL])>3) {  $CUS_INFO .= $row[MOBILE_MODEL].", "; }
                if($row[CALLER_TEL]) {  $CUS_INFO .= $row[CALLER_TEL].", "; }

                for($t=1; $t<4; $t++){
                    ${"time".$t."_site"} = "";
                    ${"time".$t."_dis"} = "";
                    ${"btime".$t."_site"} = "";
                    ${"btime".$t."_dis"} = "";
                }

                
                $sel0 = "select * from  nocadm.predict_cott_sites where itt_id='$row[ITT_ID]'";
                $rec0 = do_query($conn,$sel0);
                while ($row0 = oci_fetch_array ($rec0, OCI_ASSOC+OCI_RETURN_NULLS)) {

                    ${$row0['SITE_TYPE'].'_site'} = $row0['SITE'];
                    ${$row0['SITE_TYPE'].'_dis'} = $row0['SITE_DIS'];
                    ${$row0['SITE_TYPE'].'_rsrp_mean'} = $row0['POS_FIRST_RSRP_MEAN'];
                    ${$row0['SITE_TYPE'].'_rsrp_count'} = $row0['POS_FIRST_RSRP_COUNT'];

                    ${$row0['SITE_TYPE'].'_prb_mean'} = $row0['C_PRBUTIL_MEAN'];
                    ${$row0['SITE_TYPE'].'_prb_count'} = $row0['C_PRBUTIL_COUNT'];

                    ${$row0['SITE_TYPE'].'_rssi_mean'} = $row0['C_RSSI_MEAN'];
                    ${$row0['SITE_TYPE'].'_rssi_count'} = $row0['C_RSSI_COUNT'];

                    ${$row0['SITE_TYPE'].'_dltput_mean'} = $row0['DL_TPUT_MEAN'];
                    ${$row0['SITE_TYPE'].'_dltput_count'} = $row0['DL_TPUT_COUNT'];

                    ${$row0['SITE_TYPE'].'_rsrq_mean'} = $row0['POS_LAST_RSRQ_MEAN'];
                    ${$row0['SITE_TYPE'].'_rsrq_count'} = $row0['POS_LAST_RSRQ_COUNT'];

                    ${$row0['SITE_TYPE'].'_cqi_mean'} = $row0['END_CQI_MEAN'];
                    ${$row0['SITE_TYPE'].'_cqi_count'} = $row0['END_CQI_COUNT'];
                }
            
            ?>


                <div class="<?php echo $grid;?> px-2">
                   <div class="card shadow-lg ">
                       <div class="card-body p-3" >
                           <p class="fs-3">
                                <? echo $row[ITT_ID]."   ";?>
                                <button type="button" class="btn btn-outline-danger"  onclick="open_win2('cott_siteinfo.php?itt_id=<?echo $row[ITT_ID];?>&lati=<?echo $row[GIS_Y_84];?>&longi=<?echo $row[GIS_X_84];?>&create_date=<?echo $row[CREATE_DATE];?>', 1300, 600, 50, 50);return false;">工單1公里內的故障基站</button></p>
                           <!-- <h3 class="card-title"><? echo $row[ITT_ID];?></h3>
                            900, 500, 400, 200
                            -->
                           
                           <p class="card-text  "><mark>客戶:</mark><? echo $CUS_INFO.$row[CITY].$row[DISTRICT].$row[ROAD];?></p>
                           <p class="card-text  "><mark>4天內,次數最多的基站:</mark>
                           <mark class="text-primary">(與工單的距離)</mark><br>
                           ❶08:00-12:00：<? echo $time1_site;?> (<? echo $time1_dis."公里";?>)<br>
                           ❷12:00-18:00：<? echo $time2_site;?> (<? echo $time2_dis."公里";?>)<br>
                           ❸18:00-24:00：<? echo $time3_site;?> (<? echo $time3_dis."公里";?>)
                        </p>
                        <p class="card-text  "><mark class="text-danger">24小時內, 體驗不佳</mark><mark>,且次數最多的基站:</mark><mark class="text-primary">(與工單的距離,參數平均值)</mark>
                           <br>
                           ❶08:00-12:00：<? $br=0; echo $btime1_site;?> (<? echo $btime1_dis."公里";?>)
                           <? if($btime1_rsrp_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSRP: ".$btime1_rsrp_mean ."dBm(<mark class=\"text-danger\">".round(($btime1_rsrp_count/$time1_rsrp_count)*100,1). "%</mark>), "; } 
                           
                            if($btime1_prb_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "PRB: ".round($btime1_prb_mean*100,1) ."%(<mark class=\"text-danger\">".round(($btime1_prb_count/$time1_prb_count)*100,1). "%</mark>) "; } 

                            if($btime1_rssi_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSSI: ".$btime1_rssi_mean ."dBm(<mark class=\"text-danger\">".round(($btime1_rssi_count/$time1_rssi_count)*100,1). "%</mark>), "; } 

                            if($btime1_rsrq_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSRQ: ".$btime1_rsrq_mean ."dB(<mark class=\"text-danger\">".round(($btime1_rsrq_count/$time1_rsrq_count)*100,1). "%</mark>), "; } 

                            if($btime1_cqi_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "CQI: ".$btime1_cqi_mean ."(<mark class=\"text-danger\">".round(($btime1_cqi_count/$time1_cqi_count)*100,1). "%</mark>), "; } 

                            if($btime1_dltput_mean || $btime1_dltput_count){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "DL TPUT: ".$btime1_dltput_mean ."Mbps(<mark class=\"text-danger\">".round(($btime1_dltput_count/$time1_dltput_count)*100,1). "%</mark>)"; } 
                            if($br>0){echo '</mark>';} ?>
                           
                           <BR>❷12:00-18:00：<? $br=0; echo $btime2_site." (".  $btime2_dis."公里";?>)
                           <? if($btime2_rsrp_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSRP: ".$btime2_rsrp_mean ."dBm(<mark class=\"text-danger\">".round(($btime2_rsrp_count/$time2_rsrp_count)*100,1). "%</mark>), "; } ?>
                           
                           <? if($btime2_prb_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "PRB: ".round($btime2_prb_mean*100,1) ."%(<mark class=\"text-danger\">".round(($btime2_prb_count/$time2_prb_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime2_rssi_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSSI: ".$btime2_rssi_mean ."dBm(<mark class=\"text-danger\">".round(($btime2_rssi_count/$time2_rssi_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime2_rsrq_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSRQ: ".$btime2_rsrq_mean ."dB(<mark class=\"text-danger\">".round(($btime2_rsrq_count/$time2_rsrq_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime2_cqi_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "CQI: ".$btime2_cqi_mean ."(<mark class=\"text-danger\">".round(($btime2_cqi_count/$time2_cqi_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime2_dltput_mean || $btime2_dltput_count){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "DL TPUT: ".$btime2_dltput_mean ."Mbps(<mark class=\"text-danger\">".round(($btime2_dltput_count/$time2_dltput_count)*100,1). "%</mark>)"; } ?>
                           <? if($br>0){echo '</mark>';} ?>
                           
                           <BR>❸18:00-24:00：<? $br=0; echo $btime3_site;?> (<? echo $btime3_dis."公里";?>)
                           <? if($btime3_rsrp_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSRP: ".$btime3_rsrp_mean ."dBm(<mark class=\"text-danger\">".round(($btime3_rsrp_count/$time3_rsrp_count)*100,1). "%</mark>), "; } ?>
                           
                           <? if($btime3_prb_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "PRB: ".round($btime3_prb_mean*100,1) ."%(<mark class=\"text-danger\">".round(($btime3_prb_count/$time3_prb_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime3_rssi_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSSI: ".$btime3_rssi_mean ."dBm(<mark class=\"text-danger\">".round(($btime3_rssi_count/$time3_rssi_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime3_rsrq_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "RSRQ: ".$btime3_rsrq_mean ."dB(<mark class=\"text-danger\">".round(($btime3_rsrq_count/$time3_rsrq_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime3_cqi_mean){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "CQI: ".$btime3_cqi_mean ."(<mark class=\"text-danger\">".round(($btime3_cqi_count/$time3_cqi_count)*100,1). "%</mark>), "; } ?>

                           <? if($btime3_dltput_mean || $btime3_dltput_count){ 
                               ++$br;
                               if($br==1){echo '<br>★<mark class=\"bg-light\">';}
                               echo "DL TPUT: ".$btime3_dltput_mean ."Mbps(<mark class=\"text-danger\">".round(($btime3_dltput_count/$time3_dltput_count)*100,1). "%</mark>)"; } ?>
                           <? if($br>0){echo '</mark><br>';} 
                           
                            //reset parameter
                            $para = array('time1','time2','time3','btime1','btime2','btime3');
                            for($p=0;$p<count($para);$p++){
                                ${$para[$p].'_site'} = '';
                                ${$para[$p].'_dis'} = '';
                                ${$para[$p].'_rsrp_mean'} = '';
                                ${$para[$p].'_rsrp_count'} = '';
                                ${$para[$p].'_prb_mean'} = '';
                                ${$para[$p].'_prb_count'} = '';
                                ${$para[$p].'_rssi_mean'} = '';
                                ${$para[$p].'_rssi_count'} = '';
                                ${$para[$p].'_dltput_mean'} = '';
                                ${$para[$p].'_dltput_count'} = '';
                                ${$para[$p].'_rsrq_mean'} = '';
                                ${$para[$p].'_rsrq_count'} = '';
                                ${$para[$p].'_cqi_mean'} = '';
                                ${$para[$p].'_cqi_count'} = '';
                            }
                           ?>




                        </p>

                           <div class="d-flex">
                           <p class="card-text  ">
                               <mark>AI分類:</mark><span class="text-primary"><? echo $row[PREDICT_DESC];?> </span>
                               </p>
                               <select class="form-select-sm" name="ai_type_<?echo $row[ITT_ID];?>" >
                                <option >--新AI類別--</option>
                                <?
                                
                                for($a=0; $a<count($arr_dropmenu); $a++){
                                    $if_selected = "";
                                    if($row[REDEFINED_TYPE]==$arr_dropmenu[$a]){
                                        $if_selected = " selected";
                                    }
                                ?>
                                <option value="<?echo $arr_dropmenu[$a];?>" <?echo $if_selected;?>><?echo $arr_dropmenu_desc[$a];?></option>
                                <? } ?>
                                </select>
                                <button class="btn btn-primary btn-sm" onclick="open_win('AI類別修正, 加入再訓練, 您確定嗎?','upt_ai_type.php?itt_id=<?echo $row[ITT_ID];?>&ai_type='+document.checkform1.ai_type_<?echo $row[ITT_ID];?>.value);return false;">AI類別修正</button>
                                </div>
                           
                           <p class="card-text "><mark>RO分類:</mark><span class="text-primary"><? echo $row[FINETUNE_REASON];?></span></p>
                           <p class="card-text "><mark>RO結案:</mark><? echo $row[COMPLETION_REASON];?></p>
                           <p class="card-text"><mark>目前處理單位:</mark><? echo $row[CURRENT_WORKGROUP_CODE];?></p>
                           <p class="card-text"><mark>客訴內容:</mark><? echo $row[DESCRIPTION];?></p>
                           <!--<button class="btn btn-primary">開啟工單</button>-->
                       </div>
                       <a href="./cott/<? echo $row[ITT_ID]; ?>.png" target="_blank">
                            <img src="./cott/<? echo $row[ITT_ID]; ?>.png" alt="" class="card-img-top">
                       </a>
                   </div> 
                </div>
                
            <?php
            }//while
        }//IF COTT


            ?>
            </div>
        </div>
    </div>