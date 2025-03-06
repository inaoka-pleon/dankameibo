<?php

namespace App\Http\Controllers;

use App\Http\Requests\TempleRequest;
use App\Models\Code;
use App\Models\Member;
use App\Models\Temple;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TempleController extends Controller
{
    /**
     * 一覧表示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'temple_search') === 0) {
            $put_flg = true;
        }

        // 宗務所のデータ取得
        $templeoffices = Code::query()
                                ->where('key1', '=', 'TEMPLEOFFICE')
                                ->get();
        
        // 一覧検索パラメータ取得
        $cond_temple = CommonUtility::GetQueryParameter($request, 'cond_temple', $put_flg);

        $query = Temple::query()
                        ->join('members', 'temples.id', '=', 'members.temple_id')
                        ->select('temples.id',
                                'temples.templeoffice',
                                'temples.parish',
                                'temples.templename',
                                'temples.templenamekana',
                                'members.name',
                                'members.tel')
                        ->where('chiefpriest_flg', '=', 1);

        if(!empty($cond_temple['templeoffice'])) {
            $query->where('templeoffice', '=', $cond_temple['templeoffice']);
        }
        if(!empty($cond_temple['parish'])) {
            $query->where('parish', '=', $cond_temple['parish']);
        }
        if(!empty($cond_temple['templename'])) {
            $query->where('templename', 'like', '%'.$cond_temple['templename'].'%');
        }
        if(!empty($cond_temple['templenamekana'])) {
            $query->where('templenamekana', 'like', '%'.$cond_temple['templenamekana'].'%');
        }
        if(!empty($cond_temple['name'])) {
            $query->where('name', 'like', '%'.$cond_temple['name'].'%');
        }
        if(!empty($cond_temple['tel'])) {
            $query->where('tel', 'like', '%'.$cond_temple['tel'].'%');
        }
        $temples = $query->paginate(10);

        return view('temples.index', compact('temples', 'templeoffices'))
            ->with('cond_temple', $cond_temple);
        //
    }
    /**
     * 新規作成
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $codes = DB::table('codes')->get();

        $templeoffices = Code::query()
                             ->where('key1', '=', 'TEMPLEOFFICE')
                             ->get();
        
        $titles = Code::query()
                      ->where('key1', '=', 'TITLE')
                      ->get();

        $subtitles = Code::query()
                         ->where('key1', '=', 'SUBTITLE')
                         ->get();
                        
        $teachers = Code::query()
                        ->where('key1', '=', 'TEACHER')
                        ->get();

        $jikakus = Code::query()
                        ->where('key1', '=', 'JIKAKU')
                        ->get();
        
        $qualifications = Code::query()
                              ->where('key1', '=', 'QUALIFICATION')
                              ->get();

        $nyuukai_names = Code::query()
                             ->where('key1', '=', 'NYUUKAINAME')
                             ->get();

        return view('temples.create', compact('codes', 'templeoffices', 'titles', 'subtitles', 'teachers', 'jikakus', 'qualifications', 'nyuukai_names'));
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TempleRequest $request)
    {
        DB::beginTransaction();

        try{
            $temple = new Temple;

            $temple->templeoffice = $request->input('templeoffice');
            $temple->parish = $request->input('parish');
            $temple->no = $request->input('no');
            $temple->templename = $request->input('templename');
            $temple->templenamekana = $request->input('templenamekana');
            $temple->mountainname = $request->input('mountainname');
            $temple->jikaku = $request->input('jikaku');
            $temple->buddhistfederation = $request->input('buddhistfederation');
            $temple->memo = $request->input('memo');

            $temple->save();

            $member = new Member;
            $member->qualification = $request->input('qualification');
            $member->name = $request->input('name');
            $member->namekana = $request->input('namekana');
            $member->title = $request->input('title');
            $member->subtitle = $request->input('subtitle');
            $member->postcode = $request->input('postcode');
            $member->address1 = $request->input('address1');
            $member->address2 = $request->input('address2');
            $member->tel = $request->input('tel');
            $member->fax = $request->input('fax');
            $member->letterdivision = $request->input('letterdivision');
            $member->newyearscarddivision = $request->input('newyearscarddivision');
            $member->summergreetingdivision = $request->input('summergreetingdivision');
            $member->teacher = $request->input('teacher');

            // 入会名の配列をカンマ区切りの文字列に変換
            $nyuukai_names = $request->input('nyuukai_name');
            if (is_array($nyuukai_names)) {
                $member->nyuukai_name = implode(',', $nyuukai_names);
            } else {
                $member->nyuukai_name = $nyuukai_names;
            }
            // 寺院テーブルの自動採番されたIDをとる
            $member->temple_id = $temple->id;
            $member->chiefpriest_flg = 1;

            $member->save();
            // 正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '寺院情報を登録しました。');
        } catch(Exception $ex) {
            // 正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '寺院情報を登録できませんでした。');
        };
        // リダイレクト
        return redirect()->route('temple.index');
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
        $members = DB::table('members')->get();

        $temple = DB::table('temples')
                    ->join('members', 'temples.id', '=', 'members.temple_id')
                    ->select('temples.id',
                             'temples.templeoffice',
                             'temples.parish',
                             'temples.no',
                             'temples.templename',
                             'temples.templenamekana',
                             'members.qualification',
                             'members.name',
                             'members.namekana',
                             'members.title',
                             'members.subtitle',
                             'members.postcode',
                             'members.address1',
                             'members.address2',
                             'members.tel',
                             'members.fax',
                             'members.letterdivision',
                             'members.newyearscarddivision',
                             'members.summergreetingdivision',
                             'members.teacher',
                             'temples.mountainname',
                             'temples.jikaku',
                             'temples.buddhistfederation',
                             'members.nyuukai_name',
                             'temples.memo')
                    ->where('temples.id', '=', $id)
                    ->where('members.chiefpriest_flg', '=', 1)
                    ->first();

        $informations = Member::query()
                              ->where('temple_id', '=', $id)
                              ->where('chiefpriest_flg', '=', 0)
                              ->get();

        return view('temples.show', compact('temple', 'members', 'informations'))
                ->with('temple_id', $id);
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
        $codes = DB::table('codes')->get();

        $templeoffices = Code::query()
                             ->where('key1', '=', 'TEMPLEOFFICE')
                             ->get();
        
        $titles = Code::query()
                      ->where('key1', '=', 'TITLE')
                      ->get();

        $subtitles = Code::query()
                         ->where('key1', '=', 'SUBTITLE')
                         ->get();
                        
        $teachers = Code::query()
                        ->where('key1', '=', 'TEACHER')
                        ->get();

        $jikakus = Code::query()
                        ->where('key1', '=', 'JIKAKU')
                        ->get();
        
        $qualifications = Code::query()
                              ->where('key1', '=', 'QUALIFICATION')
                              ->get();

        $nyuukai_names = Code::query()
                              ->where('key1', '=', 'NYUUKAINAME')
                              ->get();

        $temple = Temple::query()->find($id);
        $member = Member::query()
                         ->where('temple_id', '=', $id)
                         ->first();

        $selected_nyuukai_names = explode(',', $member->nyuukai_name);
        

        return view('temples.edit', compact('temple', 'member', 'codes', 'templeoffices', 'titles', 'subtitles', 'teachers', 'jikakus', 'qualifications', 'nyuukai_names', 'selected_nyuukai_names'));
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
    public function update(TempleRequest $request, $id)
    {
        DB::beginTransaction();

        try{
            $temple = Temple::find($id);
            $member = Member::query()->where('temple_id', '=', $id)->where('chiefpriest_flg', '=', 1)->first();
            $temple->templeoffice = $request->input('templeoffice');
            $temple->parish = $request->input('parish');
            $temple->no = $request->input('no');
            $temple->templename = $request->input('templename');
            $temple->templenamekana = $request->input('templenamekana');
            $temple->mountainname = $request->input('mountainname');
            $temple->jikaku = $request->input('jikaku');
            $temple->buddhistfederation = $request->input('buddhistfederation');
            $temple->memo = $request->input('memo');
            $member->qualification = $request->input('qualification');
            $member->name = $request->input('name');
            $member->namekana = $request->input('namekana');
            $member->title = $request->input('title');
            $member->subtitle = $request->input('subtitle');
            $member->postcode = $request->input('postcode');
            $member->address1 = $request->input('address1');
            $member->address2 = $request->input('address2');
            $member->tel = $request->input('tel');
            $member->fax = $request->input('fax');
            $member->letterdivision = $request->input('letterdivision');
            $member->newyearscarddivision = $request->input('newyearscarddivision');
            $member->summergreetingdivision = $request->input('summergreetingdivision');
            $member->teacher = $request->input('teacher');
            // 入会名の配列をカンマ区切りの文字列に変換
            $nyuukai_names = $request->input('nyuukai_name');
            if (is_array($nyuukai_names)) {
                $member->nyuukai_name = implode(',', $nyuukai_names);
            } else {
                $member->nyuukai_name = $nyuukai_names;
            }
            // 寺院テーブルの自動採番されたIDをとる
            $member->temple_id = $temple->id;

            $temple->save();
            $member->save();

            // 正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '寺院情報を変更しました。');
        } catch(Exception $ex) {
            // 正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '寺院情報を変更できませんでした。');
        };

        return redirect()->route('temple.index');
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
        try {
            $members  = Member::query()->where('temple_id', '=', $id)->delete();
            // if (!is_null($members)) {
            //     // エラーメッセージ
            //     return redirect()->back();
            // }

            //データ削除
            Temple::find($id)->delete();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '寺院情報を削除しました。');

        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '寺院情報を削除できませんでした。');
        };

         //リダイレクト（削除処理が終わったら'temple.index'に遷移）
        return redirect()->route('temple.index');
        //
    }

    /**
     *住職交代
     */
    public function chiefpriest_change(Request $request, $id)
    {
        DB::beginTransaction();

        try{
            $temple_id = $request->query('temple_id');

            $member = Member::query()->where('chiefpriest_flg', 1)->where('temple_id', '=', $temple_id)->first();
            if (!is_null($member)) {
                $member->chiefpriest_flg = 0;
                $member->save();
            }

            $information = Member::query()->findOrFail($id);
            $information->chiefpriest_flg = 1;
            $information->save();

            DB::commit();
            session()->flash('success', '住職交代をしました。');

        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '住職交代をできませんでした。');
        };

        return redirect()->route('temple.show', $temple_id);
    }
}
