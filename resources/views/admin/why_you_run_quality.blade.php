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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



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
