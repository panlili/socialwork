$(function(){
    $("#common_table td").css("height","15px");
    
    $(".onefloor").click(function(){
        if($(this).attr("checked")=="checked"){
            $(this).attr("value","是");
            $("#onefloor").show();
            $(this).parent().parent().nextUntil("#onefloor").hide();
        }else{
            $("#onefloor").hide();
            $(this).parent().parent().nextUntil("#onefloor").show();
        }        
    });
});