<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'area_search') === 0) {
            $put_flg = true;
        }
        //一覧検索パラメータ取得
        $cond_area = CommonUtility::GetQueryParameter($request, 'cond_area', $put_flg);

        $query = Area::query();
        if (!empty($request->cond_area['area'])) {
            $query->where('area', 'like', '%'.$cond_area['area'].'%');
        }
        if (empty($cond_area)){
            $cond_area = ['area' => null];
        }
        $areas = $query->paginate(10);

        //変数$areasに入れて、ビューファイルにareasという変数名で渡す
        return view('areas.index', compact('areas'))
            ->with('cond_area', $cond_area);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('areas.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $area = new Area;

            //モデル->カラム名 = 値で、データを割り当てる
            $area->area = $request->input('area');

            //データベースに保存
            $area->save();

            session()->flash('success', '地区名を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '地区名を登録できませんでした。');
        };

        //リダイレクト
        return redirect()->route('area.index');
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
        $area = Area::query()->find($id);

        return view('areas.edit', compact('area'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AreaRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の地区名を検索
            $area = Area::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $area->area = $request->input('area');

            //データベースに保存
            $area->save();

            DB::commit();
            session()->flash('success', '地区名を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '地区名を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('area.index');
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
            Area::find($id)->delete();

            DB::commit();
            session()->flash('success', '地区名を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '地区名を削除できませんでした。');
        };

        //リダイレクト
        return redirect()->route('area.index');
        //
    }
}
