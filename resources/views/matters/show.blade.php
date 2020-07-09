@extends('layouts.app')

@section('content')
<div class="container">

    <!-- タスク一覧 -->
    <!-- ガントチャート -->
    <!-- 案件ステータス -->
    <!-- ガントチャート（spreadsheet埋め込み） -->
    <!-- タスク表示 -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-header">
                <h5>タイトル：{{ $matter->name }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">制作物：
                    @foreach ($matter->products as $product)
                    {{ $product->title }}
                    @endforeach
                </p>
                <p>案件作成日時：{{ $matter->created_at }}</p>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($matter->products as $product)
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="{{$product->id}}" href="?state={{$product->title}}" role="tab"
                aria-controls="{{$product->id}}" aria-selected="true">{{ $product->title }}</a>
        </li>
        @endforeach
    </ul>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($productName->steps as $step)
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="{{$step->id}}" href="?state={{$productName->title}}&step={{$step->step_name}}"
                role="tab" aria-controls="{{$step->id}}" aria-selected="true">{{ $step->step_name }}</a>
            <!-- hrefで$productNameとしているのは、$productには「その他」が入ってしまっているため -->
        </li>
        @endforeach
    </ul>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('todos.store') }}" method="POST">
                {{csrf_field()}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="inputText" class="col-sm-2 col-form-label">ワイヤーフレーム</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputText" name="todo_name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- 制作ステップ -->
    <div class="row justify-content-center">
        <div class="card" style="width: 18rem;">
            <ul class="list-group list-group-flush">
                @foreach ($productName->steps as $step)
                {{ $productName->title }}
                <li class="list-group-item">{{ $step->step_name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
