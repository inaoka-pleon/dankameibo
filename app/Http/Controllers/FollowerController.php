<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowerRequest;
use App\Models\Code;
use App\Models\Danka;
use App\Models\Follower;
use Exception;
use Illuminate\Support\Facades\DB;

class FollowerController extends Controller
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
        //家族続柄のデータ取得
        $relationships = Code::query()
                            ->where('key1', '=', 'RELATIONSHIP')
                            ->get();

        //寺役職のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();
    
        //役職のデータ取得
        $occupations = Code::query()
                        ->where('key1', '=', 'OCCUPATION')
                        ->get();

        return view('followers.create', compact('relationships', 'positions', 'occupations'))->with('danka_id', $id);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FollowerRequest $request)
    {
        $danka_id = $request->input('danka_id');

        DB::beginTransaction();

        try{
            $follower = new Follower;
            $follower->name = $request->input('name');
            $follower->namekana = $request->input('namekana');
            $follower->relationship = $request->input('relationship');
            $follower->gender = $request->input('gender');
            $follower->birthdate = $request->input('birthdate');
            $follower->postcode = $request->input('postcode');
            $follower->address1 = $request->input('address1');
            $follower->address2 = $request->input('address2');
            $follower->tel = $request->input('tel');
            $follower->fax = $request->input('fax');
            $follower->position = $request->input('position');
            $follower->seizenkaimyou = $request->input('seizenkaimyou');
            $follower->occupation = $request->input('occupation');
            $follower->memo = $request->input('memo');
            $follower->danka_id = $danka_id;
            $follower->chiefmourner_flg = 0;
            $follower->deceased_flg = 0;

            $follower->save();

            DB::commit();
            session()->flash('success', '家族情報を登録しました。');

            } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '家族情報を登録できませんでした。');

            };

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
        //寺役職のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();
    
        //役職管理のデータ取得
        $occupations = Code::query()
                        ->where('key1', '=', 'OCCUPATION')
                        ->get();
    
        //テーブルを結合
        $follower =  DB::table('followers')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('followers.id',
                                 'dankas.id as danka_id',
                                 'followers.name',
                                 'followers.namekana',
                                 'followers.relationship',
                                 'followers.gender',
                                 'followers.birthdate',
                                 'followers.postcode',
                                 'followers.address1',
                                 'followers.address2',
                                 'followers.tel',
                                 'followers.fax',
                                 'followers.position',
                                 'followers.seizenkaimyou',
                                 'followers.occupation',
                                 'followers.memo')
                        ->where('followers.id', '=', $id)
                        ->first();

        // $follower = Follower::find($id);

        return view('followers.show', compact('follower', 'positions', 'occupations'));
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
        //家族続柄のデータ取得
        $relationships = Code::query()
                            ->where('key1', '=', 'RELATIONSHIP')
                            ->get();

        //寺役職管理のデータ取得
        $positions = Code::query()
                        ->where('key1', '=', 'POSITION')
                        ->get();

        //役職管理のデータ取得
        $occupations = Code::query()
                        ->where('key1', '=', 'OCCUPATION')
                        ->get();

        $follower = Follower::query()->find($id);

        return view('followers.edit', compact('follower', 'relationships', 'positions', 'occupations'));

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FollowerRequest $request, $id)
    {
        $danka_id = $request->input('danka_id');

        DB::beginTransaction();

        try{
            $follower = Follower::find($id);
            $follower->name = $request->input('name');
            $follower->namekana = $request->input('namekana');
            $follower->relationship = $request->input('relationship');
            $follower->birthdate = $request->input('birthdate');
            $follower->gender = $request->input('gender');
            $follower->position = $request->input('position');
            $follower->postcode = $request->input('postcode');
            $follower->address1 = $request->input('address1');
            $follower->address2 = $request->input('address2');
            $follower->tel = $request->input('tel');
            $follower->fax = $request->input('fax');
            $follower->occupation = $request->input('occupation');
            $follower->seizenkaimyou = $request->input('seizenkaimyou');
            $follower->memo = $request->input('memo');
            $follower->danka_id = $danka_id;
            $follower->chiefmourner_flg = 0;
            $follower->deceased_flg = 0;

            $follower->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '家族情報を変更しました。');

        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '家族情報を変更できませんでした。');

        };

        //リダイレクト
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
            Follower::find($id)->delete();

            DB::commit();
            session()->flash('success', '家族情報を削除しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '家族情報を削除できませんでした。');
        }

        return redirect()->route('danka.show', $danka_id);
        //
    }
}
