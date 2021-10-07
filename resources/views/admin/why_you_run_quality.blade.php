@extends('layouts.admin')

@section('content')
    <div class="listContainer">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    why you run 品質保証キャンペーン　申し込みリスト一覧
                    <div class="goBack" ><a href="{{ route('admin.index') }}" >応募サイト一覧へ戻る</a></div>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>お名前</th>
                                <th>申込日時</th>
                                <th>電話番号</th>
                                <th>住所</th>
                                <th>メールアドレス</th>
                                <th>レシート画像</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataList as $appList)
                                <tr>
                                    <td>{{ $appList->f_name." ".$appList->l_name." 様" }}<br><span class="name_read" >({{ $appList->f_read." ".$appList->l_read }})</span></td>
                                    <td>{{ $appList->created_at->format('m/d H:i') }}</td>
                                    <td>{{ $appList->tel }}</td>
                                    <td>
                                        {{ $appList->zip21 ."-". $appList->zip22 }}<br>
                                        {{ $appList->pref21 ." ". $appList->addr21." ". $appList->strt21 }}
                                    </td>
                                    <td>{{ $appList->email }}</td>
                                    <td>
                                        <img src="{{asset('storage/why_you_run_quality_img_resize/'.$appList->img_pass)}}" class="thumbnail_imgs WYRRI" id="why_you_run_quality_img_resize_<?= $appList->id ?>" >
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

    <div class="why_you_run_quality" id="why_you_run_quality"  ><div class="why_you_run_quality_img" ><div id="modal_close" >閉じる</div><img src=""></div></div>

    <script>
        $('.WYRRI').on("click", function () {
            // １．ファイルのURL取得
            var str = $(this).attr('src');
            console.log(str)
            // ２．URLからファイル名を取得
            var cut_str ="why_you_run_quality_img_resize";
            var index = str.indexOf(cut_str);
            file_name = str.slice(index + 31);

            // 表示用のソースに対象のファイルURLをセット
            $('#why_you_run_quality').children('div').children('img').attr('src', '../storage/why_you_run_quality_img/' + file_name);
            $('#why_you_run_quality').slideToggle(100)
        });

        $('#why_you_run_quality').on('click',function(e) {
            if(!$(e.target).closest('.why_you_run_quality_img').length) {
                $("#why_you_run_quality").slideToggle(100);
            }
        });
        $('#modal_close').on('click',function() {
            $("#why_you_run_quality").slideToggle(100);
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
