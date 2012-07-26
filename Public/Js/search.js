var s1=0;
var s2=0;
$(function(){
    //$('#sValue0').datepicker();
    
    $("div#searchResult").hide();
    addoption();
   
    $("input#addSearchKey").click(function(){
        
        var option1='<option value="AND">并且</option><option value="OR">或者</option>';
        var sKeyRelation='<select id="sKeyRelation'+s1+'" name="sKeyRelation'+s1+'">'+option1+'</select>';
        s1=s1+1;
        var option2='<option value="name">姓名</option><option value="sex">性别</option><option value="id_card">身份证号</option><option value="birthday">出生日期</option><option value="age">年龄</option><option value="education">文化程度#</option><option value="political_status">政治面貌#</option><option value="marry_info">婚姻状况#</option><option value="employee">就业情况#</option><option value="relation_with_householder">与户主关系#</option><option value="is_fertility">是否领取计划生育指标@</option><option value="is_special">是否特殊人群@</option><option value="is_low_level">是否低保@</option><option value="is_disability">是否残疾@</option><option value="is_low_rent">是否廉租房@</option><option value="is_long_live">是否长寿金@</option>';
        var sKeyName='<select id="sKeyName'+s1+'" name="sKeyName'+s1+'" class="skeyname">'+option2+'</select>';
        var sKeyValue='<input id="sValue'+s1+'" name="sValue'+s1+'" type="text"></input>';
        var strHtml='<tr>'+'<td>'+sKeyRelation+'</td>'+'<td>'+sKeyName+'</td>'+'<td class="skeyvalue'+s1+'">'+sKeyValue+'</td>'+'</tr>';
        $("table#common_table").append(strHtml);
        addoption();
    //alert($("form#searchKey").serialize());
    //alert(s1);
    })
    
    //动态添加查询值输入表单的方法
    function addoption(){
        $(".skeyname").change(function(){
            //alert($(this).children('option:selected').val());
            //判断选择的值，根据值在后面添加相应的select元素
            var sKey=$(this).children('option:selected').val();
            s2=$(this).attr("name").charAt(8);
            var strX;
            switch(sKey){
                case "age":
                    strX='从<input id="sValue'+s2+'" name="sValue'+s2+'" type="text"></input>至<input id="sValue'+s2+'-1" name="sValue'+s2+'-1" type="text"></input>岁';
                    $(".skeyvalue"+s2).empty().append(strX);
                break;
                case "birthday":
                    strX='从<input class="date-pick" id="sValue'+s2+'" name="sValue'+s2+'" type="text"></input>至<input class="date-pick" id="sValue'+s2+'-1" name="sValue'+s2+'-1" type="text"></input>';
                    
                    $(".skeyvalue"+s2).empty().append(strX);
                    $('.date-pick').datepicker();
                break;
                case "education":
                    strX='<select name="sValue'+s2+'"><option value="文盲">文盲</option><option value="小学">小学</option><option value="初中">初中</option><option value="高中">高中</option><option value="技校">技校</option><option value="中专">中专</option><option value="大专">大专</option><option value="本科">本科</option><option value="硕士">硕士</option><option value="博士">博士</option><option value="博士后">博士后</option><option value="教授">教授</option><option value="院士">院士</option></select>';
                    //$(".skeyvalue").empty().append(strX);
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "political_status":
                    strX='<select name="sValue'+s2+'"><option value="群众">群众</option><option value="团员">团员</option><option value="民主人士">民主人士</option><option value="党员">党员</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "marry_info":
                    strX='<select name="sValue'+s2+'"><option value="未婚">未婚</option><option value="已婚">已婚</option><option value="离异">离异</option><option value="丧偶">丧偶</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "employee":
                    strX='<select name="sValue'+s2+'"><option value="就业">就业</option><option value="未就业">未就业</option><option value="灵活就业">灵活就业</option><option value="领取失业保证金">领取失业保证金</option><option value="在校生">在校生</option><option value="低保">低保</option><option value="退休">退休</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "relation_with_householder":
                    strX='<select name="sValue'+s2+'"><option value="暂住人口" >流动人口_暂住</option><option value="户主" >户主</option><option value="配偶" >配偶</option><option value="父亲或岳父" >父亲或岳父</option><option value="母亲或岳母" >母亲或岳母</option><option value="儿子" >儿子</option><option value="女儿" >女儿</option><option value="儿媳" >儿媳</option><option value="女婿" >女婿</option><option value="孙子" >孙子</option><option value="孙女" >孙女</option><option value="兄弟" >兄弟</option><option value="姐妹" >姐妹</option><option value="侄儿" >侄儿</option><option value="侄女" >侄女</option><option value="非亲属" >非亲属</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_fertility":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_special":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_low_level":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_disability":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_low_rent":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                case "is_long_live":
                    strX='<select name="sValue'+s2+'"><option value="是">是</option><option value="否">否</option></select>';
                    $(".skeyvalue"+s2).empty().append(strX);
                    break;
                default:
                    strX='<input id="sValue'+s2+'" name="sValue'+s2+'" type="text"></input>';
                    $(".skeyvalue"+s2).empty().append(strX);
            }
        })
    }
    $("input#delSearchKey").click(function(){
               
        if(s1>0){
            s1=s1-1;
            $("#common_table tr:last").remove();
        }
    })
  
    $("input#dosearch").click(function(){
        var fo=$("form#searchKey").serializeArray();//alert(fo);
        $.post("/socialwork/index.php/search/dosearch",fo,function(data){
            

       $("div#searchResult").html(data);
          $("div#searchResult").show();
        })
    
    })
    
    
    
})

