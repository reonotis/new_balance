

var errorCount = 0;
var errorMSG = [];


function applyConfilm(){
    errorMSG = [];
    errorCount = 0;

    checkName();

    if(errorMSG){
        var alertMSG = errorMSG.join('\n');
        alert(alertMSG);
    }else{
        if (window.confirm('申し込みをしますか？')) {
            return true;
        }
    }
    return false;
}

function checkName(){
    var f_name = $('#f_name').val();
    if(!f_name){
        errorMSG.push('苗字が入力されていません');
    }
    if(!f_name.match(/^[ァ-ヶー]+$/)){    //"ー"の後ろの文字は全角スペースです。
        errorMSG.push('苗字がの入力形式は');
    }
}







