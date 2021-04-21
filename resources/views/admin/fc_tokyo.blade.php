@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    FC_TOKYO 申し込みリスト一覧
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>お名前</th>
                                <th>希望サイズ</th>
                                <th>電話番号</th>
                                <th>住所</th>
                                <th>メールアドレス</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appLists as $appList)
                                <tr>
                                    <td>{{ $appList->f_name.$appList->l_name }} ({{ $appList->f_read.$appList->l_read }})</td>
                                    <td>{{ $appList->size }}</td>
                                    <td>{{ $appList->tel }}</td>
                                    <td>
                                        {{ $appList->zip21 ."-". $appList->zip22 }}
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