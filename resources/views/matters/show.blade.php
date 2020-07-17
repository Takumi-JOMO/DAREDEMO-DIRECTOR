@extends('layouts.app')

@section('content')
<div class="container">

    <!-- タスク一覧 -->
    <!-- ガントチャート -->
    <!-- 案件ステータス -->
    <!-- ガントチャート（spreadsheet埋め込み） -->
    <!-- タスク表示 -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-header">
                <h5>案件名：{{ $matter->name }} 様</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-text">制作物：
                            @foreach ($matter->products as $product)
                            {{ $product->title }}
                            @endforeach
                        </p>
                        <p>案件作成日時：{{ $matter->created_at }}</p>
                        @if(Auth::user()->authority->name === 'ディレクター')
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#ganttChartModal">
                            <!-- # === id なので "#ganttChart" === id="ganttChartModal"-->
                            ガントチャートを提出
                        </button>
                        @endif
                        <!-- Modal -->
                        <div class="modal fade" id="ganttChartModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('products.update',$matter->id) }}" method="POST">
                                    {{csrf_field()}}
                                    {{method_field('PATCH')}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @foreach ($matter->products as $product)
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="inputText" class="col-md-3 col-form-label">
                                                    {{ $product->title }}
                                                </label>
                                                <div class="col-md-10 mt-2">
                                                    <input type="text" class="form-control"
                                                        value="{{$product->director_gantt_chart_url}}" id="inputText"
                                                        name="director_gantt_chart_url[]" placeholder="ディレクター用ガントチャート">
                                                    <!-- []（配列）は[]に変数をいくつも入れられる -->
                                                </div>
                                                <div class="col-sm-10  mt-2">
                                                    <input type="text" class="form-control"
                                                        value="{{$product->customer_gantt_chart_url}}" id="inputText"
                                                        name="customer_gantt_chart_url[]" placeholder="お客様用ガントチャート">
                                                </div>
                                                <div class="col-sm-10  mt-2">
                                                    <input type="text" class="form-control"
                                                        value="{{$product->designer_engineer_gantt_chart_url}}"
                                                        id="inputText" name="designer_engineer_gantt_chart_url[]"
                                                        placeholder="デザイナー／エンジニア用ガントチャート">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">保存</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if(Auth::user()->authority->name === 'ディレクター')
                        <form action="{{ route('users.store') }}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" name="email">
                                <input type="hidden" class="form-control" id="inputText" name="matter_id"
                                    value="{{ $matter->id }}">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with
                                    anyone else.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        @endif
                    </div>
                </div>
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
    <div class="col-md-12">
        @if(Auth::user()->authority->name === "ディレクター")
        <iframe src="{{$productName->director_gantt_chart_url}}" width="1200" height="600">
        </iframe>
        @elseif(Auth::user()->authority->name === "プログラマー／デザイナー")
        <iframe src="{{$productName->designer_engineer_gantt_chart_url}}" width="1200" height="600">
        </iframe>
        @elseif(Auth::user()->authority->name === "お客様")
        <iframe src="{{$productName->customer_gantt_chart_url}}" width="1200" height="600">
        </iframe>
        @endif
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($productName->steps as $step)
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="{{$step->id}}" href="?state={{$productName->title}}&step={{$step->step_name}}"
                role="tab" aria-controls="{{$step->id}}" aria-selected="true">{{ $step->step_name }}</a>
            <!-- hrefで$productNameとしているのは、$productには「その他」が入ってしまっているため -->
        </li>
        @endforeach
    </ul>

    <!-- 制作ステップ -->
    <div class="row">
        <div class="col-md-9">
            <!-- drive url 確認用ボタン元の場所 -->

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ページ／セクション</th>
                        <th scope="col">コメント</th>
                        <th scope="col">ステータス</th>
                        <th scope="col">ステータス変更</th>
                        <th scope="col">削除</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $num=1 ?>
                    @foreach($todos as $todo)
                    <tr>
                        <th scope="row">{{ $num }}</th>
                        <td>{{ $todo->todo_name }}</td>
                        <td>
                            コメントがあります。
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#commentModal">
                                確認
                            </button>
                            <!-- Comment Modal -->
                            <div class="modal fade" id="commentModal" tabindex="-1" role="dialog"
                                aria-labelledby="commentModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">コメント</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="form-group">
                                                    <div class="exampleFormControlTextarea1">
                                                        <p>投稿者：</p>
                                                        <p class="card-text">内容：</p>
                                                        <p>投稿日時：</p>
                                                        <button type="button" class="btn btn-primary">確認しました</button>
                                                    </div>
                                                    <textarea class="form-control" id="exampleFormControlTextarea1"
                                                        rows="3"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary">保存</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">閉じる</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($todo->status)
                            <p>{{ $todo->status }}</p>
                            @else
                            <p>進行中</p>
                            @endif
                            <form action="{{ route('todos.update',$todo->id) }}" method="POST">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <input type="hidden" class="form-control" id="inputText" name="status" value="完了">
                                @isset($filter['state'])
                                <input type="hidden" class="form-control" id="inputText" name="state"
                                    value="{{ $filter['state'] }}">
                                @endisset
                                @isset($filter['step'])
                                <input type="hidden" class="form-control" id="inputText" name="step"
                                    value="{{ $filter['step'] }}">
                                @endisset
                                <!-- @if(Auth::user()->authority->name === 'ディレクター')
                                <button type="submit" class="btn btn-primary">完了する</button>
                                @endif -->
                            </form>
                        </td>
                        <td>
                            <!-- @if($todo->status)
                            <p>{{ $todo->status }}</p>
                            @else
                            <p>進行中</p>
                            @endif -->
                            <form action="{{ route('todos.update',$todo->id) }}" method="POST">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <input type="hidden" class="form-control" id="inputText" name="status" value="完了">
                                @isset($filter['state'])
                                <input type="hidden" class="form-control" id="inputText" name="state"
                                    value="{{ $filter['state'] }}">
                                @endisset
                                @isset($filter['step'])
                                <input type="hidden" class="form-control" id="inputText" name="step"
                                    value="{{ $filter['step'] }}">
                                @endisset
                                @if(Auth::user()->authority->name === 'ディレクター')
                                <button type="submit" class="btn btn-primary">完了する</button>
                                @endif
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('todos.destroy', $todo->id) }}" method='post'>
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="submit" value='削除' class="btn btn-danger"
                                    onclick='return confirm("削除しますか？");'>
                            </form>
                        </td>
                    </tr>
                    <?php $num++ ?>
                    @endforeach
                </tbody>
            </table>
            <!-- Button trigger modal -->
            @foreach ($productName->steps as $step)
            @if ($filter['step'] === $step->step_name)
            <!-- ifの役割はURL（クエリ文字）と一致するstepのボタンを出す -->
            <!-- $filter[ 'step' ]はURL（クエリ文字）と一致するのはDBのstep_nameカラムだから -->
            <!-- if ($filter['step'] === $step->step_name)に初期値がないとエラーが出るため、MatterControllerのshowメソッドに初期値を記載する -->
            @if(Auth::user()->authority->name === 'ディレクター')
            <button type="button" class="btn btn-primary" data-toggle="modal"
                data-target="#documentModal{{ $step->id }}">
                提出
            </button>
            @endif

            <a href="{{ $step->google_drive_url }}">確認用URL</a>
            @endif
            @endforeach
            <!-- Button trigger modal -->
            @if(Auth::user()->authority->name === 'ディレクター')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                ワイヤーフレームを追加
            </button>
            @endif

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
                                        <input type="hidden" class="form-control" id="inputText" name="matter_id"
                                            value="{{ $matter->id }}">
                                        <!-- input 1つに対して送れるデータは１つ -->
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
        </div>
        <!-- 制作ステップ（右側） -->
        <div class="col-md-3">
            <div class="card" style="width: 18rem;">
                <ul class="list-group list-group-flush">
                    @foreach ($productName->steps as $step)
                    {{ $productName->title }}
                    <li class="list-group-item">{{ $step->step_name }},{{ $step->status }}</li>
                    <!-- Modal -->
                    <div class="modal fade" id="documentModal{{ $step->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('steps.update',$step->id) }}" method="POST">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="inputText" class="col-sm-2 col-form-label">Google Drive
                                                URL</label>
                                            <div class="col-sm-10">
                                                <input type="url" class="form-control" id="inputText"
                                                    name="google_drive_url">
                                                @isset($filter['state'])
                                                <input type="hidden" class="form-control" id="inputText" name="state"
                                                    value="{{ $filter['state'] }}">
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">保存</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
