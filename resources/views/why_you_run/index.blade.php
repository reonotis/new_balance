@extends('layouts.why_you_run')

@section('content')

<div class="header-img" >
    <img src="{{asset('img/why_you_run/photo1.jpg')}}" >
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 explanation">

            <div class="card-header">
                10K CHARGEで使える<br class="brSp">クーポンコードプレゼント<br>抽選で115名様に合計50万円<br class="brSp">のギフトカードが当たる！」
                <!-- <div class="ribbon18-content" >
                    <div class="ribbon18" ></div>
                </div> -->
            </div>
            <div class="headerSupport">

                <div class="end_application_period" >
                    応募期間が終了しました。
                </div>

                <!-- <div class="itemRow">
                    <div class="itemTitle">期　　間</div>
                    <div class="itemContent">10月8日（金）〜10月31日（日）<br>
                        ※応募締め切りは11月14日（日）23時59分
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">応募資格</div>
                    <div class="itemContent">期間中にニューバランスランニング商品を<span class="heads-up" >合計5,000円（税込）以上</span>ご購入の方
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">対象商品</div>
                    <div class="itemContent">ニューバランスランニング全商品（セール品含む）
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">キャンペーン賞品</div>
                    <div class="itemContent">
                        10K CHARGE参加＋特別コード入力で<br>
                        合計50万円のギフトカードが115名様に当たる！<br>
                        1等賞：10万円　×　1名<br>
                        2等賞：5万円　×　4名<br>
                        3等賞：1万円　×　10名<br>
                        4等賞：1000円　×　100名<br>
                        ★10K CHARGEへのご参加はこちらから↓<br>
                        <a href="https://10kcharge.com/" >https://10kcharge.com/</a>
                    </div>
                </div> -->
            </div>
        </div>
        <!-- <div class="col-md-8">
            <div class="card-header">
                申込フォーム
            </div>
            <div class="headerSupport">
                10K charge への申し込みクーポンコードの獲得をご希望の方は、<br class="brSp">下記より必要事項をご記入の上、5000円(税込み)以上購入のレシート画像を添付してお申し込みください。
            </div>
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {!! session('status') !!}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('why_you_run.register') }} " method="post"  enctype="multipart/form-data" >
                    @csrf
                    <div class="itemRow">
                        <div class="itemTitle">氏名</div>
                        <div class="itemContent">
                            <div class="inputName">
                                <input type="text" id="f_name" name="f_name" value="{{ old('f_name') }}" class="fc_form1" placeholder="田中" >
                                <input type="text" id="l_name" name="l_name" value="{{ old('l_name') }}" class="fc_form1" placeholder="太郎" >
                            </div>
                        </div>
                    </div>
                    <div class="itemRow">
                        <div class="itemTitle">ヨミ</div>
                        <div class="itemContent">
                            <div class="inputName">
                                <input type="text" id="f_read" name="f_read" value="{{ old('f_read') }}" class="fc_form1" placeholder="タナカ" >
                                <input type="text" id="l_read" name="l_read" value="{{ old('l_read') }}" class="fc_form1" placeholder="タロウ" >
                            </div>
                        </div>
                    </div>
                    <div class="itemRow">
                        <div class="itemTitle">メールアドレス</div>
                        <div class="itemContent">
                            <div class="inputMails">
                                <input type="text" id="email1" name="email1" value="{{ old('email1') }}" class="form-control" placeholder="sample@newbalance.co.jp" >
                            </div>
                                <input type="text" id="email2" name="email2" value="{{ old('email2') }}" class="form-control" placeholder="確認用の為、同じアドレスを入力してください" >
                        </div>
                    </div>
                    <div class="itemRow">
                        <div class="itemTitle">レシート画像</div>
                        <div class="itemContent">
                            <input type="file" id="image" name="image" accept="image/jpeg, image/png">
                            <img src="" id="image_preview">
                        </div>
                    </div>
                    <div class="itemBtn">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return applyConfirm()" >申し込む</button>
                    </div>
                </form>
            </div>
        </div> -->
    </div>
</div>
@endsection
