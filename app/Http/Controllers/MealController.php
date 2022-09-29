<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealRequest;
use App\Models\Meal;
use App\Models\Category;
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
        // Post::latest()メソッドでcreated_atの降順で
        // データを取得するクエリを生成します。
        // get()メソッドで、生成したクエリでデータを取得
        // ２.$meals = Meal::latest()->get();
        // 3.$meals = Meal::latest()->simplePaginate(4);
        // N+1問題の解決のため、Postモデルに関連するユーザー情報を取得したいので、Meal::with('user')
        $meals = Meal::with('user')->latest()->paginate(4);
        return view('meals.index')->with(compact('meals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::All();
        return view('meals.create')->with(compact('categories'));

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
        // 1.ユーザーID
        $meal->user_id = $request->user()->id;
        // 2.カテゴリーID
        $meal->category_id = $request->list;
        // 3.画像
        $file = $request->file('image');
        $meal->image = self::createFileName($file);
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
    // like

    // 現在時刻の設定https://qiita.com/kohboh/items/0e255dc3bba067bc447c
    $today = now();
    // 全体
    return view('meals.show')->with(compact('meal', 'today'));
}
/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function edit(meal $meal)
{
    $categories = Category::all();
    return view('meals.edit')
        ->with(compact('meal', 'categories'));
}
/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\MealRequest  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function update(MealRequest $request, meal $meal)
{
    
    // なしすまし対策
    if ($request->user()->cannot('update', $meal)) {
        return redirect()->route('meals.show', $meal)
            ->withErrors('自分の記事以外は更新できません');
    }
    // 2.カテゴリーID input name＝listに合わせて記載
    $meal->category_id = $request->list;
        
    // 3.画像
    // updateされているか
        $file = $request->file('image');
        // ふぁいるがあるかどうか、あれば処理
        if ($file) {
            // ファイルが有れば、削除用のファイル名・場所を決めている
            $delete_file_path = $meal->image_path;

            //新しいファイル名を追加。新規作成と同じ方法。
            //下記にアクション設定$meal->image = date('YmdHis') . '_' . $file->getClientOriginalName();
            $meal->image = self::createFileName($file);
        }
        $meal->fill($request->all());

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 更新。準備していた記事の情報を保存。
            $meal->save();

            if ($file) {
                // 画像アップロード
                $chk = Storage::putFileAs('images/meals', $file, $meal->image);
                
                if (!$chk) {
                    // 例外を投げてロールバックさせる。 throw new \Exceptionでエラーを出す。
                    throw new \Exception('画像ファイルの保存に失敗しました。');
                }

                // 画像削除
                if (!Storage::delete($delete_file_path)) {
                    //アップロードした画像を削除する
                    Storage::delete($meal->image_path);
                    //例外を投げてロールバックさせる
                    throw new \Exception('画像ファイルの削除に失敗しました。');
                }
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            //前のページに戻る。          エラーを出す。
            return back()->withInput()->withErrors($e->getMessage());
        }
        //成功した場合のreturn
        return redirect()->route('meals.show', $meal)
            ->with('notice', '記事を更新しました');
    }

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function destroy($id)
{
    $meal = Meal::find($id);
    // トランザクション開始
    DB::beginTransaction();
    try {
        $meal->delete();
        // 画像削除
        // アクセサif (!Storage::delete('images/meals/' . $meal->image)) {
        if (!Storage::delete($meal->image_path)) {
            // 例外を投げてロールバックさせる
            throw new \Exception('画像ファイルの削除に失敗しました。');
        }
        // トランザクション終了(成功)
        DB::commit();
    } catch (\Exception $e) {
        // トランザクション終了(失敗)
        DB::rollback();
        return back()->withInput()->withErrors($e->getMessage());
    }
    return redirect()
        ->route('meals.index')
        ->with('notice', '記事を削除しました');
}



    public static function createFileName($file)
{
    return date('YmdHis') . '_' . $file->getClientOriginalName();
    }
}
