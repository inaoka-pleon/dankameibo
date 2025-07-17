<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralMasterRequest;
use App\Models\Code;
use App\Services\CodeData;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Queue\Middleware\ThrottlesExceptionsWithRedis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GeneralMastersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $customer_id = Auth::user()->customer_id;
        $id = Auth::user()->id;

        if (strcmp($request->searchType, 'generalmaster_search') === 0) {
            $put_flg = true;
        } else {
            $put_flg = false;
        }
        $k_sel_master = CommonUtility::GetQueryParameter($request, 'sel_master', $put_flg);

        $master_names = CodeData::GetGeneralMasterNameList('USER');

        $general_masters = CodeData::GetGeneralMaster($id, $k_sel_master, 10);
        return view('generalmasters.index', compact('master_names', 'general_masters'))
            ->with('k_sel_master', $k_sel_master);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $no = $request->query('no');
        $title = self::getTitle($no);
        return view('generalmasters.create')
            ->with('no', $no)
            ->with('title', $title);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GeneralMasterRequest $request)
    {
        try {
            $no = $request->input('no');
            $key = CodeData::GetGeneralMaterKey($no);
    
            $code = new Code();
            $code->key1 = $key;
            $code->key2 = Auth::user()->id;
            $code->key3 = CodeData::GetNextIndexNo($code->key1, $code->key2);
            $code->value1 = $request->input('value1');
            $code->value2 = $request->input('value2');

            if (Auth::guard('web')->check()) {
                $code->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $code->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '汎用マスタを登録しました。');

        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '汎用マスタを登録できませんでした。');
        };

        //汎用マスタ一覧へリダイレクト
        return redirect()->route('generalmaster.index');
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
    public function edit(Request $request, $id)
    {
        $general_master = Code::findOrFail($id);
        $title = $general_master->key1;
        
        return view('generalmasters.edit', compact('general_master'))
            ->with('title', $title);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GeneralMasterRequest $request, $id)
    {
        try {
            $code = Code::findOrFail($id);

            $code->value1 = $request->input('value1');
            $code->value2 = $request->input('value2');
            $code->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '汎用マスタを変更しました。');
        } catch (\Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '汎用マスタを変更できませんでした。');
            return redirect()->back()->withInput();
        }

            // 汎用マスタ一覧へリダイレクト
            return redirect()->route('generalmaster.index');
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

        /**
     * @param string $no
     * @return string
     */
    public function getTitle(string $no): string
    {
        if ($no === Config::get('literal.GeneralMaster.Area')) {
            $title = "地区名";
        } elseif ($no === Config::get('literal.GeneralMaster.DankaDivision')) {
            $title = "檀家区分";
        } elseif ($no === Config::get('literal.GeneralMaster.Position')) {
            $title = "寺役職";
        } elseif ($no === Config::get('literal.GeneralMaster.Relationship')) {
            $title = "家族続柄";
        } elseif ($no === Config::get('literal.GeneralMaster.Occupation')) {
            $title = "職業";
        } elseif ($no === Config::get('literal.GeneralMaster.Mortuarytablet')) {
            $title = "位牌区分";
        } elseif ($no === Config::get('literal.GeneralMaster.Manager')) {
            $title = "担当者";
        } elseif ($no === Config::get('literal.GeneralMaster.TempleOffice')) {
            $title = "宗務所";
        } elseif ($no === Config::get('literal.GeneralMaster.Title')) {
            $title = "敬称";
        } elseif ($no === Config::get('literal.GeneralMaster.SubTitle')) {
            $title = "脇敬称";
        } elseif ($no === Config::get('literal.GeneralMaster.Teacher')) {
            $title = "師";
        } elseif ($no === Config::get('literal.GeneralMaster.Jikaku')) {
            $title = "寺格";
        } elseif ($no === Config::get('literal.GeneralMaster.Qualification')) {
            $title = "資格";
        } elseif ($no === Config::get('literal.GeneralMaster.NyuukaiName')) {
            $title = "入会名";
        } elseif ($no === Config::get('literal.GeneralMaster.DankaKaihi')) {
            $title = "檀家会費";
        } elseif ($no === Config::get('literal.GeneralMaster.GozikaiKaihi')) {
            $title = "護持会会費";
        } else {
            $title = "";
        }
        return $title;
    }
}
