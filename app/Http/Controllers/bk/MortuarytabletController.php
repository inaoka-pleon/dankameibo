<?php

namespace App\Http\Controllers;

use App\Http\Requests\MortuarytabletRequest;
use App\Models\Mortuarytablet;
use Illuminate\Http\Request;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MortuarytabletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'mortuarytablet_search') === 0) {
            $put_flg = true;
        }

        $cond_mortuarytablet = CommonUtility::GetQueryParameter($request, 'cond_mortuarytablet', $put_flg);

        $query = mortuarytablet::query();
        if (!empty($request->cond_mortuarytablet['mortuarytablet'])) {
            $query->where('mortuarytablet', 'like', '%'.$cond_mortuarytablet['mortuarytablet'].'%');
        }
        if (empty($cond_mortuarytablet)){
            $cond_mortuarytablet = ['mortuarytablet' => null];
        }
        $mortuarytablets = $query->paginate(10);
        return view('mortuarytablets.index', compact('mortuarytablets'))
            ->with('cond_mortuarytablet', $cond_mortuarytablet);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mortuarytablets.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MortuarytabletRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $mortuarytablet = new Mortuarytablet;

            //モデル->カラム名 = 値で、データを割り当てる
            $mortuarytablet->mortuarytablet = $request->input('mortuarytablet');

            //データベースに保存
            $mortuarytablet->save();

            session()->flash('success', '位牌区分を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '位牌区分を登録できませんでした。');
        };
        //リダイレクト
        return redirect()->route('mortuarytablet.index');
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
        //$idに一致するレコードを取得できる
        $mortuarytablet = Mortuarytablet::query()->find($id);

        return view('mortuarytablets.edit', compact('mortuarytablet'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MortuarytabletRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の位牌区分を検索
            $mortuarytablet = Mortuarytablet::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $mortuarytablet->mortuarytablet = $request->input('mortuarytablet');

            //データベースに保存
            $mortuarytablet->save();

            DB::commit();
            session()->flash('success', '位牌区分を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '位牌区分を変更できませんでした。');
        };
        //リダイレクト
        return redirect()->route('mortuarytablet.index');
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
            Mortuarytablet::find($id)->delete();

            DB::commit();
            session()->flash('success', '位牌区分を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '位牌区分を削除できませんでした。');
        };
        //リダイレクト
        return redirect()->route('mortuarytablet.index');
        //
    }
}
