<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use App\Product;
use App\Step;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
        //todoに保存するstep_idを探す->step_idを探すためには特定の$stepを探す必要がある->特定をするためにはproduct_id, matter_id, step_nameが必要なので(matter_idはbladeから送信済みなのでOK) product_idはクエリ文字を利用して検索し、step_nameは”ワイヤーフレーム作成”で検索をする
        // $matter = Matter::find($request->matter_id);を使用する場合は$matter->idで検索する
        $filter = $request->query();
        //URLに？がついているものをクエリ文字と呼び、$request->query();はクエリ文字を取得するための定型文である
        //phpでは右辺から左辺に代入する
        // dd($filter['state']);
        if (isset($filter['state'])) {
            if ($filter['state'] === "Webサイト") {    
                $productName = Product::where('title','Webサイト')->where('matter_id',$request->matter_id)->first();
                //Product=モデル名→whereで（カラム名から,特定の文字を検索）→その中でさらにwhereで(カラム名から,特定の文字を検索)し、firstで１つ取得する
                // $request->matter_idとなっているのはbladeファイルからユーザーから送信されたデータを取得する必要があるため（ユーザーが送信するデータは$requesで送られる）
            }elseif($filter['state'] === "LP") {
                $productName = Product::where('title','LP')->where('matter_id',$request->matter_id)->first();
            }elseif($filter['state'] === "メディア") {
                $productName = Product::where('title','メディア')->where('matter_id',$request->matter_id)->first();
            }elseif($filter['state'] === "その他") {
                $productName = Product::where('title','その他')->where('matter_id',$request->matter_id)->first();
            }
        }else{
            $productName = Product::where('matter_id',$request->matter_id)->first();
            // where('title','Webサイト')があると、Webサイトを制作しない案件の場合エラーが出てしまうため 
        }
        $step = Step::where('step_name','ワイヤーフレーム作成')->where('matter_id',$request->matter_id)->where('product_id',$productName->id)->first();
        // dd($step);
        $todo = new Todo;
        $todo -> todo_name = $request -> todo_name;
        $todo -> step_id = $step->id;
        $todo -> save();

        return redirect()->route('matters.show', $request->matter_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

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
