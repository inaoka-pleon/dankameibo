<?php

namespace App\Http\Controllers;

use App\Http\Requests\TitleRequest;
use App\Models\Title;
use Illuminate\Http\Request;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Support\Facades\DB;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'title_search') === 0) {
            $put_flg = true;
        }

        $cond_title = CommonUtility::GetQueryParameter($request, 'cond_title', $put_flg);

        $query = title::query();
        if (!empty($request->cond_title['title'])) {
            $query->where('title', 'like', '%'.$cond_title['title'].'%');
        }
        if (empty($cond_title)){
            $cond_title = ['title' => null];
        }
        $titles = $query->paginate(10);
        return view('titles.index', compact('titles'))
            ->with('cond_title', $cond_title);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('titles.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TitleRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $title = new Title;

            //モデル->カラム名 = 値で、データを割り当てる
            $title->title = $request->input('title');

            //データベースに保存
            $title->save();

            session()->flash('success', '敬称を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '敬称を登録できませんでした。');
        };

        return redirect()->route('title.index');
        //
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
        $title = Title::query()->find($id);

        return view('titles.edit', compact('title'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TitleRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の敬称を検索
            $title = Title::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $title->title = $request->input('title');

            //データベースに保存
            $title->save();

            DB::commit();
            session()->flash('success', '敬称を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '敬称を変更できませんでした。');
        };

        return redirect()->route('title.index');
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
        DB::beginTransaction();
        try {
            //該当のレコードを探して、deleteメソッドを呼び出す
            Title::find($id)->delete();

            DB::commit();
            session()->flash('success', '敬称を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '敬称を削除できませんでした。');
        };

        return redirect()->route('title.index');
        //
    }
}
