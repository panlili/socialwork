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
                case "address":
                    strX='<input type="text" id="sValue'+s2+'" name="sValue'+s2+'"></input>';
                    //$(".skeyvalue").empty().append(strX);
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_fit":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_free":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_lowrent":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_floor":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_afford":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_taiwan":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_army":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_fuel":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                
                default:
                    strX='<input id="sValue'+s2+'" name="sValue'+s2+'" type="text"></input>';
                    $(".skeyvalue"+s2).empty().append(strX);
            }
        })
    }
    
    $("input#addSearchKey").click(function(){
        //var strHtml='<tr><td><select id="sKeyRelation1" ><option>并且</option></select></td><td><select id="sKeyName1" ><option>姓名</option><option>性别</option><option>身份证号</option><option>出生日期</option><option>年龄</option></select></td><td><input id="sValue1" name="sValue1" type="text"></input></td></tr>';
        //alert(s1);
        var option1='<option value="AND">并且</option><option value="OR">或者</option>';
        var sKeyRelation='<select id="sKeyRelation'+s1+'" name="sKeyRelation'+s1+'">'+option1+'</select>';
        s1=s1+1;
        var option2='<option value="address">地址</option><option value="is_fit">人户一致@</option><option value="is_free">是否空闲@</option><option value="is_lowrent">是否廉租房@</option><option value="is_floor">是否是平房@</option><option value="is_afford">是否经济适用房@</option><option value="is_taiwan">是否台属@</option><option value="is_army">是否军属@</option><option value="is_fuel">是否燃油补贴@</option><option value="contactor">联系人</option><option value="telephone">联系方式</option></select>';
        var sKeyName='<select id="sKeyName'+s1+'" class="skeyname" name="sKeyName'+s1+'">'+option2+'</select>';
        var sKeyValue='<input id="sValue'+s1+'" name="sValue'+s1+'" type="text"></input>';
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
        $.post("/socialwork/index.php/search/housesearch",fo,function(data){
            
            $("div#searchResult").html(data);
            $("div#searchResult").show();
        })
    
    })



})