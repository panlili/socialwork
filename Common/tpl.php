<?php

//放一些模版中不带数据库功能的函数,可以传递元素的name属性和默认的已经被选择了的元素。
function marry_info_select($name = "marry_info", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html .= '<option value="未婚">未婚</option>
              <option value="已婚">已婚</option>
              <option value="离异">离异</option>
              <option value="丧偶">丧偶</option></select>';
    return $html;
}

function education_select($name = "education", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html .= '<option value="文盲">文盲</option>
                    <option value="小学">小学</option>
                    <option value="初中">初中</option>
                    <option value="高中">高中</option>
                    <option value="技校">技校</option>
                    <option value="中专">中专</option>
                    <option value="大专">大专</option>
                    <option value="本科">本科</option>
                    <option value="硕士">硕士</option>
                    <option value="博士">博士</option>
                    <option value="博士后">博士后</option>
                    <option value="教授">教授</option>
                    <option value="院士">院士</option></select>';
    return $html;
}

function relation_with_householder_select($name = "relation_with_householder", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html .= '<option value="户主" >户主</option>
                        <option value="配偶" >配偶</option>
                        <option value="父亲或岳父" >父亲或岳父</option>
                        <option value="母亲或岳母" >母亲或岳母</option>
                        <option value="儿子" >儿子</option>
                        <option value="女儿" >女儿</option>
                        <option value="儿媳" >儿媳</option>
                        <option value="女婿" >女婿</option>
                        <option value="孙子" >孙子</option>
                        <option value="孙女" >孙女</option>
                        <option value="兄弟" >兄弟</option>
                        <option value="姐妹" >姐妹</option>
                        <option value="侄儿" >侄儿</option>
                        <option value="侄女" >侄女</option>
                        <option value="非亲属" >非亲属</option>
                        <option value="暂住人口" >流动人口_暂住</option></select>';
    return $html;
}

function nation_select($name = "nation", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html .= '<option value="汉族">汉族</option>
                    <option value="蒙古族">蒙古族</option>
                    <option value="彝族">彝族</option>
                    <option value="侗族">侗族</option>
                    <option value="哈萨克族">哈萨克族</option>
                    <option value="畲族">畲族</option>
                    <option value="纳西族">纳西族</option>
                    <option value="仫佬族">仫佬族</option>
                    <option value="仡佬族">仡佬族</option>
                    <option value="怒族">怒族</option>
                    <option value="保安族">保安族</option>
                    <option value="鄂伦春族">鄂伦春族</option>
                    <option value="回族">回族</option>
                    <option value="壮族">壮族</option>
                    <option value="瑶族">瑶族</option>
                    <option value="傣族">傣族</option>
                    <option value="高山族">高山族</option>
                    <option value="景颇族">景颇族</option>
                    <option value="羌族">羌族</option>
                    <option value="锡伯族">锡伯族</option>
                    <option value="乌孜别克族">乌孜别克族</option>
                    <option value="裕固族">裕固族</option>
                    <option value="赫哲族">赫哲族</option>
                    <option value="藏族">藏族</option>
                    <option value="布依族">布依族</option>
                    <option value="白族">白族</option>
                    <option value="黎族">黎族</option>
                    <option value="拉祜族">拉祜族</option>
                    <option value="柯尔克孜族">柯尔克孜族</option>
                    <option value="布朗族">布朗族</option>
                    <option value="阿昌族">阿昌族</option>
                    <option value="俄罗斯族">俄罗斯族</option>
                    <option value="京族">京族</option>
                    <option value="门巴族">门巴族</option>
                    <option value="维吾尔族">维吾尔族</option>
                    <option value="朝鲜族">朝鲜族</option>
                    <option value="土家族">土家族</option>
                    <option value="傈僳族">傈僳族</option>
                    <option value="水族">水族</option>
                    <option value="土族">土族</option>
                    <option value="撒拉族">撒拉族</option>
                    <option value="普米族">普米族</option>
                    <option value="鄂温克族">鄂温克族</option>
                    <option value="塔塔尔族">塔塔尔族</option>
                    <option value="珞巴族">珞巴族</option>
                    <option value="苗族">苗族</option>
                    <option value="满族">满族</option>
                    <option value="哈尼族">哈尼族</option>
                    <option value="佤族">佤族</option>
                    <option value="东乡族">东乡族</option>
                    <option value="达斡尔族">达斡尔族</option>
                    <option value="毛南族">毛南族</option>
                    <option value="塔吉克族">塔吉克族</option>
                    <option value="德昂族">德昂族</option>
                    <option value="独龙族">独龙族</option>
                    <option value="基诺族">基诺族</option>
                </select>';
    return $html;
}

function political_select($name = "political_status", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html.='<option value="群众">群众</option>
                        <option value="团员">团员</option>
                        <option value="民主人士">民主人士</option>
                        <option value="党员">党员</option>
                        </select>';
    return $html;
}

function employee_select($name = "employee", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html.='<option value="就业">就业</option>
                        <option value="未就业">未就业</option>
                        <option value="灵活就业">灵活就业</option>
                        <option value="领取失业保证金">领取失业保证金</option>
                        <option value="在校生">在校生</option>
                        <option value="低保">低保</option>
                        <option value="退休">退休</option></select>';
    return $html;
}

function disable_select($name = "canji_type", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html.='<option value="视力">视力</option>
                        <option value="听力语言">听力语言</option>
                        <option value="精神">精神</option>
                        <option value="智力">智力</option>
                        <option value="肢体">肢体</option>
                    </select>';
    return $html;
}

function special_select($name = "sp_status", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html.='<option value="不是">不是</option><option value="处理中">处理中</option>
                 <option value="息诉息访">息诉息访</option>
                 </select>';
    return $html;
}

function status_select($name = "status", $selected = "") {
    if ("" !== $selected) {
        $html = '<select name=' . $name . '><option value=' . $selected .
                '>' . $selected . '</option><option value=' . $selected . '>--请重选--</option>';
    } else {
        $html = '<select name=' . $name . '>';
    }

    $html .='<option value="正常">正常</option><option value="删除/迁出">删除/迁出</option><option value="死亡">死亡</option>';
    return $html;
}

//yardwork中根据work_type的数字返回字符
function get_type_name($work_type) {
    switch ($work_type) {
        case 1:
            return "日常事务";
            break;
        case 20:
            return "其他";
            break;
    }
}

//yardwork中获取status=1,2,3的option，因为要用ajax onchange方法，故不能直接返回select
function get_status_option($status) {

    $html1 = "<option value=1>发现问题</option>
        <option value=2>处理中</option>
        <option value=3>处理完毕</option>";
    $html2 = "<option value=2>处理中</option>
        <option value=1>发现问题</option>
        <option value=3>处理完毕</option>";
    $html3 = "<option value=3>处理完毕</option>
        <option value=1>发现问题</option>
        <option value=2>处理中</option>";

    switch ($status) {
        case 1:
            return $html1;
            break;
        case 2:
            return $html2;
            break;
        case 3:
            return $html3;
            break;
        default:
            return $html1;
            break;
    }
}

?>
