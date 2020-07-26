@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">案件作成</div>
                <div class="card-body">
                    <!-- ①フォームを作る  -->
                    <form action="{{ route('matters.store') }}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="NameFormControlInput1">会社名</label>
                            <input type="text" class="form-control" name="name" placeholder="クライアントの会社名を入力してください">
                        </div>
                        <div class="ml-3 form-group">
                            <div class="row">
                                <label for="CompanyNameFormControlSelect1">制作物</label>
                            </div>
                            <div class="form-check form-check-inline row">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="Webサイト"
                                    name="products[]">
                                <label class="form-check-label" for="inlineCheckbox1">Webサイト</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="LP"
                                    name="products[]">
                                <label class="form-check-label" for="inlineCheckbox2">LP</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="メディア"
                                    name="products[]">
                                <label class="form-check-label" for="inlineCheckbox3">メディア</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="その他"
                                    name="products[]">
                                <label class="form-check-label" for="inlineCheckbox3">その他</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="CommentsFormControlTextarea">備考</label>
                            <textarea class="form-control" name="comments" placeholder="備考を入力してください"
                                rows="3"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">作成</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
