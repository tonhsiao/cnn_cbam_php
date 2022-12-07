<TItle>工單1公里內之故障基站</TItle>

<? include("cott_header.php"); 

$eDate  = date("Y-m-d", mktime (0,0,0,substr($itt_id,3,2),substr($itt_id,5,2),substr($itt_id,1,2)));
$sDate = date( "Y-m-d", strtotime( "$eDate -30 day" ) );
$eDate2 = $create_date;
$sDate2 = date( "Y-m-d h:m", strtotime( "$create_date -1 day" ) );

/*
$sel = "select distinct to_char(event_time,'MM/DD HH24:MI') event_time,  nvl2(clear_time,to_char(clear_time,'MM/DD HH24:MI'),'未結') clear_time, site, decode(site_status,'DOWN','DOWN','') site_status, outage_level, noctt, noctt_closurecode, cr_id, cr_sub_category, nvl2(plan_start_date,to_char(plan_start_date,'MM/DD HH24:MI'),'') plan_start_date,nvl2(plan_end_date,to_char(plan_end_date,'MM/DD HH24:MI'),'') plan_end_date, city, division, worksheet_view.getdistance($lati,$longi,lati,longi) distance from nois.all_disable_history_info_cnn a where (((event_time between '$sDate' and '$eDate') and regexp_like(cr_sub_category,'拆站')) or ((event_time between '$sDate2' and '$eDate2') and (cr_sub_category is null or regexp_like(cr_sub_category,'[^拆站]')))) and worksheet_view.getdistance($lati,$longi,lati,longi)<=1 order by worksheet_view.getdistance($lati,$longi,lati,longi) ";
*/

$sel = "select a.*, to_char(event_time,'MM/DD HH24:MI') event_time,  nvl2(clear_time,to_char(clear_time,'MM/DD HH24:MI'),'未結') clear_time from nocadm.predict_cott_disable_sites a where itt_id='$itt_id' order by distance , a.site, a.event_time";

/*
if($cookie_nt=='nhsiao' ){
    echo $sel;
}
*/

?>
<div class="card shadow-lg mx-5 my-2">
    <div class="container">
      <div>
        <span id="success_message"></span>
      </div>
        <table class="table table-hover my-5">
          <thead  class="table-info">
            <tr>
              <th scope="col">#</th>
              <th scope="col">SiteID</th>
              <th scope="col">故障等級</th>
              <th scope="col">縣市</th>
              <th scope="col">故障時間</th>
              <th scope="col">NOC T.T.</th>
              <th scope="col">CR資訊</th>
              <th scope="col">CR預估執行時間</th>
              <th scope="col">與客訴工單的距離</th>
            </tr>
          </thead>
          <div class="form-group" id="process" style="display:block;">
            <div class="progress">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" vria-valuemax="100"></div>

            </div>

          </div>
          <tbody>
        <?
        //取得筆數
        $rec = do_query($conn,$sel);
        $nrows = oci_fetch_all($rec,$res);

        $rec = do_query($conn,$sel);
        while ($row = oci_fetch_array ($rec, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $i++;
        ?>
            <tr>
              <th scope="row"><?echo $i;?></th>
              <td>
                <?
                  echo $row[SITE];
                  if($row[SITE_STATUS]) {echo '<p class="text-danger"><br>(Site Down)</p>';}
                ?>
              </td>
              <td><?echo $row[OUTAGE_LEVEL];?></td>
              <td><?echo $row[CITY].$row[DIVISION];?></td>
              <td><? echo $row[EVENT_TIME]."～".$row[CLEAR_TIME];?></td>
              <td><?
                    if($row[NOCTT]){
                      echo 'NOCTT-'.$row[NOCTT].":".$row[NOCTT_CLOSURECODE];
                    }
                  
                  ?></td>
              <td><?
                    if($row[CR_ID]){
                      echo 'CR-'.$row[CR_ID].':'.$row[CR_SUB_CATEGORY];
                    }    
                  ?></td>
              <td> <? 
              if($row[CR_ID]){
                  echo $row[PLAN_START_DATE]."～".$row[PLAN_END_DATE];
              }
              ?> </td>
              <td><?echo number_format(round($row[DISTANCE]*1000))."公尺";?></td>
            </tr>
        <?
        }
        if(!$nrows){ echo '<tr><td><p class="text-danger">查無1公里內故障基站</p><td></tr>'; }

        ?>
        
          </tbody>
        </table>
  </div>
</div>

<div class="my-5 text-center">
    <button type="button" class="btn btn-primary"  onclick="window.opener=null;window.close();">關閉視窗</button>
</div>
<script>
  //https://www.youtube.com/watch?v=7mLaJFaYED4
  //https://www.webslesson.info/2019/09/how-to-create-progress-bar-for-data-insert-in-php-using-ajax.html



</script>

<? include("cott_footer.php"); ?>