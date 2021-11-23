@extends('layouts.admin')

@section('content')
    <div class="listContainer">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                Golf Holiday Campaign 申し込みリスト一覧
                <div class="goBack" ><a href="{{ route('admin.index') }}" >応募サイト一覧へ戻る</a></div>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>お名前</th>
                                <th>申込日時</th>
                                <th>メールアドレス</th>
                                <th>電話番号</th>
                                <th>住所</th>
                                <th>レシート画像</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataList as $appList)
                                <tr>
                                    <td>{{ $appList->f_name." ".$appList->l_name." 様" }}<br><span class="name_read" >({{ $appList->f_read." ".$appList->l_read }})</span></td>
                                    <td>{{ $appList->created_at->format('m/d H:i') }}</td>
                                    <td>{{ $appList->email }}</td>
                                    <td>{{ $appList->tel }}</td>
                                    <td>
                                        {{ $appList->zip21 . "-" . $appList->zip22 }}<br>
                                        {{ $appList->pref21 . " " . $appList->addr21 . " " . $appList->strt21 }}<br>
                                    </td>
                                    <td>
                                        <img src="{{asset('storage/golf_img_resize/'.$appList->img_pass)}}" class="thumbnail_imgs WYRRI" id="golf_resize_<?= $appList->id ?>" >
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="golf" id="golf"  ><div class="golf_img" ><div id="modal_close" >閉じる</div><img src=""></div></div>

    <script>
        $('.WYRRI').on("click", function () {
            // １．ファイルのURL取得
            var str = $(this).attr('src');
            // ２．URLからファイル名を取得
            var cut_str ="golf_img_resize";
            var index = str.indexOf(cut_str);
            file_name = str.slice(index + 15);

            // 表示用のソースに対象のファイルURLをセット
            $('#golf').children('div').children('img').attr('src', '../storage/golf_img/' + file_name);
            $('#golf').slideToggle(100)
        });

        $('#golf').on('click',function(e) {
            if(!$(e.target).closest('.golf_img').length) {
                $("#golf").slideToggle(100);
            }
        });
        $('#modal_close').on('click',function() {
            $("#golf").slideToggle(100);
        });

    </script>
@endsection

<style>
    table{
        width :100%;
    }
    table th{
        background: #def ;
        padding :10px;
    }
    table td{
        border-bottom:solid 1px #19f ;
        padding :5px 10px;
        font-size:0.9em;
    }
</style>
