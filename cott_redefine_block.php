<div class="col-sm-8 px-2">
                <div class="card shadow-sm ">
                    <div class="card-body" >
                        <div class="d-flex">
                            <h3 class="card-title pe-2"><? echo $row[ITT_ID];?></h3>
                            <select class="form-select-sm" name="checked_<?echo $row[ITT_ID];?>" >
                                    <option >--NOC確認--</option>
                                    <?
                                    for($a=0; $a<count($arr_checked_menu); $a++){
                                        $if_selected = "";
                                        if($row[CHECKED_TYPE]==$arr_checked_menu[$a]){
                                            $if_selected = " selected";
                                        }
                                    ?>
                                    <option value="<?echo $arr_checked_menu[$a];?>" <?echo $if_selected;?>><?echo $arr_checked_menu[$a];?></option>
                                    <? } ?>
                                    </select>

                                    <button class="btn btn-success btn-sm" onclick="open_win2('upt_ai_type.php?itt_id=<?echo $row[ITT_ID];?>&ai_check='+document.checkform.checked_<?echo $row[ITT_ID];?>.value, 500, 150, 400, 200);return false;">NOC確認</button>
                        </div>


                        <p class="card-text  "><mark>客戶:</mark><? echo $CUS_INFO.$row[CITY].$row[DISTRICT].$row[ROAD];?></p>
                        <div class="d-flex">
                            <div class="col-sm-5">
                            <p class="card-text "><mark>AI分類:</mark><span class="text-danger"><? echo $row[PREDICT_DESC];?> </span></p>
                            </div>
                            <div class="col-sm-7">
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
                                <button class="btn btn-primary btn-sm" onclick="open_win('AI類別修正, 加入再訓練, 您確定嗎?','upt_ai_type.php?itt_id=<?echo $row[ITT_ID];?>&ai_type='+document.checkform.ai_type_<?echo $row[ITT_ID];?>.value);return false;">AI類別修正</button>
                                <br><p class="card-text ">(By <? echo $row[REDEFINED_BY];?>)</p>
                            </div>
                        </div>
                        
                        <p class="card-text "><mark>RO分類:</mark><span class="text-danger"><? echo $row[FINETUNE_REASON];?></span></p>
                        <p class="card-text "><mark>RO結案:</mark><? echo $row[COMPLETION_REASON];?></p>
                        <p class="card-text"><mark>目前處理單位:</mark><? echo $row[CURRENT_WORKGROUP_CODE];?></p>
                        <p class="card-text"><mark>客訴內容:</mark><? echo $row[DESCRIPTION];?></p>
                    </div>
                </div> 
            </div>
            <div class="col-sm-4 px-2">
                <a href="./cott/<? echo $row[ITT_ID]; ?>.png" target="_blank">
                    <img src="./cott/<? echo $row[ITT_ID]; ?>.png" alt="" class="card-img-top">
                </a>
            </div>