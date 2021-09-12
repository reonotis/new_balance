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
                    <div class="apply_list">
                        <a href="{{ route('admin.fc_tokyo') }}">FC_TOKYO</a>
                    </div>
                    <div class="apply_list">
                        <a href="{{ route('admin.why_you_run') }}">Why you run</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

