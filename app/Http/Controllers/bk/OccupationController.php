<?php

namespace App\Http\Controllers;

use App\Http\Requests\OccupationRequest;
use App\Models\Occupation;
use Illuminate\Http\Request;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OccupationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'occupation_search') === 0) {
            $put_flg = true;
        }

        $cond_occupation = CommonUtility::GetQueryParameter($request, 'cond_occupation', $put_flg);

        $query = occupation::query();
        if (!empty($request->cond_occupation['occupation'])) {
            $query->where('occupation', 'like', '%'.$cond_occupation['occupation'].'%');
        }
        if (empty($cond_occupation)){
            $cond_occupation = ['occupation' => null];
        }
        $occupations = $query->paginate(10);
        return view('occupations.index', compact('occupations'))
            ->with('cond_occupation', $cond_occupation);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $occupations = Occupation::query()->get();
        return view('occupations.create', compact('occupations'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OccupationRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $occupation = new Occupation;

            //モデル->カラム名 = 値で、データを割り当てる
            $occupation->occupation = $request->input('occupation');

            //データベースに保存
            $occupation->save();

            session()->flash('success', '職業を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '職業を登録できませんでした。');
        };
        
        //リダイレクト
        return redirect()->route('occupation.index');
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
        //$idに一致するレコードを取得する
        $occupation = Occupation::query()->find($id);

        return view('occupations.edit', compact('occupation'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OccupationRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の職業を検索
            $occupation = Occupation::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $occupation->occupation = $request->input('occupation');

            //データベースに保存
            $occupation->save();

            DB::commit();
            session()->flash('success', '職業を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '職業を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('occupation.index');
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
            Occupation::find($id)->delete();

            DB::commit();
            session()->flash('success', '職業を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '職業を削除できませんでした。');
        };

        //リダイレクト
        return redirect()->route('occupation.index');
        //
    }
}
