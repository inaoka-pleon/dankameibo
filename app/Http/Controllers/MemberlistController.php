<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Temple;
use App\Services\CommonUtility;
use Illuminate\Http\Request;

class MemberlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if(strcmp($request->searchType, 'templelist_search') === 0) {
            $put_flg = true;
        }

        // 宗務所のデータを取得
        $templeoffices = Code::query()
                            ->where('key1', '=', 'TEMPLEOFFICE')
                            ->get();

        // 入会名のデータを取得
        $nyuukai_names = Code::query()
                            ->where('key1', '=', 'NYUUKAINAME')
                            ->get();
        
        // 一覧検索パラメータ取得
        $cond_memberlist = CommonUtility::GetQueryParameter($request, 'cond_memberlist', $put_flg);

        $query = Temple::query()
                        ->join('members', 'temples.id', '=', 'members.temple_id')
                        ->select('temples.id',
                                    'temples.templeoffice',
                                    'temples.parish',
                                    'members.nyuukai_name',
                                    'temples.no',
                                    'members.postcode',
                                    'temples.templename',
                                    'members.qualification',
                                    'members.name',
                                    'members.tel',
                                    'members.address1',
                                    'members.address2')
                        ->whereNotNull('members.nyuukai_name');

        if(!empty($cond_memberlist['templeoffice'])){
            $query->where('templeoffice', '=', $cond_memberlist['templeoffice']);
        }
        if(!empty($cond_memberlist['parish'])) {
            $query->where('parish', '=', $cond_memberlist['parish']);
        }
        if(!empty($cond_memberlist['nyuukai_name'])) {
            $query->where('nyuukai_name', 'like', '%'.$cond_memberlist['nyuukai_name'].'%');
        }

        $memberlists = $query->paginate(10);

        return view('memberlists.index', compact('memberlists', 'templeoffices', 'nyuukai_names'))
            ->with('cond_memberlist', $cond_memberlist);
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
