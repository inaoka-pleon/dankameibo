<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class KaihiListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if(strcmp($request->searchType, 'kaihilist_search') === 0) {
            $put_flg = true;
        }

        $eras = Era::query()
                    ->select('eras.id',
                             'eras.name')
                    ->get();

        // 現在の日付を取得し、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraId = $currentEra['era_id'] ?? '';
        $currentEraYear = $currentEra['era_year'] ?? '';

        // dd($currentDate);
        // dd($currentEra, $currentEraName, $currentEraYear);

        $target_year = CommonUtility::GetQueryParameter($request, 'target_year', $put_flg);

        // 初期表示時に現在年度を設定
        if (empty($target_year['era'])) {
            $target_year['era'] = $currentEraId;
        }
        if (empty($target_year['year'])) {
            $target_year['year'] = $currentEraYear;
        }

        $era = $target_year['era'] ?? null;
        $year = $target_year['year'] ?? null;
        // dd($era, $year);

        // 和暦を西暦に変換
        $search_era = null;
        if($era && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era, $year);
        }

// dd($search_era);
        $query = DB::table('dankas')
                ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                ->leftJoin('gozikaikaihi_lists', function($join) use ($search_era) {
                    $join->on('dankas.id', '=', 'gozikaikaihi_lists.danka_id')
                         ->whereRaw('YEAR(gozikaikaihi_lists.target_year) BETWEEN ? AND ?', [$search_era -5, $search_era]);
                    })
                ->select('dankas.id as danka_id',
                         'followers.name',
                         'followers.namekana',
                         'gozikaikaihi_lists.target_year',
                         'gozikaikaihi_lists.deposit_amount')
                ->where('dankas.gozikai', '=', 1)
                ->where('followers.chiefmourner_flg', '=', 1)
                ->orderBy('followers.name');

        $kaihilists = $query->paginate(10);

        $request->session()->put('target_year', $target_year);

        // 元号名を取得
        $era_name = Era::where('name', $era)->first()->name ?? '';

        // 列名を生成（西暦から和暦へ変換）
        $columns = [];
        $year_map = [];
        for ($i = 4; $i > 0; $i--) {
            $ad_year = $search_era - $i;
            // dd($search_era);
            $ja_year = CommonUtility::ADtoJACalendarConv("$ad_year-12-31");
            // dd($ja_year);
            $columns[] = $ja_year['era_name']. $ja_year['era_year'] . '年';
            $year_map[$ja_year['era_name']. $ja_year['era_year']. '年'] = $ad_year;
        }
        $ja_year = CommonUtility::ADtoJACalendarConv("$search_era-12-31");
        $columns[] = $ja_year['era_name']. $ja_year['era_year'] . '年'; // 検索で入力した年度を一番右に追加
        $year_map[$ja_year['era_name']. $ja_year['era_year']. '年'] = $search_era;

        // データを年度ごとに整理
        $data = [];
        foreach ($kaihilists as $kaihilist) {
            $danka_id = $kaihilist->danka_id;
            $year = Carbon::parse($kaihilist->target_year)->year;
            $data[$danka_id]['name'] = $kaihilist->name;
            $data[$danka_id]['namekana'] = $kaihilist->namekana;
            $data[$danka_id]['years'][$year] = $kaihilist->deposit_amount;
        }

        // dd($year);

        return view('kaihilists.index', compact('eras', 'kaihilists', 'currentEraId', 'currentEraYear', 'year', 'era_name', 'search_era', 'columns', 'data', 'year_map'))
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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

    public function print(Request $request)
    {
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8');
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');

        $target_year = $request->session()->get('target_year', []);

        $era_id = $target_year['era'] ?? null;
        $year = $target_year['year'] ?? null;

        $era  = Era::find($era_id);
        $era_name = $era ? $era->name : '';

        // 和暦を西暦に変換
        $search_era = null;
        if ($era_id && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era_id, $year);
        }

        $query = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->leftJoin('gozikaikaihi_lists', function($join) use ($search_era) {
                        $join->on('dankas.id', '=', 'gozikaikaihi_lists.danka_id')
                            ->whereRaw('YEAR(gozikaikaihi_lists.target_year) BETWEEN ? AND ?', [$search_era -5, $search_era]);
                        })
                    ->select('dankas.id as danka_id',
                             'followers.name',
                             'followers.namekana',
                             'gozikaikaihi_lists.target_year',
                             'gozikaikaihi_lists.deposit_amount')
                    ->where('dankas.gozikai', '=', 1)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderBy('followers.name');

        $kaihilists = $query->get();

        // データを年度ごとに整理
        $data = [];
        foreach ($kaihilists as $kaihilist) {
            $danka_id = $kaihilist->danka_id;
            $year = Carbon::parse($kaihilist->target_year)->year;
            $data[$danka_id]['name'] = $kaihilist->name;
            $data[$danka_id]['namekana'] = $kaihilist->namekana;
            $data[$danka_id]['years'][$year] = $kaihilist->deposit_amount;
        }

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Kaihilist.pdf';
        $templatePath = resource_path('template/Kaihilist.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);

        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null, null, null, null);

        // 列名を生成（西暦から和暦へ変換）
        $columns = [];
        $year_map = []; //西暦と和暦の対応を保持する配列
        for ($i = 4; $i > 0; $i--) {
            $ad_year = $search_era - $i;
            $ja_year = CommonUtility::ADtoJACalendarConv("$ad_year-12-31");
            $columns[] = $ja_year['era_name'] . $ja_year['era_year'] . '年';
            $year_map[$ja_year['era_name'] . $ja_year['era_year'] . '年'] = $ad_year;
        }
        $ja_year = CommonUtility::ADtoJACalendarConv("$search_era-12-31");
        $columns[] = $ja_year['era_name'] . $ja_year['era_year'] . '年'; // 検索で入力した年度を一番右に追加
        $year_map[$ja_year['era_name'] . $ja_year['era_year'] . '年'] = $search_era;


        $y = 0;
        $no = 0;
        $rowcnt = 0;
        foreach ($data as $danka_id => $details) {
            if ($rowcnt >=32) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $pdf->setFont($f, '', 11);
                $y = 0;
            }

        // 年度列名を動的に設定
        $x_positions = [58.7, 88.2, 117.4, 146.6, 175.8];
        foreach ($columns as $index => $column) {
            $pdf->setFont($f, '', 11);
            $pdf->Text($x_positions[$index], 24.1, $column);
        }

/*
        // 年度１
        $pdf->setFont($f, '', 11);
        $pdf->Text(58.7, 24.1, '令和　1年');
        // 年度２
        $pdf->setFont($f, '', 11);
        $pdf->Text(88.2, 24.1, '令和　2年');
        // 年度３
        $pdf->setFont($f, '', 11);
        $pdf->Text(117.4, 24.1, '令和　3年');
        // 年度４
        $pdf->setFont($f, '', 11);
        $pdf->Text(146.6, 24.1, '令和　4年');
        // 年度５
        $pdf->setFont($f, '', 11);
        $pdf->Text(175.8, 24.1, '令和　5年');
*/

        $rowcnt += 1;
        // No
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(8.8, 32.5 + $y, $no += 1);
        // 区分
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(18, 32.5 + $y, $details['name']);

        // 各年度の入金額を動的に設定
        $data = [];
        foreach ($columns as $index => $column) {
            $ad_year = $year_map[$column];
            $deposit_amount = $details['years'][$ad_year] ?? null;
            $pdf->setFont($f, '', 10.5);
            $pdf->Text($x_positions[$index] + 9.3, 32.5 + $y, $deposit_amount);
        }
    /*
        // 年度１_入金額
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(68, 32.5 + $y, '3,000');
        // 年度２_入金額
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(97.5, 32.5 + $y, '3,000');
        // 年度３_入金額
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(127, 32.5 + $y, '3,000');
        // 年度４_入金額
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(156.5, 32.5 + $y, '3,000');
        // 年度５_入金額
        $pdf->setFont($f, '', 10.5);
        $pdf->Text(186, 32.5 + $y, '3,000');
    */
        $y += 8.2;
        }

    // PDFをブラウザに出力
    $pdf->output('output.pdf', 'I');
}
}