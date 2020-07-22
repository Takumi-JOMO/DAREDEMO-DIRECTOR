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
                <a href="{{ route('matters.index') }}">案件一覧ページへ戻る</a>
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
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="email" class="form-control" id="exampleInputEmail1"
                                            aria-describedby="emailHelp" name="email">
                                        <input type="hidden" class="form-control" id="inputText" name="matter_id"
                                            value="{{ $matter->id }}">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- プロダクトのタブ -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach($matter->products as $product)
        <li class="nav-item" role="presentation">
            <a class="nav-link {{(isset($filter['state']) && $filter['state'] === $product->title) ? 'active' : ''}}"
                id="{{$product->id}}" href="?state={{$product->title}}" role="tab" aria-controls="{{$product->id}}"
                aria-selected="true">{{ $product->title }}</a>
        </li>
        @endforeach
    </ul>
    <div class="col-md-12 pl-0">
        @if(Auth::user()->authority->name === "ディレクター")
        <iframe src="{{$productName->director_gantt_chart_url}}" width="1110" height="800">
        </iframe>
        @elseif(Auth::user()->authority->name === "プログラマー／デザイナー")
        <iframe src="{{$productName->designer_engineer_gantt_chart_url}}" width="1110" height="800">
        </iframe>
        @elseif(Auth::user()->authority->name === "お客様")
        <iframe src="{{$productName->customer_gantt_chart_url}}" width="1110" height="800">
        </iframe>
        @endif
    </div>



    <!-- TODO -->
    <div class="row">
        <div class="col-md-10">
            <p>ＴＯＤＯ</p>
            <!-- ガントチャート下のステップ -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach($productName->steps as $step)
                <li class="nav-item" role="presentation">
                    <u>
                        <a class="nav-link small col-md-12 pr-2 pl-0 mx-0 {{(isset($filter['step']) && $filter['step'] === $step->step_name) ? 'active' : ''}}"
                            id="{{$step->id}}" href="?state={{$productName->title}}&step={{$step->step_name}}"
                            role="tab" aria-controls="{{$step->id}}" aria-selected="true">{{ $step->step_name }}</a>
                        <!-- hrefで$productNameとしているのは、$productには「その他」が入ってしまっているため -->
                    </u>
                </li>
                @endforeach
            </ul>
            <!-- drive url 確認用ボタン元の場所 -->
            <!-- Button trigger modal -->
            @foreach ($productName->steps as $step)
            @if ($filter['step'] === $step->step_name)
            <!-- ifの役割はURL（クエリ文字）と一致するstepのボタンを出す -->
            <!-- $filter[ 'step' ]はURL（クエリ文字）と一致するのはDBのstep_nameカラムだから -->
            <!-- if ($filter['step'] === $step->step_name)に初期値がないとエラーが出るため、MatterControllerのshowメソッドに初期値を記載する -->
            <div class="position-relative my-2">
                @if(Auth::user()->authority->name === 'ディレクター')
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#documentModal{{ $step->id }}">
                    制作物を提出
                </button>
                @endif
                <!-- <button type="button" class="btn btn-primary"> -->
                <a href="{{ $step->google_drive_url }}" target="_blank">
                    制作物を確認
                </a>
                </button>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <table class="table table-bordered table-striped mb-0">
                <!-- <table class="table table-bordered py-2"> -->
                <tr class="bg-primary text-white">
                    <th class="t-w-40" scope="col">#</th>
                    <th class="t-w-200" scope="col">ページ／セクション</th>
                    <th class="t-w-150" scope="col">コメント</th>
                    <th class="t-w-150" scope="col">ステータス</th>
                    <th class="t-w-150" scope="col">ステータス変更</th>
                    <th class="t-w-150" scope="col">削除</th>
                </tr>
            </table>

            <div class="table-wrapper-scroll-y my-custom-scrollbar">

                <table class="table table-bordered table-striped mb-0">
                    <tbody>
                        <?php $num=1 ?>
                        @foreach($todos as $todo)
                        <tr>
                            <th class="t-w-40" scope="row">{{ $num }}</th>
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
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('comments.store') }}" method="POST">
                                                {{ csrf_field() }}
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="exampleFormControlTextarea1">
                                                            <p>投稿者：</p>
                                                            <p class="card-text">内容：</p>
                                                            <p>投稿日時：</p>
                                                            <button type="button"
                                                                class="btn btn-primary">確認しました</button>
                                                        </div>
                                                        <textarea class="form-control" name="comment"
                                                            id="exampleFormControlTextarea1" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">保存</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">閉じる</button>
                                                </div>
                                            </form>
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
            </div>
            <!-- Button trigger modal -->
            @if(Auth::user()->authority->name === 'ディレクター')
            <button type="button" class="btn btn-primary position-relative" data-toggle="modal"
                data-target="#exampleModal">
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
                                <h5 class="modal-title" id="exampleModalLabel">ワイヤーフレームを追加</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <h5 class="col-sm-12">ワイヤーフレームを追加してください</h5>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control" id="inputText" name="todo_names[]">
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
        <div class="col-md-2 pl-0">
            <table class="table table-bordered position-relative py-2">
                <!-- テーブルテスト -->
                <!-- <div class="card position-relative"> -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-primary">
                        <p class="mb-0 text-white font-weight-bold">制作ステップ</p>
                    </li>
                    @foreach ($productName->steps as $step)
                    <li class="list-group-item  px-2 py-1">
                        <p class="mb-0">{{ $step->step_name }}</p>
                        @if($step->status === "未完了")
                        <p class="mb-0 text-danger">{{ $step->status }}</p>
                        @else
                        <p class="mb-0">{{ $step->status }}</p>
                        @endif
                    </li>
                    <!-- Modal -->
                    <div class="modal fade" id="documentModal{{ $step->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('steps.update',$step->id) }}" method="POST">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">制作物を提出</h5>
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
