<?php

namespace App\Http\Controllers;

use App\Http\Requests\TempleOfficeRequest;
use App\Models\Templeoffice;
use Illuminate\Http\Request;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Support\Facades\DB;

class TempleofficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'templeoffice_search') === 0) {
            $put_flg = true;
        }

        $cond_templeoffice = CommonUtility::GetQueryParameter($request, 'cond_templeoffice', $put_flg);

        $query = templeoffice::query();
        if (!empty($request->cond_templeoffice['templeoffice'])) {
            $query->where('templeoffice', 'like', '%'.$cond_templeoffice['templeoffice'].'%');
        }
        if (empty($cond_templeoffice)){
            $cond_templeoffice = ['templeoffice' => null];
        }
        $templeoffices = $query->paginate(10);
        return view('templeoffices.index', compact('templeoffices'))
            ->with('cond_templeoffice', $cond_templeoffice);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('templeoffices.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TempleOfficeRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $templeoffice = new Templeoffice;

            //モデル->カラム名 = 値で、データを割り当てる
            $templeoffice->templeoffice = $request->input('templeoffice');

            //データベースに保存
            $templeoffice->save();

            session()->flash('success', '宗務所を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '宗務所を登録できませんでした。');
        };

        return redirect()->route('templeoffice.index');
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
        $templeoffice = Templeoffice::query()->find($id);

        return view('templeoffices.edit', compact('templeoffice'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TempleOfficeRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の宗務所を検索
            $templeoffice = Templeoffice::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $templeoffice->templeoffice = $request->input('templeoffice');

            //データベースに保存
            $templeoffice->save();

            DB::commit();
            session()->flash('success', '宗務所を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '宗務所を変更できませんでした。');
        };

        return redirect()->route('templeoffice.index');
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
            Templeoffice::find($id)->delete();

            DB::commit();
            session()->flash('success', '宗務所を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '宗務所を削除できませんでした。');
        };

        return redirect()->route('templeoffice.index');
        //
    }
}
