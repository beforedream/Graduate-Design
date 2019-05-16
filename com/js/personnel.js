$(document).ready(function () {
    /**
     * Line Chart
     */

    
    // $('#table tbody tr:odd').css('background-color', '#bbf');
    // $('#table tbody tr:even').css('background-color','#ffc');
    // //操作class
    // $("#table tbody tr:odd").addClass("odd");
    // $("#table tbody tr:even").addClass("even");

    $("#table tr:gt(0)").hover( function () {
        $(this).addClass("hover");
    },
    function () { 
        $(this).removeClass("hover");
    });
    $('#table').on('dblclick','td',function(){
        if($(this)[0].children.length > 0){
            return;
        }
        var oldVal = $(this).text();
        var input = "<input type='text' id='tmpId' value='" + oldVal + "' >";
        $(this).text('');
        $(this).append(input);
        $('#tmpId').focus();
        $('#tmpId').blur(function(){
            if($(this).val() != ''){
               oldVal = $(this).val();
            }
           $(this).closest('td').text(oldVal);
        });
    });
});
