var initCalendar = function(){
    var div         = document.getElementById('calendar_div'),
        tr_td       ='',
        main_table  ='',
        table,
        index,
        arr_month   = ['January','February','March','April','May','June','July','August','September','October','November','December'],
        yr          = new Date().getFullYear(),arr;
        for (var i = 0; i <12; i++) {
            arr=calendarDate(yr,i);
            index = 0;
            table='<div id="zabuto_calendar_68z" class="col-md-3">'+
                        '<div class="zabuto_calendar">'+
                            '<table class="table">'+
                                '<tbody>'+
                                    '<tr class="calendar-month-header">'+
                            '<td>'+
                                '<div class="calendar-month-navigation" id="zabuto_calendar_68z_nav-prev">'+
                                    '<span>'+
                                        '<span class="glyphicon glyphicon-chevron-left">'+
                                        '</span>'+
                                   ' </span>'+
                                '</div>'+
                            '</td>'+
                            '<td colspan="5">'+
                                '<span>'+arr_month[i]+' '+yr+'</span>'+
                            '</td>'+
                            '<td>'+
                                '<div class="calendar-month-navigation" id="zabuto_calendar_68z_nav-next">'+
                                    '<span>'+
                                        '<span class="glyphicon glyphicon-chevron-right">'+
                                        '</span>'+
                                    '</span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>'+
                        '<tr class="calendar-dow-header">'+
                            '<th>Mon</th>'+
                            '<th>Tue</th>'+
                            '<th>Wed</th>'+
                            '<th>Thu</th>'+
                            '<th>Fri</th>'+
                            '<th>Sat</th>'+
                            '<th>Sun</th>'+
                        '</tr>';

            for (var j = 0; j <5; j++) {
                tr_td+='<tr class="calendar-dow">';
                for (var k = 0; k <7; k++) {
                    var val = date_event.filter(item => (item['date']['day'] === arr[index] && item['date']['month'] === i+1 && item['date']['year'] === yr));
                    
                    if(val.length > 0 ){
                        
                        tr_td +='<td>'+
                                    '<div class="day">';

                        val.forEach(element => {
                            tr_td += '<span class="event '+element['class']+'" title="'+element['detail']+'"></span>';
                        });

                                    
                        tr_td += '<a onclick="set_date('+arr[index]+','+(i+1)+','+yr+')">'+arr[index++]+'</a></div>'+
                                '</td>';
                    }else{
                        tr_td+='<td>'+
                                    '<div class="day"><a onclick="set_date('+arr[index]+','+(i+1)+','+yr+')">'+arr[index++]+'</a></div>'+
                                '</td>';
                    }
                    
                }
                tr_td+='</tr>';
            }
        table += tr_td + '</table>' + 
        '</div>' +
        '</div>';
        main_table+=table;
        tr_td='';
        table='';
    }
    div.innerHTML=''+main_table+'<div class="clear"></div>';
} //close initCalendar()

function calendarDate(yr,i){
    var months      = (i==0) ? 1 : (i+1),
        days        = (i==0) ? 0 : i,
        get_date    = new Date(yr,months,0).getDate(),//total number of month days
        get_day     = new Date(yr,days,1).getDay(),//month start day ...sun,mon.....
        arrDay      = [], 
        extra_arr   = [30,31], 
        extra_day   = get_date-29, 
        num_day     = 0,temp_day;

    for (var i = 0; i<35; i++) {
        if((get_day>5 || get_day>6) && (get_date>29) && i<extra_day)  {
            temp_day=extra_arr[i];
        } else if(i<get_day ) {
            temp_day='';
        } else {
            if(num_day<get_date){
                num_day++
                temp_day=num_day;
            } else {
                temp_day='&nbsp;';
            }
        }
        arrDay.push(temp_day);
    }
    return arrDay;
} // close calendarDate

var select_type = 0;
function select_date_type(type){
    select_type = type;
    console.log("Date type: "+select_type);
    //document.body.style.cursor = "wait";
    document.getElementById('calendar_div').classList.remove('mouse-today');
    document.getElementById('calendar_div').classList.remove('mouse-event');
    document.getElementById('calendar_div').classList.remove('mouse-bill');
    document.getElementById('calendar_div').classList.remove('mouse-invoice');

    switch(select_type){
        case 0 :document.getElementById('calendar_div').classList.add('mouse-today');
        break;
        case 1 :document.getElementById('calendar_div').classList.add('mouse-event');
        break;
        case 2 :document.getElementById('calendar_div').classList.add('mouse-bill');
        break;
        case 3 :document.getElementById('calendar_div').classList.add('mouse-invoice');
        break;
    }
}


function set_date(day, month, year){
    var val = date_event.filter(item => (item['date']['day'] === day && item['date']['month'] === month && item['date']['year'] === year && item['type'] === select_type));
    if(val.length > 0){
        date_event = date_event.filter(item => (item['date']['day'] !== day || item['date']['month'] !== month || item['date']['year'] !== year || item['type'] !== select_type));
    }else{
        var _class = "";
        var _detail = "-";
        switch(select_type){
            case 0 :_class = "badge-today color-today";
            break;
            case 1 :_class = "badge-event color-event";
            break;
            case 2 :_class = "badge-bill color-bill";
            break;
            case 3 :_class = "badge-invoice color-invoice";
            break;
        }



        date_event.push({
            date:{
                day:day,
                month:month,
                year:year
            }, 
            type:select_type,
            class:_class, 
            detail:_detail
        });
    }
    
    initCalendar();
}

function toggle_select(){
   if( document.getElementById('fixed_body').classList.contains('active')){
        document.getElementById('fixed_body').classList.remove('active');
        document.getElementById('fixed_header').innerHTML= "<i class='fa fa-cog' aria-hidden='true'></i>";
   }else{
        document.getElementById('fixed_header').innerHTML= "<i class='fa fa-window-close' aria-hidden='true'></i>";
        document.getElementById('fixed_body').classList.add('active');
   }
}