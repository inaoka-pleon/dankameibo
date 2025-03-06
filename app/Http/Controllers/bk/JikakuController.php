<?php

namespace App\Http\Controllers;

use App\Http\Requests\JikakuRequest;
use App\Models\Jikaku;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JikakuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'jikaku_search') === 0) {
            $put_flg = true;
        }

        $cond_jikaku = CommonUtility::GetQueryParameter($request, 'cond_jikaku', $put_flg);

        $query = jikaku::query();
        if (!empty($request->cond_jikaku['jikaku'])) {
            $query->where('jikaku', 'like', '%'.$cond_jikaku['jikaku'].'%');
        }
        if (empty($cond_jikaku)){
            $cond_jikaku = ['jikaku' => null];
        }
        $jikakus = $query->paginate(10);
        return view('jikakus.index', compact('jikakus'))
            ->with('cond_jikaku', $cond_jikaku);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jikakus.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JikakuRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $jikaku = new Jikaku;

            //モデル->カラム名 = 値で、データを割り当てる
            $jikaku->jikaku = $request->input('jikaku');

            //データベースに保存
            $jikaku->save();

            session()->flash('success', '寺格を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺格を登録できませんでした。');
        };

        return redirect()->route('jikaku.index');
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
        $jikaku = Jikaku::query()->find($id);

        return view('jikakus.edit', compact('jikaku'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JikakuRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の地区名を検索
            $jikaku = Jikaku::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $jikaku->jikaku = $request->input('jikaku');

            //データベースに保存
            $jikaku->save();

            DB::commit();
            session()->flash('success', '寺格を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺格を変更できませんでした。');
        };

        return redirect()->route('jikaku.index');
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
            Jikaku::find($id)->delete();

            DB::commit();
            session()->flash('success', '寺格を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺格を削除できませんでした。');
        };
        return redirect()->route('jikaku.index');
        //
    }
}
