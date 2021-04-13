@extends('layouts.app_fc_tokyo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-header">
                FC東京がアルク東京 <br class="brSp">FC東京3rdユニフォーム<br class="brSp2">お申込フォーム
            </div>
        </div>
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{route('FcTokyo.aplication')}}" method="post">
                @csrf
                <div class="itemRow">
                    <div class="itemTitle">氏名</div>
                    <div class="itemContent">
                        <div class="inputName">
                            <input type="text" name="f_name" value="" class="fc_form1" >
                            <input type="text" name="l_name" value="" class="fc_form1" >
                        </div>
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">ヨミ</div>
                    <div class="itemContent">
                        <div class="inputName">
                            <input type="text" name="f_read" value="{{ old('f_read') }}" class="fc_form1" >
                            <input type="text" name="l_read" value="" class="fc_form1" >
                        </div>
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">希望サイズ</div>
                    <div class="itemContent">
                        <div class="inputRadio4">
                            <label><input type="radio" name="size" value="s" >S</label>
                            <label><input type="radio" name="size" value="m" >M</label>
                            <label><input type="radio" name="size" value="l" >L</label>
                            <label><input type="radio" name="size" value="xl" >XL</label>
                        </div>
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">住所</div>
                    <div class="itemContent">
                        <div class="inputZip">
                            <input type="text" name="zip21" value="" class="fc_form_zip1" >-
                            <input type="text" name="zip22" value="" class="fc_form_zip2" onKeyUp="AjaxZip3.zip2addr('zip21','zip22','pref21','addr21','strt21');" >
                        </div>
                        <div class="inputZip">
                            <input type="text" name="pref21" value="" class="Prefectures" >
                            <input type="text" name="addr21" value="" class="Municipality" >
                        </div>
                        <input type="text" name="strt21" value="" class="form-control" >
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">電話番号</div>
                    <div class="itemContent">
                        <input type="text" name="tel" value="" class="form-control" >
                    </div>
                </div>
                <div class="itemRow">
                    <div class="itemTitle">メールアドレス</div>
                    <div class="itemContent">
                        <div class="inputMails">
                            <input type="text" name="email1" value="" class="form-control" >
                        </div>
                            <input type="text" name="email2" value="" class="form-control" >
                    </div>
                </div>
                <div class="itemBtn">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >申請する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

