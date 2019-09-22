<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/font-awesome.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    
    <title>Calendar Event</title>
</head>
<body>

    <div id="draggable" class="fixed">
        
        <div id="fixed_body" class="fixed-body">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(0);" class="link"><span class="date-select color-today"></span> วันนี้ </a>
                </div>
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(1);" class="link"><span class="date-select color-event"></span> วันหยุด </a>
                </div>
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(2);" class="link"><span class="date-select color-bill"></span> วันวางบิล </a>
                </div>
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(3);" class="link"><span class="date-select color-invoice"></span> วันรับ Invoice ช้าสุด </a>
                </div>
            </div>
            <div class="row" align="right">
                <div class="col-md-12">
                    <button class="btn btn-primary " style="margin-right:4px;" onclick="location.reload();">Refresh</button>
                    <button class="btn btn-success " style="margin-right:4px;">Save</button>
                </div>
            </div>
        </div>
        <div id="fixed_header" class="fixed-header" onclick="toggle_select();">
            <i class="fa fa-cog" aria-hidden="true"></i>
        </div>
    </div>

    <div id="calendar_div" class="row mouse-today"></div>

<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="function.js"></script>
    
<script type="text/javascript">

var d = new Date();

var date_event = [{
    date:{
        day:d.getDate(),
        month:d.getMonth()+1,
        year:d.getFullYear()
    }, 
    type:0,
    class:'badge-today color-today', 
    detail:'ปัจจุบัน'
},{
    date:{
        day:25,
        month:1,
        year:2018
    }, 
    type:1,
    class:'badge-event color-event', 
    detail:'วันหยุด'
},{
    date:{
        day:24,
        month:1,
        year:2018
    }, 
    type:3,
    class:'badge-invoice color-invoice', 
    detail:'รับ Invoice วันสุดท้าย'
},{
    date:{
        day:1,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
},{
    date:{
        day:2,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
},{
    date:{
        day:3,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
},{
    date:{
        day:4,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
}];



    //load initCalendar 
    window.onload =function() {
        initCalendar();
    };
    $( function() {
        $( "#draggable" ).draggable();
    } );
</script>
</body>
</html>