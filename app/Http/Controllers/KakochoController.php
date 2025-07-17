<?php

namespace App\Http\Controllers;

use App\Http\Requests\KakochoRequest;
use App\Models\Code;
use App\Models\Era;
use App\Models\Follower;
use App\Models\Kaiki;
use App\Models\Kakocho;
use App\Models\Nenkilist;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KakochoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $eras = Era::query()->orderBy('start_ymd', 'desc')->get();

        $relationships = Code::query()
                        ->where('key1', '=', 'RELATIONSHIP')
                        ->get();

        return view('kakochos.create', compact('eras', 'relationships'))->with('danka_id', $id);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KakochoRequest $request)
    {
        $danka_id = $request->input('danka_id');

        DB::beginTransaction();

        try{
            $kakocho = new Follower;
            $kakocho->kaimyou = $request->input('kaimyou');
            $kakocho->zokumyou = $request->input('zokumyou');
            $kakocho->zokumyoukana = $request->input('zokumyoukana');
            $kakocho->death_era = $request->input('death_era');
            $kakocho->death_year = $request->input('death_year');
            $kakocho->death_month = $request->input('death_month');
            $kakocho->death_day = $request->input('death_day');
            $kakocho->ageatdeath = $request->input('ageatdeath');
            $kakocho->kakocho_memo = $request->input('kakocho_memo');
            $kakocho->chiefmourner_flg = 0;
            $kakocho->deceased_flg = 1;
            $kakocho->danka_id = $danka_id;            
            
            if (!empty($request->input('death_era')) &&
                !empty($request->input('death_year')) &&
                !empty($request->input('death_month')) &&
                !empty($request->input('death_day'))) {
                $death_year = CommonUtility::JAtoADCalendarYearConv($request->input('death_era'), $request->input('death_year'));
                $deathanniversary = Carbon::create($death_year, $request->input('death_month'), $request->input('death_day'));
                $kakocho->deathanniversary = $deathanniversary;
            }
            
            if(!empty($request->input('ageatdeath'))) {
                $kakocho->ageatdeath = $request->input('ageatdeath');
            }

            if (Auth::guard('web')->check()) {
                $kakocho->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }

            $kakocho->save();
 
            $kaikis = Kaiki::query()
                    ->select('kaikis.id',
                            'kaikis.kaiki_kbn',
                            'kaikis.kaiki',
                            'kaikis.kaiki_name',
                            'kaikis.from_year_kbn',
                            'kaikis.from_month',
                            'kaikis.from_day',
                            'kaikis.to_year_kbn',
                            'kaikis.to_month',
                            'kaikis.to_day',
                            'kaikis.houyou_month',
                            'kaikis.houyou_day')
                    ->where(function($query) {
                        $query->where('kaikis.kaiki_kbn', '=', 0)
                            ->where('kaikis.target_flg', '=', 1);
                    })
                    ->orWhere('kaikis.kaiki_kbn', '!=', 0)
                    ->orderBy('kaikis.disp_order', 'asc')
                    ->get();

            foreach($kaikis as $kaiki) {
                $nenkilist = new Nenkilist;
                $nenkilist->kakocho_id = $kakocho->id;
                $nenkilist->kaiki_id = $kaiki->id;
                $nenkilist->jiin_id = $kakocho->jiin_id;
                if (Auth::guard('web')->check()) {
                    $nenkilist->jiin_id = Auth::guard('web')->user()->jiin_id;
                }
                $nenkilist->save();
            }
         
            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '過去帳情報を登録しました。');

            } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '過去帳情報を登録できませんでした。');
            };

        //リダイレクト
        return redirect()->route('danka.show', $danka_id);
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
        $kakocho = Follower::find($id);

        $death_era = Follower::query()
                    ->join('eras', 'followers.death_era', '=', 'eras.id')
                    ->where('followers.id', $kakocho->id)
                    ->select('followers.death_era',
                             'eras.id',
                             'eras.name')
                    ->first();

        $relationships = Code::query()
                        ->where('key1', '=', 'RELATIONSHIP')
                        ->get();

        return view('kakochos.show', compact('kakocho', 'death_era', 'relationships'));
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
        $kakocho = Follower::query()->find($id);
        $eras = Era::query()->orderBy('start_ymd', 'desc')->get();

        $relationships = Code::query()
                        ->where('key1', '=', 'RELATIONSHIP')
                        ->get(); 

        return view('kakochos.edit', compact('kakocho', 'eras', 'relationships'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KakochoRequest $request, $id)
    {
        $danka_id = $request->input('danka_id');

        DB::beginTransaction();

        try{
            $kakocho = Follower::find($id);
            $kakocho->kaimyou = $request->input('kaimyou');
            $kakocho->zokumyou = $request->input('zokumyou');
            $kakocho->zokumyoukana = $request->input('zokumyoukana');
            $kakocho->deathanniversary = $request->input('deathanniversary');
            $kakocho->death_era = $request->input('death_era');
            $kakocho->death_year = $request->input('death_year');
            $kakocho->death_month = $request->input('death_month');
            $kakocho->death_day = $request->input('death_day');
            $kakocho->ageatdeath = $request->input('ageatdeath');
            $kakocho->kakocho_memo = $request->input('kakocho_memo');
            $kakocho->chiefmourner_flg = 0;
            $kakocho->deceased_flg = 1;
            $kakocho->danka_id = $danka_id;           
            
            if (!empty($request->input('death_era')) &&
                !empty($request->input('death_year')) &&
                !empty($request->input('death_month')) &&
                !empty($request->input('death_day'))) {
                $death_year = CommonUtility::JAtoADCalendarYearConv($request->input('death_era'), $request->input('death_year'));
                $deathanniversary = Carbon::create($death_year, $request->input('death_month'), $request->input('death_day'));
                $kakocho->deathanniversary = $deathanniversary;
            }
            
            if(!empty($request->input('ageatdeath'))) {
                $kakocho->ageatdeath = $request->input('ageatdeath');
        }

        $kakocho->save();

        $kaikis = Kaiki::query()
                ->select('kaikis.id',
                        'kaikis.kaiki_kbn',
                        'kaikis.kaiki',
                        'kaikis.kaiki_name',
                        'kaikis.from_year_kbn',
                        'kaikis.from_month',
                        'kaikis.from_day',
                        'kaikis.to_year_kbn',
                        'kaikis.to_month',
                        'kaikis.to_day',
                        'kaikis.houyou_month',
                        'kaikis.houyou_day')
                ->where(function($query) {
                    $query->where('kaikis.kaiki_kbn', '=', 0)
                        ->where('kaikis.target_flg', '=', 1);
                })
                ->orWhere('kaikis.kaiki_kbn', '!=', 0)
                ->orderBy('kaikis.disp_order', 'asc')
                ->get();

                $nenkilists = Nenkilist::query()
                            ->get();


        foreach ($kaikis as $kaiki) {
            $nenkilist = nenkilist::where('kakocho_id', $kakocho->id)
                                  ->where('kaiki_id', $kaiki->id)
                                  ->first();

            if(!$nenkilist) {
                $nenkilist = new Nenkilist;
            }

            $nenkilist->kakocho_id = $kakocho->id;
            $nenkilist->kaiki_id = $kaiki->id;

            $nenkilist->save();

        }

        //正常に登録出来たらコミット
        DB::commit();
        session()->flash('success', '過去帳情報を変更しました。');

        } catch(Exception $ex) {
        //正常に終了しなかったらロールバック
        DB::rollBack();
        session()->flash('error', '過去帳情報を変更できませんでした。');

        };

    return redirect()->route('danka.show', $danka_id);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($danka_id, $id)
    {
        DB::beginTransaction();

        try{
            //該当のレコードを探してdeleteメソッドを呼び出す
            Follower::find($id)->delete();

            DB::commit();
            session()->flash('success', '過去帳情報を削除しました。');
        
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '過去帳情報を削除できませんでした。');
        }

        //リダイレクト
        return redirect()->route('danka.show', $danka_id);
        //
    }
}
