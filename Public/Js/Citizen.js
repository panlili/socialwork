//残疾
function toggleDisable(element) {
    if(element.attr("value")=="是"){
        $(".canji").show();
    }else{
        $(".canji").hide();
    }
}

//享受低保,与house.js中的重复
function toggleDibao(element) {
    if(element.attr("value")=="是"){
        $(".dibao").show();
    }else{
        $(".dibao").hide();
    }
}

//享受廉租房,与house.js中的重复
function toggleLianzu(element) {
    if(element.attr("value")=="是"){
        $(".lianzu").show();
    }else{
        $(".lianzu").hide();
    }
}

//是否特殊人群
function toggleSpecial(element) {
    if(element.attr("value")=="是"){
        $(".special").show();
    }else{
        $(".special").hide();
    }
}

//是否计生指标
function toggleJhsy(element) {
    if(element.attr("value")=="是"){
        $(".jhsy").show();
    }else{
        $(".jhsy").hide();
    }
}

//新添居民信息时检查身份证
function checkIdCard(idcard) {
    var message="";
    //调用common.js中的函数
    message=vertifyIdCard(idcard);
    if("验证通过!"!=message){
        alert(message);
    }else{
        //年龄18-55且为女出现计划生育按钮
        if(getAgeByIdcard(idcard)<56 && getAgeByIdcard(idcard)>17 && getSexByIdcard(idcard)=="女"){
            $(".is_jhsy").show();
        }
    }
}

//根据身份证获取年龄
function getAgeByIdcard(idcard) {
    var birthday=getBirthdayByIdcard(idcard);
    var now=new Date();
    var old=new Date(birthday);
    var age=now.getFullYear()-old.getFullYear();
    return age;
}

//根据身份证获取生日
function getBirthdayByIdcard(idcard) {
    var birthday = "";
    if (18 == idcard.length) {
        birthday = idcard.substring(6,10);
        birthday += "-" +idcard.substring(10,12);
        birthday += "-" + idcard.substring(12,14);
    } else if (15 == idcard.length) {
        birthday = "19" + idcard.substring(6,8);
        birthday += "-" + idcard.substring(8,10);
        birthday += "-" + idcard.substring(10,12);
    }
    return birthday;
}

//根据身份证获取性别
function getSexByIdcard(idCard){
    if(idCard.length==15){
        if(idCard.substr(14,1)%2==0){
            return '女';
        }else{
            return '男';
        }
    }else if(idCard.length ==18){
        if(idCard.substr(16,1)%2==0){
            return '女';
        }else{
            return '男';
        }
    }else{
        return "未知";
    }
}

