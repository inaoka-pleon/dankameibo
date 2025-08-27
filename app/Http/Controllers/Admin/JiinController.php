<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JiinRequest;
use App\Models\Jiin;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JiinController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'jiin_search') === 0) {
            $put_flg = true;
        }
        // 一覧検索パラメータ取得
        $cond_jiin = CommonUtility::GetQueryParameter($request, 'cond_jiin', $put_flg);

        $query = Jiin::query();
        if (!empty($request->cond_jiin['jiin_name'])) {
            $query->where('jiin_name', 'like', '%'.$cond_jiin['jiin_name'].'%');
        }
        $jiins = $query->paginate(10);

        // 該当件数表示
        $jiinCount = $jiins->total();
        
        return view('admin.jiin.index', compact('jiins', 'jiinCount'))
            ->with('cond_jiin', $cond_jiin);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jiin.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JiinRequest $request)
    {
        try {
            $jiin            = new Jiin();
            $jiin->jiin_name = $request->input('jiin_name');
            $jiin->memo      = $request->input('memo');
            $jiin->save();

            DB::commit();
            session()->flash('success', '寺院情報を登録しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '寺院情報を登録できませんでした。');
        }
        return redirect()->route('admin.jiin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jiin = Jiin::find($id);

        return view('admin.jiin.show', compact('jiin'));
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
        $jiin = Jiin::query()->find($id);
        return view('admin.jiin.edit', compact('jiin'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JiinRequest $request, $id)
    {
        try {
            $jiin               = Jiin::query()->findOrFail($id);
            $jiin->jiin_name    = $request->input('jiin_name');
            $jiin->memo         = $request->input('memo');
            $jiin->save();

            DB::commit();
            session()->flash('success', '寺院情報を更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '寺院情報を更新できませんでした。');
        }
        return redirect()->route('admin.jiin.index');
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
        try {
            Jiin::query()->findOrFail($id)->delete();
            DB::commit();
            session()->flash('success', '寺院情報を削除しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '寺院情報を削除できませんでした。');
        }
        return redirect()->route('admin.jiin.index');
        //
    }
}
