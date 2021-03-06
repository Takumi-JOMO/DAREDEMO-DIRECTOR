@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                案件一覧
                </div>
                @foreach (Auth::user()->matters as $matter)
                {{-- 上記はリレーションを使う --}}
                <div class="card-body">
                    <p class="card-name">会社名：{{ $matter->name }}</p>
                    <p class="card-outputs">制作物：
                    @foreach ($matter->products as $product)
                    {{$product->title}}
                    @endforeach
                    </p>
                    <p class="card-commnets">備考：{{ $matter->comments }}</p>
                    <a href="{{ route('matters.show', $matter->id) }}" class="btn btn-primary">案件詳細</a>
                </div>
                @endforeach
                {{-- routeでコントローラーにルーティングして、matterのidをMatterContorollerに送る→MatterContorollerのshowメソッドで受け取る処理を行う --}}
            </div>
        </div>
        <div class="col-md-2">
            <a href="{{route ('matters.create')}}" class="btn btn-primary">新規案件作成</a>
        </div>
    </div>
</div>
@endsection
{{--15行目 @foreach ($matter->products as $product)ではMatter.phpでつないだリレーションを呼び出した--}}