<?php

namespace App\Http\Controllers;

use App\Http\Requests\TempleMasterRequest;
use App\Models\TempleMaster;
use Exception;
use Illuminate\Support\Facades\DB;

class TempleMasterController extends Controller
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
    public function create()
    {
        return view('templemasters.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TempleMasterRequest $request)
    {
        DB::beginTransaction();

        try {
            //モデルのインスタンス化
            $templemaster = new TempleMaster();

            //モデル->カラム名 = 値で、データを割り当てる
            $templemaster->mountainname = $request->input('mountainname');
            $templemaster->templename = $request->input('templename');
            $templemaster->jyushokuname = $request->input('jyushokuname');
            $templemaster->postcode = $request->input('postcode');
            $templemaster->address1 = $request->input('address1');
            $templemaster->address2 = $request->input('address2');
            $templemaster->tel = $request->input('tel');
            $templemaster->fax = $request->input('fax');

            //データベースに保存
            $templemaster->save();

            DB::commit();
            session()->flash('success', '自寺院マスタを登録しました。');

        }catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '自寺院マスタを登録できませんでした。');
        }
        
        //リダイレクト
        return redirect()->route('templemaster.createOrEdit');
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
        $templemaster = TempleMaster::findOrFail($id);
        return view('templemasters.edit', compact('templemaster'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TempleMasterRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            //モデルのインスタンス化
            $templemaster = TempleMaster::findOrFail($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $templemaster->mountainname = $request->input('mountainname');
            $templemaster->templename = $request->input('templename');
            $templemaster->jyushokuname = $request->input('jyushokuname');
            $templemaster->postcode = $request->input('postcode');
            $templemaster->address1 = $request->input('address1');
            $templemaster->address2 = $request->input('address2');
            $templemaster->tel = $request->input('tel');
            $templemaster->fax = $request->input('fax');

            //データベースに保存
            $templemaster->save();

            DB::commit();
            session()->flash('success', '自寺院マスタを変更しました。');

        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '自寺院マスタを変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('templemaster.createOrEdit');
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
    public function createOrEdit()
    {
        $templemaster = TempleMaster::first(); // ここでデータの存在を確認

        if ($templemaster) {
            return view('templemasters.edit', compact('templemaster'));
        } else {
            return view('templemasters.create');
        }
    }
}
