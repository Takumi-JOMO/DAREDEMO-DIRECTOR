<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use App\Product;
use App\Step;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
        $steps = Step::where('matter_id',$request->matter_id)->where('product_id',$productName->id)->get();
        // dd($request);
        foreach ($steps as $step){
            foreach ($request->todo_names as $todo_name){
            if ($todo_name === null){
                continue;
            }
            $todo = new Todo;
            $todo -> todo_name = $todo_name;
            $todo -> step_id = $step->id;
            $todo -> product_id = $productName->id;
            $todo -> status = "進行中";
            $todo -> save();
            }
        }

        if (isset($filter['step'])) {
        $step = Step::where('step_name',$filter['step'])->where('product_id',$productName->id)->where('matter_id',$request->matter_id)->first();
        $countTodos = Todo::where('product_id',$productName->id)->where('step_id',$step->id)->where('status','!=','完了')->count();

        if ($countTodos === 0){
            $step -> status = "完了";
            $step -> save();
            
        }else{
            $step -> status = "未完了";
            $step -> save();
        }
    }else{
        $step = Step::where('step_name','ワイヤーフレーム作成')->where('product_id',$productName->id)->where('matter_id',$request->matter_id)->first();
        $countTodos = Todo::where('product_id',$productName->id)->where('step_id',$step->id)->where('status','!=','完了')->count();

        if ($countTodos === 0){
            $step -> status = "完了";
            $step -> save();
            
        }else{
            $step -> status = "未完了";
            $step -> save();
        }
    }

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
        // dd($request);
        $todo = Todo::find($id);
        // dd($todo->product->matter);
        $todo -> status    = $request -> status;
        
        $todo -> save();
        
        // dd($request->step);
        $step = Step::where('matter_id',$todo->product->matter->id)->where('product_id',$todo->product->id)->where('step_name',$request->step)->first();
        // 開いているページのステップを探している
        // where('step_name',$request->step)->first();のstepは$requestの中身が入っている

        $countTodos = Todo::where('product_id',$todo->product->id)->where('step_id',$step->id)->where('status','!=','完了')->count();
        
        if ($countTodos === 0){
            $step -> status = "完了";
            $step -> save();
            
        }else{
            $step -> status = "未完了";
            $step -> save();
        }
        // dd($step);










        
        return redirect('matters/' . $todo->product->matter->id . '?state=' . $request->state . '&step=' . $request->step);
        // phpの変数と文字列の結合を行い、matters詳細ページのURLに合わせてURLを作成した

        // return redirect()->route('matters.show',$todo->product->matter->id);
        // return redirect()->routeはMatterControllerのshowメソッドに飛ぶ
        // $todo->product->matter->idはリレーションの階層を下から辿っているのでidはmatterのidになる

        // return view('matters.show', compact('todo'));は動かない
        // compactはブレードファイルに変数を渡す
        // return view はbladeに直接飛ぶ
        // Mattersのshowbaldeファイルに飛ばすのはMatterControllerのShowメソッドにコーディング済みのため（コーディングの二度手間を省くため）
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        $todos =Todo::where('product_id',$todo->product->id)->where('todo_name',$todo->todo_name)->get();

        foreach ($todos as $todoList){
        $todoList -> delete();
        }

        foreach ($todo->product->steps as $step){
            // リレーション（$todo belongsTo product hasMany steps）
            $countTodos = Todo::where('product_id',$todo->product->id)->where('step_id',$step->id)->where('status','!=','完了')->count();
            
            if ($countTodos === 0){
                $step -> status = "完了";
                $step -> save();
                
            }else{
                $step -> status = "未完了";
                $step -> save();
            }
        }
        
        return redirect()->route('matters.show', $todo->product->matter->id );
    }
}
