<?php

namespace App\Http\Controllers;

use App\Models\Danka;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaimyouCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $put_flg = false;
        if (strcmp($request->searchType, 'kaimyou_search') === 0) {
            $put_flg = true;
        }

        //一覧検索パラメータ取得
        $cond_kaimyoucheck = CommonUtility::GetQueryParameter($request, 'cond_kaimyoucheck', $put_flg);

        $query = Danka::query()
                    ->join('followers as chief', function($join) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                            ->where('chief.chiefmourner_flg', '=', 1);
                    })
                    ->leftJoin('followers as deceased', function($join) {
                        $join->on('dankas.id', '=', 'deceased.danka_id')
                            ->where('deceased.deceased_flg', '=', 1);
                    })
                    ->select('dankas.id',
                            'dankas.area',
                            'chief.name',
                            'chief.namekana',
                            'deceased.kaimyou',
                            'deceased.zokumyou',
                            'deceased.relationship')
                    ->where('chief.deceased_flg', '=', 0)
                    ->where('deceased.chiefmourner_flg', '=', 0)
                    ->where('dankas.jiin_id', '=', $userJiinId)
                    ->orderby('chief.namekana', 'asc');

        if(!empty($cond_kaimyoucheck['kaimyou'])) {
         $query->where('deceased.kaimyou', 'like', '%'.$cond_kaimyoucheck['kaimyou'].'%');
        }
        if(!empty($cond_kaimyoucheck['zokumyou'])) {
            $query->where('deceased.zokumyou', 'like', '%'.$cond_kaimyoucheck['zokumyou'].'%');
        }
        if(!empty($cond_kaimyoucheck['relationship'])) {
            $query->where('deceased.relationship', 'like', '%'.$cond_kaimyoucheck['relationship'].'%');
        }

        $kaimyouchecks = $query->paginate(10);

        // 該当件数表示
        $kaimyouCount = $kaimyouchecks->total();

        return view('kaimyouchecks.index', compact('kaimyouchecks', 'kaimyouCount'))
            ->with('cond_kaimyoucheck', $cond_kaimyoucheck);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
        //
    }
}
