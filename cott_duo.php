<!DOCTYPE html>
<html lang="en" >

<head>

  <title>AI機制說明</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    
<style>
:root {
    --prm-color: #0381ff;
    --prm-gray: #b1b1b1;
}
/*  unnecessary */
body {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: space-evenly;
  flex-direction:column;
}
section{
  width:100%;
}
/*  unnecessary finished*/

/* CSS */
.steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    position: relative;
}

.step-button {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    background-color: var(--prm-gray);
    transition: .4s;
}

.step-button[aria-expanded="true"] {
    width: 60px;
    height: 60px;
    background-color: var(--prm-color);
    color: #fff;
}

.done {
    background-color: var(--prm-color);
    color: #fff;
}

.step-item {
    z-index: 10;
    text-align: center;
}

#progress {
  -webkit-appearance:none;
    position: absolute;
    width: 95%;
    z-index: 5;
    height: 10px;
    margin-left: 18px;
    margin-bottom: 18px;
}

/* to customize progress bar */
#progress::-webkit-progress-value {
    background-color: var(--prm-color);
    transition: .5s ease;
}

#progress::-webkit-progress-bar {
    background-color: var(--prm-gray);

}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>
  
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>

<? 
include("cott_header.php"); 

if($cookie_group=='ND' || $cookie_dir =='DIR' || $cookie_nt=='noc'){} else {
    check_access(100);
    }

if($cookie_nt != 'nhsiao'){
    $log_sel = " INSERT INTO nocadm.cott_predict_login_rec (login_time, login_nt, login_name, login_dept, condition) values (sysdate,'$cookie_nt','$cookie_e_name $cookie_c_name','$cookie_deptcode $cookie_ccname', 'DUO PAGE') ";
    $log_rec = do_query($conn,$log_sel);
    }
?>

</head>

<body translate="no" >

<? include("cott_nav.php"); ?>

  <div class="p-2 text-center">  <!--mx-auto-->
    <p class="h2 font-weight-bold">AI智能雙模 同步機制</p><!--  m-4 -->
</div>

<section>
        <div class="container">
            <div class="accordion" id="accordionExample">
                <div class="steps">
                    <progress id="progress" value=0 max=100 ></progress>
                    <div class="step-item">
                        <button class="step-button text-center" type="button" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            每日9:30
                        </button>
                        <div class="step-title">
                            取得CEM及網管重要參數
                        </div>
                    </div>
                    <div class="step-item">
                        <button class="step-button text-center collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            每日09:40
                        </button>
                        <div class="step-title">
                            將數據圖像化, 利用AI雙模進行診斷
                        </div>
                    </div>
                    <div class="step-item">
                        <button class="step-button text-center collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            每日11:00
                        </button>
                        <div class="step-title">
                            將診斷結果, 上傳至NOIS
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div  id="headingOne">
                        
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            每天9:30定期由NPSA提供<mark class="bg-warning">前一日</mark>的客戶工單的重要資訊, 包含來自CEM及網管重要參數, 針對數據型的資訊, 再作轉成圖像化。 
                        </div>
                        <div class="col-sm-7 px-2">
                            <img src="../assets/images/process1.jpg" alt="" class="card-img-top img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div  id="headingTwo">
                        
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                           每日9:40 採用<mark class="bg-warning">AI雙模型</mark>機制, 進行客訴診斷。<br>
                           此機制採用：CNN: Convolution Neural Network卷積神經網路, 並結合CBAM: Convolutional Block Attention Module，模型訓練結果準確率可達<mark class="bg-warning">93.6%</mark>.
                        </div>
                        <div class="col-sm-7 px-2">
                            <img src="../assets/images/process2.jpg" alt="" class="card-img-top img-fluid rounded">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div  id="headingThree">
                        
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            接著將AI診斷結果，同步至NOIS網站，即可提供同仁查詢，作進一步的確認。
                        </div>
                        <div class="col-sm-7 px-2">
                            <img src="../assets/images/process3.jpg" alt="" class="card-img-top img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-1b93190375e9ccc259df3a57c1abc0e64599724ae30d7ea4c6877eb615f89387.js"></script>

  <script src='https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js'></script>
      <script id="rendered-js" >
const stepButtons = document.querySelectorAll('.step-button');
const progress = document.querySelector('#progress');

Array.from(stepButtons).forEach((button, index) => {
  button.addEventListener('click', () => {
    progress.setAttribute('value', index * 100 / (stepButtons.length - 1)); //there are 3 buttons. 2 spaces.

    stepButtons.forEach((item, secindex) => {
      if (index > secindex) {
        item.classList.add('done');
      }
      if (index < secindex) {
        item.classList.remove('done');
      }
    });
  });
});
//# sourceURL=pen.js
    </script>

  

</body>

</html>
 
