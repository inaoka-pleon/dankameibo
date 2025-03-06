<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\KaikiRequest;
use App\Models\Kaiki;
use App\Rules\KaikiDateCheck;
use App\Services\GenericData;
use App\Services\KaikiData;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KaikiController extends Controller
{
    /**
     * 一覧
     * @return View
     */
    public function index(): View
    {
        $kaikis = null;
        try {
            $kaikis = Kaiki::query()->orderBy('kaiki_kbn')->orderBy('kaiki')->get();
        } catch (Exception $e) {
            session()->flash('error', '回忌設定情報を取得できませんでした。');
        }
        return view('kaikis.index', compact('kaikis'));
    }

    /**
     * 新規作成
     * @return View
     */
    public function create(): View
    {
        $kaiki_kbns = GenericData::GetGeneric('KAIKI', 'KBN', null);
        $from_kbns  = GenericData::GetGeneric('KAIKI', 'KIKAN_KBN', null);
        $to_kbns    = GenericData::GetGeneric('KAIKI', 'KIKAN_KBN', null);
        return view('kaikis.create', compact('kaiki_kbns', 'from_kbns', 'to_kbns'));
    }

    /** 
     * 保存
     * @param KaikiRequest $request
     * @return RedirectResponse
     */
    public function store(KaikiRequest $request): RedirectResponse
    {
        // if($request->input('kaiki_kbn') === '2' ||
        //    $request->input('kaiki_kbn') === '3') {
        //    $request->validate([
        //         'from_year_kbn' => ['required', new KaikiDateCheck('from')],
        //         'to_year_kbn'   => ['required', new KaikiDateCheck('to'), new KaikiDateCheck('large_and_small')],
        //         'houyou_month'  => ['required', new KaikiDateCheck('houyou_date')],
        //    ])->setData($request->all());
        // }

        $user_name  = Auth::user()->name;
        try {
            DB::beginTransaction();
            KaikiData::Regist($request, $user_name);
            DB::commit();
            session()->flash('success', '回忌設定情報を登録できました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '回忌設定情報を登録できませんでした。');
        }
        return redirect()->route('kaiki.index');
    }

    /**
     * 編集
     * @param $id
     * @return View
     */
    public function edit($id): View
    {
        $kaiki_kbns = GenericData::GetGeneric('KAIKI', 'KBN', null);
        $from_kbns  = GenericData::GetGeneric('KAIKI', 'KIKAN_KBN', null);
        $to_kbns    = GenericData::GetGeneric('KAIKI', 'KIKAN_KBN', null);
        $kaiki      = Kaiki::query()->find($id);

        return view('kaikis.edit', compact('kaiki_kbns', 'from_kbns', 'to_kbns', 'kaiki'));
    }

    /**
     * 更新
     * @param KaikiRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(KaikiRequest $request, $id): RedirectResponse
    {
        if (!empty($request->input('from_year_kbn')) &&
            !empty($request->input('from_month')) &&
            !empty($request->input('from_day'))) {
            $from_ymd   = KaikiData::GetYmd($request->input('from_year_kbn'), $request->input('from_month'), $request->input('from_day'));
            if (is_null($from_ymd)) {
                session()->flash('error', '対象期間（自）の日付が正しくありません。');
                return redirect()->back();
            }
        }

        if (!empty($request->input('to_year_kbn')) &&
            !empty($request->input('to_month')) &&
            !empty($request->input('to_day'))) {
            $to_ymd = KaikiData::GetYmd($request->input('to_year_kbn'), $request->input('to_month'), $request->input('to_day'));
            if (is_null($to_ymd)) {
                session()->flash('error', '対象期間（至）の日付が正しくありません。');
                return redirect()->back();
            }
        }

        $user_name  = Auth::user()->name;
        try {
            DB::beginTransaction();
            KaikiData::Regist($request, $user_name, $id);
            DB::commit();
            session()->flash('success', '回忌設定情報を変更しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '回忌設定情報を変更できませんでした。');
        }
        return redirect()->route('kaiki.index');
    }

    /**
     * 削除
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            Kaiki::find($id)->delete();
            session()->flash('success', '回忌設定情報を削除しました。');
        } catch (Exception $e) {
            session()->flash('error', '回忌設定情報を削除できませんでした。');
        }
        return redirect()->route('kaiki.index');
    }
}