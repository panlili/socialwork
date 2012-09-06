var s1=0;
var s2=0;
$(function(){
    $("div#searchResult").hide();
    addoption();
    function addoption(){
        $(".skeyname").change(function(){
            ////alert($(this).children('option:selected').val());
            //alert($(this).children('option:selected').val());
            //判断选择的值，根据值在后面添加相应的select元素
            var sKey=$(this).children('option:selected').val();
            //alert($(this).attr("name").charAt(8));
            s2=$(this).attr("name").charAt(8);
            //alert(s2);
            var strX;
            switch(sKey){
                
                case "register_date":
                    strX='从<input class="date-pick" id="sValue'+s2+'" name="sValue'+s2+'" type="text"></input>至<input class="date-pick" id="sValue'+s2+'-1" name="sValue'+s2+'-1" type="text"></input>';
                    
                    $(".skeyvalue"+s2).empty().append(strX);
                    $('.date-pick').datepicker({
                        changeMonth: true,
                        changeYear: true
                    });
                    break;
                
                default:
                    strX='<input id="sValue'+s2+'" name="sValue'+s2+'" type="text"></input>';
                    $(".skeyvalue"+s2).empty().append(strX);
            }
        })
    }
    
    $("input#addSearchKey").click(function(){
        
        var option1='<option value="AND">并且</option><option value="OR">或者</option>';
        var sKeyRelation='<select id="sKeyRelation'+s1+'" name="sKeyRelation'+s1+'">'+option1+'</select>';
        s1=s1+1;
        var option2='<option value="">请选择查询条件</option><option value="name">组织名称</option><option value="chairman">负责人</option><option value="telephone">负责人电话</option><option value="register_date">成立日期</option><option value="registerplace">登记备案机关</option><option value="scope">业务内容</option><option value="introduce">简介</option>';
        var sKeyName='<select id="sKeyName'+s1+'" class="skeyname" name="sKeyName'+s1+'">'+option2+'</select>';
        var sKeyValue='';
        var strHtml='<tr>'+'<td>'+sKeyRelation+'</td>'+'<td>'+sKeyName+'</td>'+'<td class="skeyvalue'+s1+'">'+sKeyValue+'</td>'+'</tr>';
        $("table#common_table").append(strHtml);
        addoption();
    
    })
    $("input#delSearchKey").click(function(){
        
        if(s1>0){
            s1=s1-1;
            $("#common_table tr:last").remove();
        }
    })
    
    $("input#dosearch").click(function(){
        var fo=$("form#searchKey").serializeArray();//alert(fo);
        $.post("/socialwork/index.php/search/ngosearch",fo,function(data){
            
            $("div#searchResult").html(data);
            $("div#searchResult").show();
        })
    
    })
})