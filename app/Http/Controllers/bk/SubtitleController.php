<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubTitleRequest;
use App\Models\Subtitle;
use Illuminate\Http\Request;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Support\Facades\DB;

class SubtitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'subtitle_search') === 0) {
            $put_flg = true;
        }

        $cond_subtitle = CommonUtility::GetQueryParameter($request, 'cond_subtitle', $put_flg);

        $query = subtitle::query();
        if (!empty($request->cond_subtitle['subtitle'])) {
            $query->where('subtitle', 'like', '%'.$cond_subtitle['subtitle'].'%');
        }
        if (empty($cond_subtitle)){
            $cond_subtitle = ['subtitle' => null];
        }
        $subtitles = $query->paginate(10);
        return view('subtitles.index', compact('subtitles'))
            ->with('cond_subtitle', $cond_subtitle);;
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subtitles.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubTitleRequest $request)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $subtitle = new Subtitle;

            //モデル->カラム名 = 値で、データを割り当てる
            $subtitle->subtitle = $request->input('subtitle');

            //データベースに保存
            $subtitle->save();

            session()->flash('success', '脇名称を登録しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '脇名称を登録できませんでした。');
        };

        return redirect()->route('subtitle.index');
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
        $subtitle = Subtitle::query()->find($id);

        return view('subtitles.edit', compact('subtitle'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubtitleRequest $request, $id)
    {
        DB::beginTransaction();
        try{ 
            //該当の脇名称を検索
            $subtitle = Subtitle::find($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $subtitle->subtitle = $request->input('subtitle');

            //データベースに保存
            $subtitle->save();

            DB::commit();
            session()->flash('success', '脇名称を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '脇名称を変更できませんでした。');
        };

        return redirect()->route('subtitle.index');
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
            Subtitle::find($id)->delete();

            DB::commit();
            session()->flash('success', '脇名称を削除しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '脇名称を削除できませんでした。');
        };
        //
    }
}
