<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealRequest;
use App\Models\Meal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('meals.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MealRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MealRequest $request)
    {
        $meal = new Meal($request->all());
        $meal->user_id = $request->user()->id;

        $file = $request->file('image');
        $meal->image = date('YmdHis') . '_' . $file->getClientOriginalName();

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 登録
            $meal->save();

            // 画像アップロード
            if (!Storage::putFileAs('images/meals', $file, $meal->image)) {
                // 例外を投げてロールバックさせる
                throw new \Exception('画像ファイルの保存に失敗しました。');
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('meals.show', $meal)
            // フラッシュメッセージの追加 UXユーザーエクスペリエンス的に良くする
            ->with('notice', '食事記録を登録しました！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(meal $meal)
    {
        
        // 現在時刻の設定https://qiita.com/kohboh/items/0e255dc3bba067bc447c
        $today = now(); 
        return view('meals.show')->with(compact('meal', 'today'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('meals.edit');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MealRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MealRequest $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(meal $meal)
    {
        
        // // トランザクション開始
        // DB::beginTransaction();
        // try {
        //     $meal->delete();

        //     // 画像削除.$meal->image->imade_pathはmeal.phpに定義済。
        //     if (!Storage::delete($meal->image->imade_path)) {
        //         // 例外を投げてロールバックさせる
        //         throw new \Exception('画像の削除に失敗しました。');
        //     }

        //     // トランザクション終了(成功)
        //     DB::commit();
        // } catch (\Exception $e) {
        //     // トランザクション終了(失敗)
        //     DB::rollback();
        //     return back()->withInput()->withErrors($e->getMessage());
        // }

        return redirect()->route('meals.index')
            ->with('notice', '食事記録を削除しました');
    }
}
