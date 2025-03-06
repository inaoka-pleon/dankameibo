<?php

namespace App\Http\Controllers;

use App\Http\Requests\DankadivisionRequest;
use App\Models\Dankadivision;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DankadivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'dankadivision_search') === 0) {
            $put_flg = true;
        }

        $cond_dankadivision = CommonUtility::GetQueryParameter($request, 'cond_dankadivision', $put_flg);

        $query = Dankadivision::query();
        if (!empty($request->cond_dankadivision['dankadivision'])) {
            $query->where('dankadivision', 'like', '%'.$cond_dankadivision['dankadivision'].'%');
        }
        if (empty($cond_dankadivision)){
            $cond_dankadivision = ['dankadivision' => null];
        }
        $dankadivisions = $query->paginate(10);
        return view('dankadivisions.index', compact('dankadivisions'))
            ->with('cond_dankadivision', $cond_dankadivision);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dankadivisions.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DankadivisionRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $dankadivision = new Dankadivision;

            //モデル->カラム名 = 値で、データを割り当てる
            $dankadivision->dankadivision = $request->input('dankadivision');

            //データベースに保存
            $dankadivision->save();

            session()->flash('success', '檀家区分を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '檀家区分を登録できませんでした。');
        };

        //リダイレクト
        return redirect()->route('dankadivision.index');
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
        $dankadivision = Dankadivision::query()->find($id);

        return view('dankadivisions.edit', compact('dankadivision'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DankadivisionRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の地区名を検索
            $dankadivision = Dankadivision::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $dankadivision->dankadivision = $request->input('dankadivision');

            //データベースに保存
            $dankadivision->save();

            DB::commit();
            session()->flash('success', '檀家区分を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '檀家区分を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('dankadivision.index');
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
            Dankadivision::find($id)->delete();

            DB::commit();
            session()->flash('success', '檀家区分を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '檀家区分を削除できませんでした。');
        };
        //リダイレクト
        return redirect()->route('dankadivision.index');
        //
    }
}
