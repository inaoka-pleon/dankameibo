<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'position_search') === 0) {
            $put_flg = true;
        }

        $cond_position = CommonUtility::GetQueryParameter($request, 'cond_position', $put_flg);

        $query = position::query();
        if (!empty($request->cond_position['position'])) {
            $query->where('position', 'like', '%'.$cond_position['position'].'%');
        }
        if (empty($cond_position)){
            $cond_position = ['position' => null];
        }
        $positions = $query->paginate(10);
        return view('positions.index', compact('positions'))
            ->with('cond_position', $cond_position);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('positions.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $position = new Position;

            //モデル->カラム名 = 値で、データを割り当てる
            $position->position = $request->input('position');

            //データベースに保存
            $position->save();

            session()->flash('success', '寺役職を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺役職を登録できませんでした。');
        };

        //リダイレクト
        return redirect()->route('position.index');
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
        $position = Position::query()->find($id);

        return view('positions.edit', compact('position'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の寺役職を検索
            $position = Position::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $position->position = $request->input('position');

            //データベースに保存
            $position->save();

            DB::commit();
            session()->flash('success', '寺役職を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺役職を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('position.index');
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
            Position::find($id)->delete();

            DB::commit();
            session()->flash('success', '寺役職を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺役職を削除できませんでした。');
        };
        //リダイレクト
        return redirect()->route('position.index');
        //
    }
}
