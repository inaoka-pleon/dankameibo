<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Danka;
use App\Models\Era;
use App\Models\GozikaikaihiList;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class GozikaikaihiListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if(strcmp($request->searchType, 'gozikaikaihi_search') === 0) {
            $put_flg = true;
        }

        //元号のデータ取得
        $eras = Era::query()
                    ->select('eras.id',
                             'eras.name')
                    ->get();   

        // 現在の日付を取得し、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraId = $currentEra['era_id'] ?? '';
        $currentEraYear = $currentEra['era_year'] ?? '';

        $target_year = CommonUtility::GetQueryParameter($request, 'target_year', $put_flg);

        // 一覧検索パラメータ取得
        $cond_gozikaikaihi_list = CommonUtility::GetQueryParameter($request, 'cond_gozikaikaihi_list', $put_flg);

        // 初期表示時に現在年度を設定
        if (empty($target_year['era'])) {
            $target_year['era'] = $currentEraId;
        }
        if (empty($target_year['year'])) {
            $target_year['year'] = $currentEraYear;
        }

        $era = $target_year['era'] ?? null;
        $year = $target_year['year'] ?? null;
        // 和暦を西暦に変換
        $search_era = null;
        if($era && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era, $year);
        }

        $query = DB::table('dankas')
                ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                ->leftJoin('gozikaikaihi_lists', function($join) use ($search_era) {
                    $join->on('dankas.id', '=', 'gozikaikaihi_lists.danka_id')
                         ->whereRaw('YEAR(gozikaikaihi_lists.target_year) = ?', [$search_era]);
                    })
                ->select('dankas.id as danka_id',
                         'dankas.area',
                         'followers.name',
                         'followers.namekana',
                         'followers.tel',
                         'gozikaikaihi_lists.id',
                         'gozikaikaihi_lists.payment_date',
                         'gozikaikaihi_lists.payment_class',
                         'gozikaikaihi_lists.deposit_amount',
                         'gozikaikaihi_lists.memo',
                         'gozikaikaihi_lists.target_year')
                ->where('dankas.gozikai', '=', 1)
                ->where('followers.chiefmourner_flg', '=', 1)
                ->orderBy('followers.name');


        if(!empty($cond_gozikaikaihi_list['name'])) {
            $query->where('name', 'like', '%'.$cond_gozikaikaihi_list['name'].'%');
        }
        if (!empty($cond_gozikaikaihi_list['namekana'])) {
            $query->where('namekana', 'like', '%'.$cond_gozikaikaihi_list['namekana'].'%');
        }
        if (!empty($cond_gozikaikaihi_list['tel'])) {
            $query->where('tel', 'like', '%'.$cond_gozikaikaihi_list['tel'].'%');
       }
           
        $gozikaikaihilists = $query->paginate(10);

        foreach ($gozikaikaihilists as $item) {
            // 西暦を和暦に変換
            if(!is_null($item->payment_date)) {
                $target_date = CommonUtility::ADtoJACalendarConv($item->payment_date);
                $item->era_name = $target_date['era_name'] ?? '';
                $item->era_year = $target_date['era_year'] ?? '';
                $item->month = $target_date['month'] ?? '';
                $item->day = $target_date['day'] ?? '';
            } else {
                $item->era_name = '';
                $item->era_year = '';
                $item->month = '';
                $item->day = '';
            }
        }

        // 検索条件をセッションに保存
        $request->session()->put('cond_gozikaikaihi_list', $cond_gozikaikaihi_list);
        $request->session()->put('target_year', $target_year);

        return view('gozikaikaihilists.index', compact('eras', 'gozikaikaihilists', 'currentEraId', 'currentEraYear'))
            ->with('cond_gozikaikaihi_list', $cond_gozikaikaihi_list)
            ->with('target_year', $target_year);
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
    public function edit($danka_id, $id = null)
    {
        // 護持会会費入金区分のデータ取得
        $gozikaikaihis = Code::query()
                            ->where('key1', '=', 'GOZIKAIKAIHI')
                            ->get();

        $target_year = session('target_year');
// dd($target_year);
        if ($id) {
            // 該当するデータを取得
            $gozikaikaihilist = GozikaikaihiList::where('danka_id', $danka_id)->where('id', $id)->first();
            if (!$gozikaikaihilist) {
                return redirect()->route('gozikaikaihilist.index')->with('error', 'データが見つかりませんでした。');
            }
            // dd($gozikaikaihilist);
            return view('gozikaikaihilists.edit', compact('gozikaikaihis','gozikaikaihilist'))->with('danka_id', $danka_id)->with('id', $id)->with('target_year', $target_year);
        } else {
            $gozikaikaihilist = new GozikaikaihiList();
            $gozikaikaihilist->danka_id = $danka_id;

            // dd($gozikaikaihilist);
            // 新規登録画面にリダイレクト
            return view('gozikaikaihilists.edit', compact('gozikaikaihilist', 'gozikaikaihis'))->with('danka_id', $danka_id)->with('target_year', $target_year);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $danka_id, $id = null)
    {
        DB::beginTransaction();

        try {
            if ($id) {
                // 該当するデータを取得
                $gozikaikaihilist = GozikaikaihiList::where('danka_id', $danka_id)->where('id', $id)->first();
                if (!$gozikaikaihilist) {
                    return redirect()->route('gozikaikaihilist.index')->with('error', 'データが見つかりませんでした。');
                }
                // dd($gozikaikaihilist);
            } else {
                // 新規作成
                $gozikaikaihilist = new GozikaikaihiList();
                $gozikaikaihilist->danka_id = $danka_id;
                // dd($gozikaikaihilist);
            }

            // データの更新または新規作成
            $gozikaikaihilist->payment_date = $request->input('payment_date');
            $gozikaikaihilist->payment_class = $request->input('payment_class');
            $gozikaikaihilist->deposit_amount = $request->input('deposit_amount');
            $gozikaikaihilist->memo = $request->input('memo');
            $gozikaikaihilist->target_year = $request->input('target_year');
            $gozikaikaihilist->save();

            DB::commit();
            session()->flash('success', '護持会会費情報を登録しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '護持会会費情報を登録できませんでした。');
        }

        return redirect()->route('gozikaikaihilist.index');
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

    public function print(Request $request)
    {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation='P', $unit='mm', $format='A4', $unicod=true, $encoding='UTF-8');
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');

        // セッションから検索条件を取得
        $cond_gozikaikaihi_list = $request->session()->get('cond_gozikaikaihi_list', []);
        $target_year = $request->session()->get('target_year', []);

        $era_id = $target_year['era'] ?? null;
        $year = $target_year['year'] ?? null;
        
        $era = Era::find($era_id);
        $era_name = $era ? $era->name : '';
        /*
        // 検索データを取得
        $put_flg = false;
        if(strcmp($request->searchType, 'gozikaikaihi_search') === 0) {
            $put_flg = true;
        }

        $cond_gozikaikaihi_list = CommonUtility::GetQueryParameter($request, 'cond_gozikaikaihi_list', $put_flg);

        $keys = ListData::GetGozikaikaihiListKey($cond_gozikaikaihi_list);

        $print_flg = false;

        foreach ($keys as $key) {
            $print_flg = true;

        $query = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->leftJoin('gozikaikaihi_lists', 'dankas.id', '=', 'gozikaikaihi_lists.danka_id')
                    ->where('dankas.gozikai', '=', 1)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderBy('followers.name');
                    
        if (!empty($cond_gozikaikaihi_list['era']) && !empty($cond_gozikaikaihi_list['year'])) {
            $era = $cond_gozikaikaihi_list['era'];
            $year = $cond_gozikaikaihi_list['year'];
        */

            // 和暦を西暦に変換
            $search_era = null;
            if ($era_id && $year) {
                $search_era = CommonUtility::JAtoADCalendarYearConv($era_id, $year);
            }

            $query = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->leftJoin('gozikaikaihi_lists', function($join) use ($search_era) {
                        $join->on('dankas.id', '=', 'gozikaikaihi_lists.danka_id')
                             ->whereRaw('YEAR(gozikaikaihi_lists.target_year) = ?', [$search_era]);
                    })
                    ->select('dankas.id as danka_id',
                             'dankas.area',
                             'followers.name',
                             'followers.namekana',
                             'followers.tel',
                             'gozikaikaihi_lists.id',
                             'gozikaikaihi_lists.payment_date', 
                             'gozikaikaihi_lists.payment_class',
                             'gozikaikaihi_lists.deposit_amount',
                             'gozikaikaihi_lists.memo',
                             'gozikaikaihi_lists.target_year')
                    ->where('dankas.gozikai', '=', 1)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderBy('followers.name');

            if (!empty($cond_gozikaikaihi_list['name'])) {
                $query->where('name', 'like', '%'.$cond_gozikaikaihi_list['name'].'%');
            }
            if (!empty($cond_gozikaikaihi_list['namekana'])) {
                $query->where('namekana', 'like', '%'.$cond_gozikaikaihi_list['namekana'].'%');
            }
            if (!empty($cond_gozikaikaihi_list['tel'])) {
                $query->where('tel', 'like', '%'.$cond_gozikaikaihi_list['tel'].'%');
            }

        $gozikaikaihilists = $query->get();

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Gozikaikaihilist.pdf';
        $templatePath = resource_path('template/Gozikaikaihilist.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);

        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null, null, null, null);

        $page = 1;

        $y = 0;
        $no = 0;
        $rowcnt = 0;
        foreach ($gozikaikaihilists as $gozikaikaihilist) {
            if ($rowcnt >= 30) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $page += 1;
                $pdf->setFont($f, '', 11);
                $pdf->Text(180, 274, $page);
                $y = 0;
            }

            $rowcnt += 1;
            // 年度
            $pdf->setFont($f, '', 20);
            $pdf->Text(45, 9, $era_name. ' '. $year. ' '.'年度');

            // No
            $pdf->setFont($f, '', 10.5);
            $pdf->Text(12.5, 35.5 + $y, $no += 1);

            // 氏名
            $pdf->setFont($f, '', 10.5);
            $pdf->Text(21.5, 35.5 + $y, $gozikaikaihilist->name);

            // 電話番号
            $pdf->setFont($f, '', 10);
            $pdf->Text(57.5, 35.5 + $y, $gozikaikaihilist->tel);

            // 入金日
            $pdf->setFont($f, '', 10.5);
            foreach ($gozikaikaihilists as $item) {
                // 和暦に変換
                if(!is_null($item->payment_date)) {
                    $target_date = CommonUtility::ADtoJACalendarConv($item->payment_date);
                    $item->era_name = $target_date['era_name'] ?? '';
                    $item->era_year = $target_date['era_year'] ?? '';
                    $item->month = $target_date['month'] ?? '';
                    $item->day = $target_date['day'] ?? '';
                } else {
                    $item->era_name = '';
                    $item->era_year = '';
                    $item->month = '';
                    $item->day = '';
                }
            }

            $paymentDateText = '';
            if (!empty($gozikaikaihilist->era_name)) {
                $paymentDateText .= $gozikaikaihilist->era_name;
            }
            if (!empty($gozikaikaihilist->era_year)) {
                $paymentDateText .= $gozikaikaihilist->era_year . '年';
            }
            if (!empty($gozikaikaihilist->month)) {
                $paymentDateText .= $gozikaikaihilist->month . '月';
            }
            if (!empty($gozikaikaihilist->day)) {
                $paymentDateText .= $gozikaikaihilist->day. '日';
            }

            $pdf->Text(87, 35.5 + $y, $paymentDateText);

            // 区分
            $pdf->setFont($f, '', 10.5);
            $pdf->Text(126, 35.5 + $y, $gozikaikaihilist->payment_class);

            // 入金額
            $pdf->setFont($f, '', 10.5);
            $pdf->Text(146.5, 35.5 + $y, $gozikaikaihilist->deposit_amount);

            // 備考
            $pdf->setFont($f, '', 8.5);
            $pdf->MultiCell(40, 10, $gozikaikaihilist->memo, 0, 'L', 0, 0, 159.5, 34 + $y);
            $y += 8.2;
        }

    // PDFをブラウザに出力
    $pdf->Output('output.pdf', 'I');
    $result = 0;
    }
}
