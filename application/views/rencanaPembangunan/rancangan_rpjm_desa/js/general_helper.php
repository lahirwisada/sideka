<script type="text/javascript">
    var ResetInputSelect = function (id) {

        $(id).find('option')
                .remove()
                .end();

    };

    var ValidateInput = function (elem, dvAlert, msg) {

        if ($("#" + elem + "").val() == '' || $("#" + elem + "").val() == null) {
            $("#" + elem + "").addClass('has-warning');

            $("#" + dvAlert + "").append(msg);
            return false;
        }
        return true;
    };

    var ResetValidationMessage = function () {
        $(".dvAlert").empty();
    };
    
    function toRp(a,b,c,d,e){e=function(f){return f.split('').reverse().join('')};b=e(parseInt(a,10).toString());for(c=0,d='';c<b.length;c++){d+=b[c];if((c+1)%3===0&&c!==(b.length-1)){d+='.';}}return'Rp.\t'+e(d)+',00'}

</script>