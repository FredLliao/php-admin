/**
 * 显示loading信息
 * @param msg 必须参数，需要显示的文字
 * arguments[1] shade 可选参数，是否显示遮罩ture/false,默认显示遮罩层
 */
function showLoading(msg) {
    layer.msg(msg, {
        shade: (arguments[1] != undefined && arguments[1] == false) ? 0 : [0.8, '#393D49'],
        icon: 16,
        time: 0 //x秒后关闭（0表示永不关闭，由ajax回调触发关闭动作）
    });
}

function hideLoading() {
    layer.closeAll();
}

//正常提示
function showError(msg){
    if(arguments[1] != undefined) {
        switch (arguments[1]) {
            case 'center': //正中间震动提示
                layer.msg(msg, function(){
                    //关闭后的操作
                });
                break;
            case 'top': //正上方震动提示
                layer.msg(msg, {
                    offset: 0,
                    shift: 6
                });
                break;
            default :
                //正常提示
                layer.msg(msg);
        }
    } else {
        //正常提示
        layer.msg(msg);
    }
}

//js checkbox多选组件,
function checkCheckBoxList(checkbox_group_class_or_id) {
    var listLen=0;
    var list=$(checkbox_group_class_or_id).find("input[type='checkbox']");
    $(list).each(function(i,e){
        if($(e).parent().hasClass("checked")){
            listLen++;
        }
    });
    return listLen;
}

$(".select-all").click(function() {
    var $all=$(this).find("input[type='checkbox']");
    var $op=$(this).siblings(".select-list").find("input[type='checkbox']");
    if($all.parent().hasClass("checked")){
        $op.each(function(i,e){
            if(!$(e).parent().hasClass("checked")){
                $(e).click();
            }
        });
    }
    else {
        $op.each(function(i,e){
            if($(e).parent().hasClass("checked")){
                $(e).click();
            }
        });
    }
});

