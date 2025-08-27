<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EraRequest;
use App\Models\Era;
use App\Services\EraData;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eras = null;
        try {
            $eras = Era::query()->orderBy('start_ymd', 'desc')->paginate(10);
        } catch (Exception $e) {
            Log::error($e);
            session()->flash('toastr', Config::get('toastr.search.era.error'));
        }
        return view('admin.era.index', compact('eras'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.era.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EraRequest $request)
    {
        DB::beginTransaction();

        try {
            EraData::Regist($request);
            DB::commit();
            session()->flash('success', '元号情報を登録しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '元号情報を登録できませんでした。');
        };
        return redirect()->route('admin.era.index');
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
        $era = Era::query()->find($id);

        return view('admin.era.edit', compact('era'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EraRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            EraData::Regist($request, $id);
            DB::commit();
            session()->flash('success', '元号情報を変更しました。');
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            session()->flash('error', '元号情報を変更できませんでした。');
        }
        return redirect()->route('admin.era.index');
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
            Era::query()->find($id)->delete();
            DB::commit();
            session()->flash('success', '元号情報を削除しました。');

        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '元号情報を削除できませんでした。');
        };
        return redirect()->route('admin.era.index');
        //
    }
}
