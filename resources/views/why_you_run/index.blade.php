@extends('layouts.why_you_run')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-header">
                sss<br class="brSp">aaa<br class="brSp2">お申込フォーム
            </div>
        </div>
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {!! session('status') !!}
                </div>
            @endif
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
                    <div class="itemTitle">住所</div>
                    <div class="itemContent">
                        <div class="inputZip">
                            <input type="text" id="zip21" name="zip21" value="{{ old('zip21') }}" class="fc_form_zip1" placeholder="101" >-
                            <input type="text" id="zip22" name="zip22" value="{{ old('zip22') }}" class="fc_form_zip2" onKeyUp="AjaxZip3.zip2addr('zip21','zip22','pref21','addr21','strt21');" placeholder="0051" >
                        </div>
                        <div class="inputZip">
                            <input type="text" id="pref21" name="pref21" value="{{ old('pref21') }}" class="Prefectures" placeholder="東京都" >
                            <input type="text" id="addr21" name="addr21" value="{{ old('addr21') }}" class="Municipality" placeholder="千代田区" >
                        </div>
                        <input type="text" id="strt21" name="strt21" value="{{ old('strt21') }}" class="form-control" placeholder="神田神保町1−105" >
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">電話番号</div>
                    <div class="itemContent">
                        <input type="text" id="tel" name="tel" value="{{ old('tel') }}" class="form-control" placeholder="03-5577-2300" >
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
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return applyConfilm()" >申し込む</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
