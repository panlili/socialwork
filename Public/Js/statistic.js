// 婚姻情况统计
function marryinfo() {
    $(".ploading").show();
    $.get("marryinfo",{},function(data){
        $("#graph").html("");
        $("#graph").html(data);
        $("#marry").visualize({
            type:'pie',
            parseDirection:'y',
            width:600,
            height:500
            
        });
        
        //原来pie里面的百分比数字根据width和height计算，按定制的长宽字体太大了，只能这样了。
        $(".visualize-label").css("font-size","16px");
        
        $("#marry").visualize({
            type:'bar',
            parseDirection:'x',
            width:600,
            height:500
        });
     
        $(".ploading").hide();
    });
}

// 性别统计
function sexinfo() {
    $(".ploading").show();
    $.get("sexinfo",{},function(data){
        $("#graph").html("");
        $("#graph").html(data);
        $("#sex").visualize({
            type:'pie',
            parseDirection:'y',
            width:350,
            height:250
        });
        
        $("#sex").visualize({
            type:'bar',
            parseDirection:'x',
            width:350,
            height:250
        });
        $(".ploading").hide();
    });
}

// 民族统计
function nationinfo() {
    $(".ploading").show();
    $.get("nationinfo",{},function(data){
        $("#graph").html("");
        $("#graph").html(data);
        $("#nation").visualize({
            type:'pie',
            parseDirection:'y',
            width:600,
            height:500
            
        });
        $(".visualize-label").css("font-size","16px");
        
        $("#nation").visualize({
            type:'bar',
            parseDirection:'x',
            width:600,
            height:500
            
        });
        $(".ploading").hide();
    });
}

// 文化程度统计
function educationinfo() {
    $(".ploading").show();
    $.get("educationinfo",{},function(data){
        $("#graph").html("");
        $("#graph").html(data);
        $("#education").visualize({
            type:'pie',
            parseDirection:'y',
            width:600,
            height:500
        });
        $(".visualize-label").css("font-size","16px");
        
        $("#education").visualize({
            type:'bar',
            parseDirection:'x',
            width:600,
            height:500
        });
        $(".ploading").hide();
    });
}

// 政治面貌统计
function politicalinfo() {
    $(".ploading").show();
    $.get("politicalinfo",{},function(data){
        $("#graph").html("");
        $("#graph").html(data);
        $("#political").visualize({
            type:'pie',
            parseDirection:'y',
            width:600,
            height:500
        });
        $(".visualize-label").css("font-size","16px");
        
        $("#political").visualize({
            type:'bar',
            parseDirection:'x',
            width:600,
            height:500
        });
        $(".ploading").hide();
    });
}

// 就业情况统计
function employeeinfo() {
    $(".ploading").show();
    $.get("employeeinfo",{},function(data){
        $("#graph").html("");
        $("#graph").html(data);
        $("#employee").visualize({
            type:'pie',
            parseDirection:'y',
            width:600,
            height:500
        });
        $(".visualize-label").css("font-size","16px");
        
        $("#employee").visualize({
            type:'bar',
            parseDirection:'x',
            width:600,
            height:500
        });
        $(".ploading").hide();
    });
}