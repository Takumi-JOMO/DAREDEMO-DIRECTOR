<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Matter;
use App\Product;
use App\Step;
use Auth;

class MatterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matters = Matter::all();
        return view('matters.index', compact('matters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('matters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $matter = new Matter;
        $matter -> name = $request -> name;
        $matter -> comments = $request -> comments;
        $matter -> save();

        $matter->users()->attach(Auth::id());//matterとユーザーを紐づける（中間テーブル）

        foreach($request->products as $data){
        $product = new Product;
        $product -> title = $data;   
        $product -> matter_id = $matter->id; 
        $product -> save();

        $stepNames = [
            "お見積もり",
            "ヒアリング",
            "ワイヤーフレーム作成",
            "ワイヤーフレーム修正",
            "文言作成",
            "文言修正",
            "デザイン作成",
            "デザイン修正",
            "コーディング",
            "動作チェック",
            "公開",
        ];
        foreach ($stepNames as $name){
            $step = new Step;
            $step -> step_name = $name;//step_nameはカラム名であり、DBのカラム名と一致している必要がある
            $step -> matter_id = $matter->id;
            $step -> product_id = $product->id;
            $step -> save();
            }//$stepNames = []は配列であり、foreachで配列の中身を1つずつ取得している
        }//matterを作成する際にチェックしたproductを作成・保存している
        // 43-55行　1つのメソッドで複数のモデルをいじるケースで使う
        
        return redirect()->route('matters.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $matter = Matter::find($id);
        $filter = $request->query();
        //URLに？がついているものをクエリ文字と呼び、$request->query();はクエリ文字を取得するための定型文である
        //phpでは右辺から左辺に代入する
        // dd($filter['state']);
        if (isset($filter['state'])) {
            if ($filter['state'] === "Webサイト") {    
                $productName = Product::where('title','Webサイト')->where('matter_id',$matter->id)->first();
            }elseif($filter['state'] === "LP") {
                $productName = Product::where('title','LP')->where('matter_id',$matter->id)->first();
            }elseif($filter['state'] === "メディア") {
                $productName = Product::where('title','メディア')->where('matter_id',$matter->id)->first();
            }elseif($filter['state'] === "その他") {
                $productName = Product::where('title','その他')->where('matter_id',$matter->id)->first();
            }
        }else{
            $productName = Product::where('matter_id',$matter->id)->first();
            // where('title','Webサイト')があると、Webサイトを制作しない案件の場合エラーが出てしまうため 
        }
        // dd($product);
        return view('matters.show',compact('matter','productName'));
    }
        //isset()は()内の文字がある場合trueなので、案件一覧ページから詳細に飛んだ際はクエリ文字が見つからずelseに飛ぶ 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
