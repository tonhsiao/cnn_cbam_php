<!doctype html>
<html lang="en">
  <head>
    <title>客訴工單-AI模型分類</title>


<? include("cott_header.php"); ?>

  </head>
  <body>

    <? include("cott_nav.php"); ?>

    <?php
    if($cookie_nt ==""){
        chk_user("cott_predict.php");
        }

    if($cookie_group=='ND' || $cookie_dir =='DIR' || $cookie_nt=='noc'){} else {
    check_access(100);
    }

    if($cookie_nt != 'nhsiao'){
        $log_sel = " INSERT INTO nocadm.cott_predict_login_rec (login_time, login_nt, login_name, login_dept, condition) values (sysdate,'$cookie_nt','$cookie_e_name $cookie_c_name','$cookie_deptcode $cookie_ccname', 'Main Page') ";
        $log_rec = do_query($conn,$log_sel);
        }
        ?>

    ?>
        <div class="p-5  border-bottom">
            <!--<h1>客訴工單-預測分類</h1>-->
            <div class="col-sm-10 col-lg-10 mx-auto text-center">  <!--mx-auto-->
                <p class="h2 m-4 font-weight-bold">COTT客訴診斷查詢</p>
                
       <form id="checkform" name="checkform">
        <!--Section: Demo-->
        <section class="">
            <div class="bg-white">
                <div class="content mt-3"> <span id="ttid_error" class="text-danger"></span></div>

                <div class="text-center" id="loading" style="display:none;">
                        <div class="spinner-border text-primary text-center" role="status" >
                        </div>
                </div>

            <section class="w-100 p-3 text-center d-flex justify-content-center">
            
                <div class="form-outline border rounded-2" style="width: 22rem;">
                    <div class="form-group">
                    
                    <textarea class="form-control" name="ttid" id="ttid"  rows="4" placeholder="請輸入客訴工單編號"></textarea>
                        
                    </div>
                </div>
            </section>
            <p class=" m-0 text-muted">
                    多筆資料，請以 <mark>"逗點"</mark> 隔開。可<mark>模糊比對</mark>。</p>
            <p class="p-1 mt-1 text-muted text-center">(前1日工單:需<mark>今日11:00</mark>才會產生喔)</p>

                <input type="submit" id="save" class="btn btn-primary btn-lg mt-3" value="查詢">
            </div>
        </div>
    </FORM>

    <FORM NAME="checkform1" ACTION="" METHOD=POST>
    <div class="py-5 text-left">
        <div class="container">
            <div id="result" class="row g-4 align-items-start justify-content-center">
            </div>
        </div>
    </div>
    </form>

    <? include("cott_footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

    $(document).ready(function() {

        $('#checkform').on('submit', function(event){
            event.preventDefault();
            var count_error = 0;

            if($('#ttid').val() == ''){
                $('#ttid_error').text('您忘了輸入客訴工單編號囉～');
                count_error++;
            }else{
                $('#ttid_error').text('');
            }
            if(count_error == 0){
                
                $('#loading').css('display', 'block');

                $.ajax({
                    url:"cott_predict_search.php",
                    method: "POST",
                    data:$(this).serialize(),
                    success:function(data){
                        location.href = "#save"
                        $('#result').html(data);
                        $('#ttid_error').text('');
                        $('#loading').css('display', 'none');
                    },
                    error: function(jqXHR) {
                        console.log(jqXHR);
                        console.log('Data Failed');
                        $('#result').html('<font color="#ff0000">資料有誤, 請再確認看看. ' + jqXHR.status + '</font>');
                }
                });
            } else {
                return false;
            }
        });

    });

</script>
  </body>
</html>
