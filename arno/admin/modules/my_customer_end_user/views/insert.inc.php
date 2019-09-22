<script>

var options_name_th = {
        url: function(keyword) {
            var after_key = keyword.replace(/[,.]/g, ' ').replace(/  +/g, ' ');
            return "controllers/getNameTHCustomerByAutoComplete.php?keyword="+after_key;
        },

        list: {
            maxNumberOfElements: 10,
            // match: {
            //     enabled: true
            // }
        },

        getValue: function(element) {
            return element.customer_name_th ;
        },

        template: {
            type: "description",
            fields: {
                description: "customer_name_th"
            }
        },
        
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.keyword = $(".name-th-search-ajax-post:focus").val();
            return data;
            console.log(data);
            
        },

        requestDelay: 400
    };

    var options_name_en = {
        url: function(keyword) {

            var after_key = keyword.toLowerCase().replace(/[,.]/g, ' ').replace(/  +/g, ' ');
            // console.log(keyword.length);
            // if(keyword.length > 3){
                // console.log("OK");
                return "controllers/getNameENCustomerByAutoComplete.php?keyword="+after_key;

            // }
        },

        list: {
            maxNumberOfElements: 10,
            // match: {
            //     enabled: true
            // }
        },

        getValue: function(element) {

            console.log("value",element);
            

            return element.customer_name_en ;
        },

        template: {
            type: "description",
            fields: {
                description: "customer_name_en"
            }
        },
        
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.keyword = $(".name-en-search-ajax-post:focus").val();
            return data;
        },

        requestDelay: 400
    };
    function check_code(){
        var code = $('#customer_code').val();
        $.post( "controllers/getCustomerByCode.php", { 'customer_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("customer_code").focus();
                $("#code_check").val(data.customer_id);
                
            } else{
                $("#code_check").val("");
            }
        });
    }

    
    function check(){


        var customer_code = document.getElementById("customer_code").value;
        var customer_name_th = document.getElementById("customer_name_th").value;
        var customer_name_en = document.getElementById("customer_name_en").value;
        var customer_type = document.getElementById("customer_type").value;
        var customer_tax = document.getElementById("customer_tax").value;
        var customer_address_1 = document.getElementById("customer_address_1").value;
        var customer_address_2 = document.getElementById("customer_address_2").value;
        var customer_address_3 = document.getElementById("customer_address_3").value;
        var customer_domestic = document.getElementById("customer_domestic").value;
        var customer_branch = document.getElementById("customer_branch").value;
        var code_check = document.getElementById("code_check").value; 
       
       
        customer_code = $.trim(customer_code);
        customer_name_th = $.trim(customer_name_th);
        customer_name_en = $.trim(customer_name_en);
        customer_type = $.trim(customer_type);
        customer_tax = $.trim(customer_tax);
        customer_address_1 = $.trim(customer_address_1);
        customer_address_2 = $.trim(customer_address_2);
        customer_address_3 = $.trim(customer_address_3);
        customer_domestic = $.trim(customer_domestic);
        customer_branch = $.trim(customer_branch);
        
        

        if(code_check != "" ){
            alert("This "+code_check+" is already in the system.");
            document.getElementById("customer_code").focus();
            return false;
        }else if(customer_code.length == 0){
            alert("Please input customer code");
            document.getElementById("customer_code").focus();
            return false;
        // }else if(customer_name_th.length == 0){
        //     alert("Please input customer name thai");
        //     document.getElementById("customer_name_th").focus();
        //     return false;
        }else  if(customer_name_en.length == 0){
            alert("Please input customer name english");
            document.getElementById("customer_name_en").focus();
            return false;
        // }else if(customer_type.length == 0){
        //     alert("Please input customer type");
        //     document.getElementById("customer_type").focus();
        //     return false;
        // }else if(customer_tax.length == 0){
        //     alert("Please input customer tax");
        //     document.getElementById("customer_tax").focus();
        //     return false;
        // }else if(customer_tax.length != 13){
        //     alert("Please input customer tax 13 digit");
        //     document.getElementById("customer_tax").focus();
        //     return false;
        // }else if(customer_address_1.length == 0 &&customer_address_2.length == 0 && customer_address_3.length == 0 ){
        //     alert("Please input customer address");
        //     document.getElementById("customer_address_1").focus();
        //     return false;
        // }else if(customer_branch.length == 0){
        //     alert("Please input customer branch");
        //     document.getElementById("customer_branch").focus();
        //     return false;
        }else{ 
            $('#customer_end_user').prop("disabled",false);
            return true;
        }



    }

    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }

    function readURL(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '../upload/customer/default.png');
        }
    }
 
    function update_code(){
        var customer_name = document.getElementById("customer_name_en").value;
        var dealer = document.getElementById("dealer").value;
        customer_name = $.trim(customer_name);
        if(customer_name.length > 0){
            $.post( "controllers/getCustomerCodeIndex.php", { 'char': '[E]-'+ customer_name.split('')[0].toUpperCase() }, function( data ) {
                var index = parseInt(data);
                document.getElementById("customer_code").value = '[E]-'+ customer_name.split('')[0].toUpperCase() + pad(index + 1,3);
            });
            
        }else{
            document.getElementById("customer_code").value = "";
        }
    }

    function set_end_user(){
       var customer_end_user_type = $("#customer_end_user_type").val();
       if(customer_end_user_type == 0){
            $('#customer_end_user').prop("disabled",true);
            $('#customer_end_user').val(0);
       }else{
            $('#customer_end_user').prop("disabled",false);
       }
       $('#customer_end_user').selectpicker('refresh');
    }
    function set_customer_approve(customer_approve){
        $('#customer_approve').val(customer_approve);
    }

</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">End User Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มลูกค้าปลายทาง / Add End User 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" id="form_target" method="post" onsubmit="return check();" action="index.php?app=my_customer_end_users&my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&action=add" enctype="multipart/form-data">  
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสลูกค้า / Customer code<font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code"   class="form-control" onchange="check_code()" />
                                        <input id="code_check" type="hidden" value="" />
                                        <input id="dealer" type="hidden" value="<?php echo $customer['customer_code']; ?>" />
                                        <input type="hidden" name="customer_approve" id="customer_approve" value="New" />
                                        <input id="customer_end_user_type" name="customer_end_user_type" type="hidden" value="1" />
                                        <input id="sale_id" name="sale_id" type="hidden" value="<?PHP echo $customer_id; ?>" />
                                        <p class="help-block">Example : R001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ชื่อลูกค้า (ไทย) / Customer name (Thai)</label>
                                        <input id="customer_name_th" name="customer_name_th" class="name-th-search-ajax-post form-control" onchange=""  autocomplete="off">
                                        <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ชื่อลูกค้า (อังกฤษ) / Customer name (English)<font color="#F00"><b>*</b></font></label>
                                        <input id="customer_name_en" name="customer_name_en" class="name-en-search-ajax-post form-control" onChange="update_code()" autocomplete="off">
                                        <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                
                                <div class="col-lg-4">
                                    
                                        <div class="form-group">
                                            <label>การจดทะเบียน / Company registration </label>
                                            <select id="customer_type" name="customer_type" class="form-control"> 
                                                <option>บริษัทจำกัด</option>
                                                <option>บริษัทมหาชนจำกัด</option>
                                                <option>ห้างหุ้นส่วน</option>
                                                <option>กิจการเจ้าของเดียว</option>
                                            </select>
                                            <p class="help-block">Example : บริษัทจำกัด.</p>
                                        </div>
                                    
                                </div>
                                <div class="col-lg-4">
                                    
                                        <div class="form-group">
                                            <label>เลขประจำตัวผู้เสียภาษี / Tax. </label>
                                            <input id="customer_tax" name="customer_tax" class="form-control">
                                            <p class="help-block">Example : 123456789012345.</p>
                                        </div>
                                    
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>พื้นที่จดทะเบียน / Domestic  </label>
                                        <select id="customer_domestic" name="customer_domestic" class="form-control"> 
                                                <option>ภายในประเทศ</option>
                                                <option>ภายนอกประเทศ</option>
                
                                            </select>
                                        <p class="help-block">Example : ภายนอกประเทศ.</p>
                                    </div>
                                </div>
                                
                                <!-- /.col-lg-6 (nested) -->
                            </div>

                            <!-- /.row (nested) -->
                            <div class="row">
                            
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch </label>
                                        <input id="customer_branch" name="customer_branch" class="form-control" />
                                        <p class="help-block">Example : 0000 = สำนักงานใหญ่.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>พื้นที่การขาย / Zone </label>
                                        <input id="customer_zone" name="customer_zone" type="text" class="form-control">
                                        <p class="help-block">Example : ชลบุรี.</p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>โทรศัพท์ / Telephone </label>
                                        <input id="customer_tel" name="customer_tel" type="text" class="form-control">
                                        <p class="help-block">Example : 023456789.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>แฟกซ์ / Fax </label>
                                        <input id="customer_fax" name="customer_fax" type="text" class="form-control">
                                        <p class="help-block">Example : 020265389-01.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>อีเมล์ / Email </label>
                                        <input id="customer_email" name="customer_email" type="email" class="form-control">
                                        <p class="help-block">Example : admin@arno.co.th.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 1 / Address 1 </label>
                                        <input id="customer_address_1" name="customer_address_1" type="text" class="form-control">
                                        <p class="help-block">Example : ตึกบางนาธานี.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 2 / Address 2 </label>
                                        <input id="customer_address_2" name="customer_address_2" type="text" class="form-control">
                                        <p class="help-block">Example : เขตบางนา.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ที่อยู่ 3 / Address 3 </label>
                                        <input id="customer_address_3" name="customer_address_3" type="text" class="form-control">
                                        <p class="help-block">Example : กรุงเทพ.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสไปรษณีย์ / Zipcode </label>
                                        <input id="customer_zipcode" name="customer_zipcode" type="text" class="form-control">
                                        <p class="help-block">Example : 20150.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale </label>
                                        <select id="sale_id" name="sale_id" class="form-control select" data-live-search="true" >
                                            <option value="">เลือก / Select</option>
                                            <?PHP 
                                                for($i=0; $i < count($user) ; $i++){
                                            ?>
                                                <option value="<?PHP echo $user[$i]['user_id'];?>" <?PHP if($user[$i]['user_id'] == $customer['sale_id'] ){ ?> SELECTED <? } ?> ><?PHP echo $user[$i]['name'];?>   (<?PHP echo $user[$i]['user_position_name'];?>)</option>
                                            <?PHP
                                                }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div> -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สิทธิ์การขาย / Sales privileges </label>
                                        <select id="customer_end_user" name="customer_end_user" class="form-control select" data-live-search="true" >
                                            <option value="0"> ไม่ระบุ</option>
                                            <?PHP 
                                                for($i=0; $i < count($customers) ; $i++){
                                            ?>
                                                <option value="<?PHP echo $customers[$i]['customer_id'];?>" <?PHP if($customers[$i]['customer_id'] == $customer_id ){ ?> SELECTED <? } ?> ><?PHP echo $customers[$i]['customer_name_en'];?></option>
                                            <?PHP
                                                }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>วันที่ เพิ่ม ลูกค้าปลายทาง / EndUser Add Date </label>
                                        <input type="text" id="customer_register_date" name="customer_register_date" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">Example : 01-01-2019.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ระยะเวลาทำการค้า / Trade Date </label>
                                        <input id="customer_register_relimit" name="customer_register_relimit" value="30" type="text" class="form-control">
                                        <p class="help-block">Example : 45.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สถานะการค้า / Trade status </label>
                                        <select id="customer_register_status" name="customer_register_status" class="form-control"> 
                                            <option>ลงทะเบียน</option>
                                            <option>ทดสอบ</option>
                                            <option>ขาย</option>
                                            <option>ยกเลิก</option>
                                        </select>
                                        <p class="help-block">Example : ไม่ดำเนินการ.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รูปลูกค้า / Customer Picture<font color="#F00"><b>*</b></font> </label>
                                        <img class="img-responsive" id="img_logo" src="../upload/customer/default.png" />
                                        <input accept=".jpg , .png"   type="file" id="customer_logo" name="customer_logo" onChange="readURL(this);">
                                        <p class="help-block">Example : .jpg or .png </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                    <hr>

                    <!-- <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทบัญชี / Account type </label>
                                <select id="account_id" name="account_id" class="form-control select" data-live-search="true"> 
                                    <?PHP 
                                        for($i=0; $i < count($account) ; $i++){
                                    ?>
                                        <option value="<?PHP echo $account[$i]['account_id'];?>" <?PHP if($account[$i]['account_code'] == '1131-01' ){ ?> SELECTED <? } ?> ><?PHP echo $account[$i]['account_code'];?> <?PHP echo $account[$i]['account_name_th'];?></option>
                                    <?PHP
                                        }
                                    ?>
                                </select>
                                <p class="help-block">Example : 2120-01.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                                <select id="vat_type" name="vat_type" class="form-control"> 
                                    <option value="0" >0 - ไม่มี Vat</option>
                                    <option value="1"  >1 - รวม Vat</option>
                                    <option value="2"  Selected >2 - แยก Vat</option>
                                </select>
                                <p class="help-block">Example : 0 - ไม่มี vat.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ภาษีมูลค่าเพิ่ม / Vat </label>
                                <input id="vat" name="vat" type="text" class="form-control"  style="text-align:right;" value="7">
                                <p class="help-block">Example : 7.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>สกุลเงิน / Curreny. </label>
                                <select id="currency_id" name="currency_id" class="form-control"> 
                                    <?PHP 
                                        for($i=0; $i < count($currency) ; $i++){
                                    ?>
                                        <option value="<?PHP echo $currency[$i]['currency_id'];?>" <?PHP if($currency[$i]['currency_id'] == 117){?> Selected <?PHP }?>><?PHP echo $currency[$i]['currency_code'];?> - <?PHP echo $currency[$i]['currency_name'];?></option>
                                    <?PHP
                                        }
                                    ?>
                                </select>
                                <p class="help-block">Example : 3.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>เครดิต / Credit Day </label>
                                <input id="credit_day" name="credit_day" type="text" class="form-control"  value="30" style="text-align:right;" value="<? echo $customer['credit_day']?>">
                                <p class="help-block">Example : 30 (วัน).</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เงื่อนไขการชำระเงิน / Pay Type </label>
                                <select id="condition_pay" name="condition_pay" class="form-control"> 
                                    <option <?PHP if($customer['condition_pay'] == 'เช็ค'){?>Selected <?PHP }?> >เช็ค</option>
                                    <option <?PHP if($customer['condition_pay'] == 'เงินสด'){?>Selected <?PHP }?> >เงินสด</option>
                                    <option <?PHP if($customer['condition_pay'] == 'โอนเงิน'){?>Selected <?PHP }?> >โอนเงิน</option>
                                </select>
                                <p class="help-block">Example : เงินสด.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทลูกค้า / Customer type </label>
                                <select id="customer_type_id" name="customer_type_id" class="form-control"> 
                                    <?PHP 
                                        for($i=0; $i < count($customer_types) ; $i++){
                                    ?>
                                        <option value="<?PHP echo $customer_types[$i]['customer_type_id'];?>" <?PHP if($customer_types[$i]['customer_type_id'] == $customer['customer_type_id']){?> Selected <?PHP }?>> <?PHP echo $customer_types[$i]['customer_type_name'];?></option>
                                    <?PHP
                                        }
                                    ?>
                                </select>
                                <p class="help-block">Example : Dealer.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>กลุ่มลูกค้า / Customer group </label>
                                <select id="customer_group_id" name="customer_group_id" class="form-control select" data-live-search="true"> 
                                    <?PHP  
                                        for($i=0; $i < count($customer_groups) ; $i++){
                                    ?>
                                        <option value="<?PHP echo $customer_groups[$i]['customer_group_id'];?>" <?PHP if($customer_groups[$i]['customer_group_id'] == $customer['customer_group_id']){?> Selected <?PHP }?>> <?PHP echo $customer_groups[$i]['customer_group_name'];?></option>
                                    <?PHP 
                                        }
                                    ?>
                                </select>
                                <p class="help-block">Example : Automotive.</p>
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="button" onclick="set_customer_approve('New');check_login('form_target');" class="btn btn-success">Save</button>
                            <button type="button" onclick="set_customer_approve('<?php echo $customer_approve; ?>');check_login('form_target');" class="btn btn-success">Save & Request</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<script>
    $(".name-th-search-ajax-post").easyAutocomplete(options_name_th);
    $(".name-en-search-ajax-post").easyAutocomplete(options_name_en);
$( document ).ready(function() {
    $('#sale_id').selectpicker('refresh'); 
    $('#customer_end_user').selectpicker('refresh');
});
</script>