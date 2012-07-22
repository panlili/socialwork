//party information ;functions
function partyInformation(partyid) {
    $.get("partyInformation",{
        id:partyid
    },function(data){
        $("#content_middle").html("").html(data);
    });
}

//显示不同级别的党员: 
//班子成员+正式党员+预备党员+发展对象+积极分子
function showParter(partyid,category) {
    $.get("showParter",{
        id:partyid,
        category:category
    },function(data){
        $("#parterlist").html("").html(data);
    });
}