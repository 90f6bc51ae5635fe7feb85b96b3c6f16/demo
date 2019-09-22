function number_format (number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(/[^0-9+-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };

    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        //s[0] = s[0].replace(/B(?=(?:d{3})+(?!d))/g, sep);
        var rgx = /(d+)(d{3})/;
	
        while (rgx.test(s[0])) {
            s[0] = s[0].replace(rgx, '$1' + sep + '$2');
        }
    }
    
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

const numberWithCommas = (x) => {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
  }


// function roundNumber(number, decimals) {
//     decimals = parseInt(decimals,10);
//     var dec = Math.pow(10,decimals)
//     console.log(dec,parseFloat(number)*dec);
//     number=""+Math.round(parseFloat(number)*dec+.0000000000001); // fixed the .X99999999999
//     return parseFloat(number.slice(0,-1*decimals) + "." + number.slice(-1*decimals))     
// }