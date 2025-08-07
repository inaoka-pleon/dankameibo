<?php

namespace App\Http\Controllers;

use App\Http\Requests\DankaRequest;
use App\Http\Requests\FollowerRequest;
use App\Models\Code;
use Exception;
use Illuminate\Http\Request;
use App\Models\Danka;
use App\Models\Follower;
use Illuminate\Support\Facades\DB;
use App\Services\CommonUtility;
use Illuminate\Support\Facades\Auth;

class DankaController extends Controller
{
    /**
     * 一覧表示
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
        if (strcmp($request->searchType, 'danka_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();

        //寺役職管理のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();

        //一覧検索パラメータ取得
        $cond_danka = CommonUtility::GetQueryParameter($request, 'cond_danka', $put_flg);

        $query = Danka::query()
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->select('dankas.id', 
                            'dankas.area',
                            'followers.name',
                            'followers.namekana',
                            'followers.postcode',
                            'followers.address1',
                            'followers.address2',
                            'followers.tel',
                            'followers.position')
                    ->where('chiefmourner_flg', '=', 1)
                    ->where('dankas.jiin_id', '=', $userJiinId)
                    ->orderBy('dankas.area', 'asc')
                    ->orderBy('followers.namekana', 'asc');

        if(!empty($cond_danka['area'])) {
         $query->where('area', '=', $cond_danka['area']);
        } 
        if(!empty($cond_danka['name'])) {
         $query->where('name', 'like', '%'.$cond_danka['name'].'%');
        }
        if (!empty($cond_danka['namekana'])) {
         $query->where('namekana', 'like', '%'.$cond_danka['namekana'].'%');
        }
        if (isset($cond_danka['tel']) && $cond_danka['tel'] !== '') {
            $query->where('tel', 'like', '%' . $cond_danka['tel'] . '%');
        }
        if(!empty($cond_danka['position'])) {
            $query->where('position', '=', $cond_danka['position']);
        }

        $dankas = $query->paginate(10);

        // 該当件数表示
        $dankaCount = $dankas->total();

        return view('dankas.index', compact('dankas', 'areas', 'positions', 'dankaCount'))
            ->with('cond_danka', $cond_danka);
        //

    }

    /**
     * 新規登録
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        //地区名のデータ取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();

        //寺役職のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();
        
        //檀家区分のデータ取得
        $dankadivisions = Code::query()
                            ->where('key1', '=', 'DANKADIVISION')
                            ->get();

        //位牌区分のデータ取得
        $mortuarytablets = Code::query()
                                ->where('key1', '=', 'MORTUARYTABLET')
                                ->get();

        //職業のデータ取得
        $occupations = Code::query()
                            ->where('key1', '=', 'OCCUPATION')
                            ->get();
                
        return view('dankas.create', compact('areas', 'positions', 'dankadivisions', 'mortuarytablets', 'occupations'));
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DankaRequest $dankaRequest, FollowerRequest $followerRequest)
    {

        DB::beginTransaction();

        try{
            //モデルをインスタンス化
            $danka = new Danka;

            //モデル->カラム名=値で、データを割り当てる
            $danka->area = $dankaRequest->input('area');
            $danka->dankadivision = $dankaRequest->input('dankadivision');
            $danka->mortuarytablet = $dankaRequest->input('mortuarytablet');
            $danka->gozikai = $dankaRequest->input('gozikai');
            $danka->membershipfee = $dankaRequest->input('membershipfee');
            $danka->report = $dankaRequest->input('report');
            $danka->tanagyou = $dankaRequest->input('tanagyou');
            $danka->haruhigan = $dankaRequest->input('haruhigan');
            $danka->akihigan = $dankaRequest->input('akihigan');
            $danka->hanamatsuri = $dankaRequest->input('hanamatsuri');
            $danka->postcard = $dankaRequest->input('postcard');
            $danka->memo = $dankaRequest->input('memo');

            if (Auth::guard('web')->check()) {
                $danka->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }

            //データベースに保存
            $danka->save();

            $follower = new Follower;
            $follower->gender = $followerRequest->input('gender');
            $follower->name = $followerRequest->input('name');
            $follower->namekana = $followerRequest->input('namekana');
            $follower->birthdate = $followerRequest->input('birthdate');
            $follower->postcode = $followerRequest->input('postcode');
            $follower->address1 = $followerRequest->input('address1');
            $follower->address2 = $followerRequest->input('address2');
            $follower->tel = $followerRequest->input('tel');
            $follower->fax = $followerRequest->input('fax');
            $follower->position = $followerRequest->input('position');
            $follower->seizenkaimyou = $followerRequest->input('seizenkaimyou');
            $follower->occupation = $followerRequest->input('occupation');
            //檀家テーブルの採番されたIDをとる
            $follower->danka_id = $danka->id;
            $follower->jiin_id = $danka->jiin_id;
            if (Auth::guard('web')->check()) {
                $follower->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $follower->chiefmourner_flg = 1;

            //データベースに保存
            $follower->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '檀信徒情報を登録しました。');
            
        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '檀信徒情報を登録できませんでした。');
        };

        //リダイレクト
        return redirect()->route('danka.index');
        //
    }

    /**
     * 詳細表示
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        //地区名のデータ取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();

        //寺役職のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();
        
        //檀家区分のデータ取得
        $dankadivisions = Code::query()
                            ->where('key1', '=', 'DANKADIVISION')
                            ->get();

        //位牌区分のデータ取得
        $mortuarytablets = Code::query()
                            ->where('key1', '=', 'MORTUARYTABLET')
                            ->get();
        
        //職業のデータ取得
        $occupations = Code::query()
                        ->where('key1', '=', 'OCCUPATION')
                        ->get();

        //テーブルを結合
        //selectで必要な項目を指定
        $danka  = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->select('dankas.id', 
                             'dankas.area',
                             'dankas.dankadivision',
                             'dankas.mortuarytablet',
                             'followers.occupation',
                             'followers.position',
                             'followers.id as follower_id',
                             'followers.name',
                             'followers.namekana',
                             'followers.gender',
                             'followers.birthdate',
                             'followers.postcode',
                             'followers.address1',
                             'followers.address2',
                             'followers.tel',
                             'followers.fax',
                             'followers.seizenkaimyou',
                             'dankas.gozikai',
                             'dankas.membershipfee',
                             'dankas.report',
                             'dankas.tanagyou',
                             'dankas.haruhigan',
                             'dankas.akihigan',
                             'dankas.hanamatsuri',
                             'dankas.postcard',
                             'dankas.memo')
                    ->where('dankas.id', '=', $id)
                    ->where('dankas.jiin_id', '=', $userJiinId)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->first();

//        $danka = Danka::find($id);

        //施主のデータを取得するために施主フラグが1のものをとってくる
        //同じ画面で他の情報をとってくるときは名前を変えないとエラーが出るから今回はfollowerとfamily
/*
        $follower = Follower::query()
                             ->where('danka_id', '=', $id)
                             ->where('chiefmourner_flg', '=', 1)
                             ->first();
*/

        //施主以外の家族情報を取得するために施主フラグが0のものをとってくる
        //get()はヒットした全件をとってこれて、first()はヒットした一件をとってこれる
        $families = Follower::query()
                             ->where('danka_id', '=', $id)
                             ->where('followers.jiin_id', '=', $userJiinId)
                             ->where('chiefmourner_flg', '=', 0)
                             ->where('deceased_flg', '=', 0)
                             ->orderBy('namekana', 'asc')
                             ->paginate(10, ['*'], 'family_page');

        $kakochos = Follower::query()
                             ->leftJoin('eras', 'followers.death_era', '=', 'eras.id')
                             ->select('followers.id',
                                      'followers.name',
                                      'followers.namekana',
                                      'followers.kaimyou',
                                      'followers.zokumyou',
                                      'followers.deathanniversary',
                                      'eras.name as death_era_name',
                                      'followers.death_year',
                                      'followers.death_month',
                                      'followers.death_day',
                                      'followers.ageatdeath',
                                      'followers.danka_id',
                                      'followers.chiefmourner_flg',
                                      'followers.deceased_flg')
                             ->where('danka_id', '=', $id)
                             ->where('followers.jiin_id', '=', $userJiinId)
                             ->where('chiefmourner_flg', '=', 0)
                             ->where('deceased_flg', '=', 1)
                             ->orderby('deathanniversary', 'desc')
                             ->paginate(10, ['*'], 'kakocho_page');

        return view('dankas.show', compact('danka', 'families', 'kakochos', 'areas', 'positions', 'dankadivisions', 'mortuarytablets', 'occupations'))
                ->with('danka_id', $id);
        //
    }

    /**
     * 編集
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        //地区名のデータ取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();

        //寺役職のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();
        
        //檀家区分のデータ取得
        $dankadivisions = Code::query()
                            ->where('key1', '=', 'DANKADIVISION')
                            ->get();

        //位牌区分のデータ取得
        $mortuarytablets = Code::query()
                            ->where('key1', '=', 'MORTUARYTABLET')
                            ->get();
        
        //役職のデータ取得
        $occupations = Code::query()
                        ->where('key1', '=', 'OCCUPATION')
                        ->get();

        $danka = Danka::query()->find($id);
        $follower = Follower::query()
                    ->where('danka_id', '=', $id)
                    ->where('chiefmourner_flg', '=', 1)
                    ->first();

        return view('dankas.edit', compact('danka', 'follower', 'areas', 'positions', 'dankadivisions', 'mortuarytablets', 'occupations'));

        //
    }

    /**
     * 更新
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DankaRequest $dankaRequest, FollowerRequest $followerRequest, $id)
    {
        DB::beginTransaction();

        try{
            $danka = Danka::find($id);
            $follower = Follower::query()->where('danka_id', '=', $id)->where('chiefmourner_flg', '=', 1)->first();
            $danka->area = $dankaRequest->input('area');
            $follower->gender = $followerRequest->input('gender');
            $follower->name = $followerRequest->input('name');
            $follower->namekana = $followerRequest->input('namekana');
            $follower->birthdate = $followerRequest->input('birthdate');
            $follower->postcode = $followerRequest->input('postcode');
            $follower->address1 = $followerRequest->input('address1');
            $follower->address2 = $followerRequest->input('address2');
            $follower->tel = $followerRequest->input('tel');
            $follower->fax = $followerRequest->input('fax');
            $follower->position = $followerRequest->input('position');
            $danka->dankadivision = $dankaRequest->input('dankadivision');
            $danka->mortuarytablet = $dankaRequest->input('mortuarytablet');
            $follower->seizenkaimyou = $followerRequest->input('seizenkaimyou');
            $follower->occupation = $followerRequest->input('occupation');
            $danka->gozikai = $dankaRequest->input('gozikai');
            $danka->membershipfee = $dankaRequest->input('membershipfee');
            $danka->report = $dankaRequest->input('report');
            $danka->tanagyou = $dankaRequest->input('tanagyou');
            $danka->haruhigan = $dankaRequest->input('haruhigan');
            $danka->akihigan = $dankaRequest->input('akihigan');
            $danka->hanamatsuri = $dankaRequest->input('hanamatsuri');
            $danka->postcard = $dankaRequest->input('postcard');
            $danka->memo = $dankaRequest->input('memo');
            $follower->danka_id = $danka->id;

            if (Auth::guard('web')->check()) {
                $follower->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $danka->save();
            $follower->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '檀信徒情報を変更しました。');
        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '檀信徒情報を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('danka.index');
        //
    }

    /**
     * 削除
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        DB::beginTransaction();

        try {
            $danka = Danka::findOrFail($id);

            $followers  = Follower::query()
                            ->where('danka_id', '=', $id)
                            ->where('jiin_id', '=', $userJiinId)
                            ->delete();

            //データ削除
            $danka->delete();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '檀信徒情報を削除しました。');

        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '檀信徒情報を削除できませんでした。');
        };

         //リダイレクト（削除処理が終わったら'danka.index'に遷移）
        return redirect()->route('danka.index');

        //
    }

    /**
     * 施主交代
     */
    public function chiefmourner_change(Request $request, $id)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        DB::beginTransaction();

        try {
            $danka_id   = $request->query('danka_id');

            // データベースの更新（施主＝０に更新）
            // followersから施主フラグ＝１のデータを検索
            $follower = Follower::query()->where('chiefmourner_flg', 1)->where('danka_id', '=', $danka_id)->first();
            if (!is_null($follower)) {
                $follower->chiefmourner_flg = 0;
                $follower->save();
            }

            // データベースの更新（施主＝１に更新）
            $family = Follower::query()->findOrFail($id);
            $family->chiefmourner_flg = 1;
            $family->save();

            DB::commit();
            session()->flash('success', '施主交代しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '施主交代できませんでした。');
        }

        return redirect()->route('danka.show', $danka_id);
    }
}
