<?
$eDate  = date("Y-m-d", mktime (0,0,0,substr($itt_id,3,2),substr($itt_id,5,2),substr($itt_id,1,2)));
$sDate = date( "Y-m-d", strtotime( "$eDate -30 day" ) );

$sel = "select distinct to_char(event_time,'MM/DD HH24:MI') event_time,  nvl2(clear_time,to_char(clear_time,'MM/DD HH24:MI'),'未結') clear_time, site, decode(site_status,'DOWN','DOWN','') site_status, outage_level, noctt, noctt_closurecode, cr_id, cr_sub_category, nvl2(plan_start_date,to_char(plan_start_date,'MM/DD HH24:MI'),'') plan_start_date,nvl2(plan_end_date,to_char(plan_end_date,'MM/DD HH24:MI'),'') plan_end_date, city, division, worksheet_view.getdistance($lati,$longi,lati,longi) distance from nois.all_disable_history_info_cnn a where (((event_time between '$sDate' and '$eDate') and regexp_like(cr_sub_category,'拆站')) or ((event_time between '$eDate' and '$eDate 23:59') and (cr_sub_category is null or regexp_like(cr_sub_category,'[^拆站]')))) and worksheet_view.getdistance($lati,$longi,lati,longi)<=2 order by worksheet_view.getdistance($lati,$longi,lati,longi) ";

$rec = do_query($conn,$sel);
$nrows = oci_fetch_all($rec,$res);

$rec = do_query($conn,$sel);

?>

