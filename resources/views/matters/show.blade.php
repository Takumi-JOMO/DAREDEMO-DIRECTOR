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
                        <p class="card-text">制作物　　　：
                            @foreach ($matter->products as $product)
                            {{ $product->title }}　
                            @endforeach
                        </p>
                        <p>案件作成日時：{{ $matter->created_at }}</p>
                        <p>備考　　　　：{{ $matter->comments }}</p>

                    </div>
                    <div class="col-md-6">
                        @if(Auth::user()->authority->name === 'ディレクター')
                        <form action="{{ route('users.store') }}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="exampleInputEmail1">お客様／デザイナー／プログラマーのEmail</label>
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
                        <div class="row mt-4">
                            <!-- Button trigger modal -->
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary mr-4" data-toggle="modal"
                                    data-target="#imageModal">
                                    画像格納フォルダのURLを追加
                                </button>
                                <a href="{{ $productName->image_url }}" target="_blank">
                                    画像を確認
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                        aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('products.updateUrl',$productName->id) }}" method="POST">
                                {{csrf_field()}}
                                {{method_field('PATCH')}}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel">画像格納フォルダのURLを提出</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="inputText" class="col-md-4 col-form-label">URL</label>
                                            <div class="col-md-10">
                                                <input type="url" class="form-control" id="inputText" name="image_url">
                                                @isset($filter['state'])
                                                <input type="hidden" class="form-control" id="inputText" name="state"
                                                    value="{{ $filter['state'] }}">
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">close</button>
                                        <button type="submit" class="btn btn-primary">save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    @if(Auth::user()->authority->name === 'ディレクター')
    <button type="button" class="btn btn-primary mt-1 float-right" data-toggle="modal" data-target="#ganttChartModal">
        <!-- # === id なので "#ganttChart" === id="ganttChartModal"-->
        ガントチャートを提出
    </button>
    @endif
    <!-- Modal -->
    <div class="modal fade" id="ganttChartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                <input type="text" class="form-control" value="{{$product->director_gantt_chart_url}}"
                                    id="inputText" name="director_gantt_chart_url[]" placeholder="ディレクター用ガントチャート">
                                <!-- []（配列）は[]に変数をいくつも入れられる -->
                            </div>
                            <div class="col-sm-10  mt-2">
                                <input type="text" class="form-control" value="{{$product->customer_gantt_chart_url}}"
                                    id="inputText" name="customer_gantt_chart_url[]" placeholder="お客様用ガントチャート">
                            </div>
                            <div class="col-sm-10  mt-2">
                                <input type="text" class="form-control"
                                    value="{{$product->designer_engineer_gantt_chart_url}}" id="inputText"
                                    name="designer_engineer_gantt_chart_url[]" placeholder="デザイナー／エンジニア用ガントチャート">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TODO -->
    <div class="row mt-5">
        <div class="col-md-10">
            <!-- ガントチャート下のステップ -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach($productName->steps as $step)
                <li class="nav-item" role="presentation">
                    <a class="nav-link small col-md-12 pr-2 pl-0 mx-0 {{(isset($filter['step']) && $filter['step'] === $step->step_name) ? 'active' : ''}}"
                        id="{{$step->id}}" href="?state={{$productName->title}}&step={{$step->step_name}}" role="tab"
                        aria-controls="{{$step->id}}" aria-selected="true">{{ $step->step_name }}</a>
                    <!-- hrefで$productNameとしているのは、$productには「その他」が入ってしまっているため -->
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- 制作ステップ -->
    <div class="row">
        <div class="col-md-10">
            <!-- drive url 確認用ボタン元の場所 -->

            <table class="table table-bordered table-striped mb-0">
                <!-- <table class="table table-bordered py-2"> -->
                <tr class="bg-primary text-white">
                    <th class="h-n" scope="col">#</th>
                    <th class="h-p" scope="col">ページ／セクション</th>
                    <th class="h-c" scope="col">コメント</th>
                    <th class="h-s" scope="col">ステータス</th>
                    <th class="h-sc" scope="col">ステータス変更</th>
                    <th class="h-d" scope="col">削除</th>
                </tr>
            </table>
            <div class="table-wrapper-scroll-y my-custom-scrollbar t-h">

                <table class="table table-bordered table-striped mb-0">
                    <tbody>
                        <?php $num=1 ?>
                        @foreach($todos as $todo)
                        <tr>
                            <th class="h-n pt-3" scope="row">{{ $num }}</th>
                            <td class="h-p pt-3">{{ $todo->todo_name }}</td>
                            <td class="h-c py-2">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#commentModal{{ $todo->id }}">
                                    確認
                                </button>
                                <!-- Comment Modal -->
                                <div class="modal fade" id="commentModal{{ $todo->id }}" tabindex="-1" role="dialog"
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
                                                    @if($todo->comments)
                                                    @foreach( $todo -> comments as $comment)
                                                        <div class="exampleFormControlTextarea1">
                                                            <p class="my-0">投稿者　：{{ $comment->user->name }}</p>
                                                            <p class="my-0">投稿日時：{{ $comment->created_at }}</p>
                                                            <p class="card-text">コメント：{{ $comment->body }}</p>
                                                            <p class="mt-3 mb-0">コメントを残す</p>
                                                        </div>
                                                    @endforeach
                                                    @endif
                                                        <input type="text" class="form-control" id="inputText"
                                                            name="body">
                                                        <input type="hidden" class="form-control" id="inputText"
                                                            name="todo_id" value="{{ $todo->id }}">
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
                            <td class="h-s pt-3 pb-0">
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
                            <td class="h-sc py-2">
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
                            <td class="h-d py-2">
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
            @foreach ($productName->steps as $step)
            @if ($filter['step'] === $step->step_name)
            <!-- ifの役割はURL（クエリ文字）と一致するstepのボタンを出す -->
            <!-- $filter[ 'step' ]はURL（クエリ文字）と一致するのはDBのstep_nameカラムだから -->
            <!-- if ($filter['step'] === $step->step_name)に初期値がないとエラーが出るため、MatterControllerのshowメソッドに初期値を記載する -->
            @if(Auth::user()->authority->name === 'ディレクター')
            <button type="button" class="btn btn-primary my-3 mr-2" data-toggle="modal"
                data-target="#documentModal{{ $step->id }}">
                制作物を提出
            </button>
            @endif

            <a href="{{ $step->google_drive_url }}" target="_blank">制作物を確認</a>
            @endif
            @endforeach
            <!-- Button trigger modal -->
            @if(Auth::user()->authority->name === 'ディレクター')
            <button type="button" class="btn btn-primary float-md-right my-3" data-toggle="modal"
                data-target="#exampleModal">
                ページ／セクションを追加
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
                                <h5 class="modal-title" id="exampleModalLabel">ページ／セクションを追加</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <h5 class="col-sm-12 my-0">ページ／セクションを追加してください</h5>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control my-2" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control my-2" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control my-2" id="inputText" name="todo_names[]">
                                        <input type="text" class="form-control my-2" id="inputText" name="todo_names[]">
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
            <table class="table table-bordered">
                <tr class="list-group list-group-flush">
                    <li class="list-group-item bg-primary">
                        <p class="mb-0 text-white font-weight-bold">
                            制作ステップ
                        </p>
                    </li>
                    @foreach ($productName->steps as $step)
                    <li class="list-group-item  px-2 py-1 s-h">
                        <p class="mb-0">{{ $step->step_name }}</p>
                        @if($step->status === "未完了")
                        <p class="mb-0 text-danger">{{ $step->status }}</p>
                        @else
                        <p class="mb-0">{{ $step->status }}</p>
                        @endif
                    </li>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
<!-- Modal -->
@foreach ($productName->steps as $step)
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
                            <input type="url" class="form-control" id="inputText" name="google_drive_url">
                            @isset($filter['state'])
                            <input type="hidden" class="form-control" id="inputText" name="state"
                                value="{{ $filter['state'] }}">
                            @endisset
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
@endforeach
@endsection
