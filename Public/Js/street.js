function delete_street(id) {
    if(window.confirm("街道是院落,店铺,单位等数据组织的必须预先数据,确定删除?")){
        $.post("delete",{
            id:id
        }, function(data){
            if(1==$.parseJSON(data).status) $('#'+id).fadeOut("slow");            
        });        
    }
}

function showYard(streetid){
    $(".loading").show();
    $.get("showYard",{
        id:streetid
    },function(data){
        $("#content_middle").html(data);
        $(".loading").hide();
        setNumber("#common_table2");
    });
}

function showOrganization(streetid,type) {
    $(".loading").show();
    $.get("showOrganization",{
        id:streetid,
        type:type
    },function(data){
        $("#content_middle").html(data);
        $(".loading").hide();
        setNumber("#common_table2");
    });
}

function showStore(streetid) {
    $(".loading").show();
    $.get("showStore",{
        id:streetid
    },function(data){
        $("#content_middle").html(data);
        $(".loading").hide();
        setNumber("#common_table2");
    });
}


$(function(){
    $("#common_table td").css("height","15px");

    $("div#addstreet").hide();

    $(".newdata a").click(function(event){
        event.preventDefault();
        $("div#addstreet").toggle();
    });   
    
    //实现ajax修改
    var nameTd=$(".street_name");
    nameTd.click(function() {	
        var tdObj = $(this);
        if (tdObj.children("input").length > 0) {
            return false;
        }
        var text = tdObj.html(); 
        tdObj.html("");
		
        var inputObj = $("<input type='text'>").css("border-width","0")
        .css("font-size","13px").width(tdObj.width()).css("background-color","yellow").val(text).appendTo(tdObj);
        inputObj.trigger("focus").trigger("select");
        inputObj.click(function() {
            return false;
        });
		
        inputObj.keyup(function(event){
            var keycode = event.which;
            if (keycode == 13) {
                var inputtext = $(this).val();
                tdObj.html(inputtext);
                $.post("update", {
                    id:tdObj.parent("tr").attr("id"),
                    name:inputtext
                },
                function(data){
                    var dataObj=$.parseJSON(data);
                    var action_message=$("<div>").css({            
                        position: "absolute",
                        "top": "30%",
                        "left": "30%",    
                        "width": "250px",
                        "height": "40px",
                        "line-height": "40px", 
                        "z-index": "12",
                        "text-align": "center",
                        "background-color": "orange"
                    });
                    
                    if(1==dataObj.status) {
                        action_message.text(dataObj.info).appendTo("#content").fadeOut(2000);
                    }       
                    if(0==dataObj.status) {
                        action_message.text(dataObj.info).appendTo("#content").fadeOut(2000);
                        tdObj.html(text);
                    }   
                }
                );
            }
			
            if (keycode == 27) {
                tdObj.html(text);
            }
        });
    });
});