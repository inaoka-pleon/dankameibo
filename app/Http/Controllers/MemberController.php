<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\Temple;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
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
        // 敬称のデータを取得
        $titles = DB::table('codes')
                ->where('key1', '=', 'TITLE')
                ->get();

        // 脇敬称のデータを取得
        $subtitles = DB::table('codes')
                    ->where('key1', '=', 'SUBTITLE')
                    ->get();

        // 師のデータを取得
        $teachers = DB::table('codes')
                  ->where('key1', '=', 'TEACHER')
                  ->get();

        // 資格のデータを取得
        $qualifications = DB::table('codes')
                        ->where('key1', '=', 'QUALIFICATION')
                        ->get();

        // 入会名のデータを取得
        $nyuukai_names = DB::table('codes')
                        ->where('key1', '=', 'NYUUKAINAME')
                        ->get();

        return view('members.create', compact('titles', 'subtitles', 'teachers', 'qualifications', 'nyuukai_names'))->with('temple_id', $id);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        $temple_id = $request->input('temple_id');

        DB::beginTransaction();

        try{
            $member = new member;
            $member->name = $request->input('name');
            $member->namekana = $request->input('namekana');
            $member->title = $request->input('title');
            $member->subtitle = $request->input('subtitle');
            $member->teacher = $request->input('teacher');
            $member->qualification = $request->input('qualification');
            $member->postcode = $request->input('postcode');
            $member->address1 = $request->input('address1');
            $member->address2 = $request->input('address2');
            $member->tel = $request->input('tel');
            $member->fax = $request->input('fax');
            $member->letterdivision = $request->input('letterdivision');
            $member->newyearscarddivision = $request->input('newyearscarddivision');
            $member->summergreetingdivision = $request->input('summergreetingdivision');
            $member->memo = $request->input('memo');

            // 入会名の配列をカンマ区切りの文字列に変換
            $nyuukai_names = $request->input('nyuukai_name');
            if (is_array($nyuukai_names)) {
                $member->nyuukai_name = implode(',', $nyuukai_names);
            } else {
                $member->nyuukai_name = $nyuukai_names;
            }
            $member->temple_id = $temple_id;
            $member->chiefpriest_flg = 0;

            $member->save();

            DB::commit();
            session()->flash('success', '寺院情報を登録しました。');

        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺院情報を登録できませんでした。');
        };

        return redirect()->route('temple.show', $temple_id);
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
        // 敬称のデータを取得
        $titles = DB::table('codes')
                ->where('key1', '=', 'TITLE')
                ->get();

        // 脇敬称のデータを取得
        $subtitles = DB::table('codes')
                    ->where('key1', '=', 'SUBTITLE')
                    ->get();

        // 師のデータを取得
        $teachers = DB::table('codes')
                  ->where('key1', '=', 'TEACHER')
                  ->get();

        // 資格のデータを取得
        $qualifications = DB::table('codes')
                        ->where('key1', '=', ' QUALIFICATION')
                        ->get();

        // テーブルを結合
        $member = DB::table('members')
                    ->join('temples', 'members.temple_id', '=', 'temples.id')
                    ->select('members.id',
                             'temples.id as temple_id',
                             'members.name',
                             'members.namekana',
                             'members.title',
                             'members.subtitle',
                             'members.teacher',
                             'members.qualification',
                             'members.nyuukai_name',
                             'members.postcode',
                             'members.address1',
                             'members.address2',
                             'members.tel',
                             'members.fax',
                             'members.letterdivision',
                             'members.newyearscarddivision',
                             'members.summergreetingdivision',
                             'members.nyuukai_name',
                             'members.memo')
                    ->where('members.id', '=', $id)
                    ->first();

        return view('members.show', compact('member', 'titles', 'subtitles', 'teachers', 'qualifications'));
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
        // 敬称のデータを取得
        $titles = DB::table('codes')
        ->where('key1', '=', 'TITLE')
        ->get();

        // 脇敬称のデータを取得
        $subtitles = DB::table('codes')
                    ->where('key1', '=', 'SUBTITLE')
                    ->get();

        // 師のデータを取得
        $teachers = DB::table('codes')
                  ->where('key1', '=', 'TEACHER')
                  ->get();

        // 資格のデータを取得
        $qualifications = DB::table('codes')
                        ->where('key1', '=', 'QUALIFICATION')
                        ->get();

        // 入会名のデータを取得
        $nyuukai_names = DB::table('codes')
                       ->where('key1', '=', 'NYUUKAINAME')
                       ->get();

        $member = Member::query()->find($id);

        $selected_nyuukai_names = explode(',', $member->nyuukai_name);

        return view('members.edit', compact('member', 'titles', 'subtitles', 'teachers', 'qualifications', 'nyuukai_names', 'selected_nyuukai_names'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MemberRequest $request, $id)
    {
        $temple_id = $request->input('temple_id');

        DB::beginTransaction();

        try{
            $member = Member::find($id);
            $member->name = $request->input('name');
            $member->namekana = $request->input('namekana');
            $member->title = $request->input('title');
            $member->subtitle = $request->input('subtitle');
            $member->teacher = $request->input('teacher');
            $member->qualification = $request->input('qualification');
            $member->postcode = $request->input('postcode');
            $member->address1 = $request->input('address1');
            $member->address2 = $request->input('address2');
            $member->tel = $request->input('tel');
            $member->fax = $request->input('fax');
            $member->letterdivision = $request->input('letterdivision');
            $member->newyearscarddivision = $request->input('newyearscarddivision');
            $member->summergreetingdivision = $request->input('summergreetingdivision');
            $member->memo = $request->input('memo');
            // 入会名の配列をカンマ区切りの文字列に変換
            $nyuukai_names = $request->input('nyuukai_name');
            if (is_array($nyuukai_names)) {
                $member->nyuukai_name = implode(',', $nyuukai_names);
            } else {
                $member->nyuukai_name = $nyuukai_names;
            }
            $member->temple_id = $temple_id;
            $member->chiefpriest_flg = 0;

            $member->save();

            DB::commit();
            session()->flash('success', '寺院情報を変更しました。');

        } catch(Exception $ex) {
            // 正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '寺院情報を変更できませんでした。');

        };
        //
        return redirect()->route('temple.show', $temple_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($temple_id, $id)
    {
        DB::beginTransaction();

        try{
            Member::find($id)->delete();

            DB::commit();
            session()->flash('success', '寺院情報を削除しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '寺院情報を削除できませんでした。');
        }

        return redirect()->route('temple.show', $temple_id);
        //
    }
}
