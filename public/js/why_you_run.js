

var errorCount = 0;
var errorMSG = [];


function applyConfirm(){
    errorMSG = [];
    errorCount = 0;

    checkFName();
    checkLName();
    checkFRead();
    checkLRead();
    // checkZip();
    // checkAddress();
    // checkTel();
    checkEMail();
    checkImage();

    if(errorMSG.length){
        var alertMSG = errorMSG.join('\n');
        alert(alertMSG);
    }else{
        if (window.confirm('申し込みをしますか？')) {
            return true;
        }
    }
    return false;
}

function qualityApplyConfirm(){
    errorMSG = [];
    errorCount = 0;

    checkFName();
    checkLName();
    checkFRead();
    checkLRead();
    checkZip();
    checkAddress();
    checkTel();
    checkEMail();
    checkImage();

    if(errorMSG.length){
        var alertMSG = errorMSG.join('\n');
        alert(alertMSG);
    }else{
        if (window.confirm('申し込みをしますか？')) {
            return true;
        }
    }
    return false;
}

function checkFName(){
    var f_name = $('#f_name').val();
    if(!f_name){
        errorMSG.push('苗字が入力されていません');
    }
}

function checkLName(){
    var l_name = $('#l_name').val();
    if(!l_name){
        errorMSG.push('名前が入力されていません');
    }
}

function checkFRead(){
    var f_read = $('#f_read').val();
    if(!f_read){
        errorMSG.push('ミョウジが入力されていません');
    }else if(!f_read.match(/^[ァ-ヶー]+$/)){
        errorMSG.push('ミョウジの入力形式は全角カタカナで入力して下さい');
    }
}

function checkLRead(){
    var l_read = $('#l_read').val();
    if(!l_read){
        errorMSG.push('ナマエが入力されていません');
    }else if(!l_read.match(/^[ァ-ヶー]+$/)){
        errorMSG.push('ナマエの入力形式は全角カタカナで入力して下さい');
    }
}

function checkZip(){
    var zip21 = $('#zip21').val();
    var zip22 = $('#zip22').val();

    if(!zip21 || !zip22){
        errorMSG.push('郵便番号が入力されていません');
    }else if(String(zip21).length != 3 || String(zip22).length != 4 ){
        errorMSG.push('郵便番号は3桁-4桁で入力してください');
    }
}

function checkAddress(){
    var pref21 = $('#pref21').val();
    var addr21 = $('#addr21').val();
    var strt21 = $('#strt21').val();

    if(!pref21 || !addr21 || !strt21){
        errorMSG.push('住所は[都道府県][市区町村][番地以降]の全てを入力してください');
    }
}

function checkTel(){
    var tel = $('#tel').val();

    if(!tel){
        errorMSG.push('電話番号が入力されていません');
    }
}

function checkEMail(){
    var email1 = $('#email1').val();
    var email2 = $('#email2').val();

    if(!email1 || !email2){
        errorMSG.push('メールアドレスが入力されていません');
    }else if(email1 != email2){
        errorMSG.push('メールアドレスが確認用と一致していません');
    }
}

function checkImage(){
    var image = $('#image').val();
    if(!image){
        errorMSG.push('レシート画像が選択されていません');
    }
}

$('#image').on('change', function() {
    if ($('#image').val() !== '') {
        //fileが選択されたときは、propを使って、file[0]にアクセスする
        var image_ = $('#image').prop('files')[0];

        //添付されたのが本当に画像かどうか、ファイル名と、ファイルタイプを正規表現で検証する
        if (!/\.(jpg|jpeg|png|JPG|JPEG|PNG)$/.test(image_.name) || !/(jpg|jpeg|png|gif)$/.test(image_.type)) {
            alert('JPG、PNGファイルの画像を添付してください。');
        //添付された画像ファイルが7M以下か検証する
        } else if (7340032 < image_.size) {
            alert('7MB以下の画像を添付してください。');
        } else {
            //window.FileReaderに対応しているブラウザどうか
            if (window.FileReader) {
                //FileReaderをインスタンス化
                var reader_ = new FileReader();
                //添付ファイルの読み込みが成功したときに実行されるイベント（成功時のみ）
                //一旦飛ばしてreader_ .readAsDataURLが先に動く
                reader_.onload = function() {
                    //Data URI Schemeをimgタグのsrcにいれてリアルタイムに添付画像を描画する
                    $('#image_preview').attr('src', reader_.result);
                }
                //Data URI Schemeを取得する
                reader_.readAsDataURL(image_);
            }
            return false;
        }
    }
    //ダメだったら値をクリアする
    $('#image').val('');
});
