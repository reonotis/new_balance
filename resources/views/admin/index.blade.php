@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    応募サイト一覧
                </div>
                <div class="card-body">
                    <div class="">
                        <a href="{{ route('admin.fc_tokyo') }}">FC_TOKYO</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

