<?php

namespace App\Http\Controllers;

use App\Http\Requests\QualificationRequest;
use App\Models\Qualification;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'qualification_search') === 0) {
            $put_flg = true;
        }

        $cond_qualification = CommonUtility::GetQueryParameter($request, 'cond_qualification', $put_flg);

        $query = Qualification::query();
        if (!empty($request->cond_qualification['qualification'])) {
            $query->where('qualification', 'like', '%'.$cond_qualification['qualification'].'%');
        }
        if (empty($cond_qualification)){
            $cond_qualification = ['qualification' => null];
        }
        $qualifications = $query->paginate(10);

        return view('qualifications.index', compact('qualifications'))
            ->with('cond_qualification', $cond_qualification);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('qualifications.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QualificationRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $qualification = new Qualification;

            //モデル->カラム名 = 値で、データを割り当てる
            $qualification->qualification = $request->input('qualification');

            //データベースに保存
            $qualification->save();

            session()->flash('success', '資格を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '資格を登録できませんでした。');
        };

        return redirect()->route('qualification.index');
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
        $qualification = Qualification::query()->find($id);

        return view('qualifications.edit', compact('qualification'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QualificationRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の資格を検索
            $qualification = Qualification::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $qualification->qualification = $request->input('qualification');

            //データベースに保存
            $qualification->save();

            DB::commit();
            session()->flash('success', '資格を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '資格を変更できませんでした。');
        };

        return redirect()->route('qualification.index');
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
            Qualification::find($id)->delete();

            DB::commit();
            session()->flash('success', '資格を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '資格を削除できませんでした。');
        };

        return redirect()->route('qualification.index');
        //
    }
}
