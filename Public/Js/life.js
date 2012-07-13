var idcard="";
var sid="";
$(function(){
    $("#service_item").hide();
    $("#service").hide();
    $("#expense").hide();
    $("input[name='idcard']").focusin(function(){
        $("input[name='idcard']").attr("value", "");
    })
    $("#idok").click(function(){
        var ss=$("input[name='idcard']").attr("value");
        idcard=ss;
        //alert(ss);
        $("#oldinfo").empty().load("/socialwork/index.php/old/getoldinfo/"+ss,function(data){
            //alert(data.substring(1,5));
            if(data.substring(1,5) == "老人信息"){
                $("input[name='idcard']").focus();
            }
            else{
              $("#service").show();  
            }
        });
        //$("#oldinfo").empty().get("/socialwork/index.php/old/getoldinfo/"+ss)
        //         var test=$("#oldinfo").html;
        //         alert(test);
        
    });
    $("#service_type").change(function(){
        var str=$(this).children('option:selected').val();
        //alert(str);
        $("#service_item").empty().load("/socialwork/index.php/old/getservicebytype/"+str,function(data){
            //alert(data);
        })
        $("#service_item").show();
        $("#expense").show();
    });
    $("#service_item").change(function(){
        sid=$(this).children('option:selected').val();
        
    })
    $("#expense").click(function(){
        sid=$("#service_item").children('option:selected').val();
        //alert(sid);
        $.get("/socialwork/index.php/old/expense/"+idcard+"/"+sid,function(data){
            $("#expense_info").append(data);
        })
    });
});

