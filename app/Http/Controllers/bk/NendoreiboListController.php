<?php

namespace App\Http\Controllers;

use App\Models\Danka;
use App\Models\Era;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class NendoreiboListController extends Controller
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
        if(strcmp($request->searchType, 'nendoreibo_search') === 0) {
            $put_flg = true;
        }

        // 元号のデータを取得
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

        $query = Danka::query()
                        ->join('followers as chief', function($join) {
                            $join->on('dankas.id', '=', 'chief.danka_id')
                                ->where('chief.chiefmourner_flg', '=', 1);
                        })
                        ->leftJoin('followers as deceased', function($join) {
                            $join->on('dankas.id', '=', 'deceased.danka_id')
                                ->where('deceased.deceased_flg', '=', 1);
                        })
                        ->leftJoin('eras', 'deceased.death_era', '=', 'eras.id')
                        ->select('dankas.id',
                                 'chief.name as chief_name',
                                 'chief.address1',
                                 'chief.address2',
                                 'chief.postcode',
                                 'deceased.kaimyou',
                                 'deceased.zokumyou',
                                 'deceased.relationship',
                                 'deceased.deathanniversary',
                                 'eras.name as death_era_name',
                                 'deceased.death_year',
                                 'deceased.death_month',
                                 'deceased.death_day',
                                 'deceased.ageatdeath')
                        ->where('chief.deceased_flg', '=', 0);

        if(!empty($target_year['era'])) {
            $query->where('deceased.death_era', '=', $target_year['era']);
           } 
           if(!empty($target_year['year'])) {
            $query->where('deceased.death_year', '=', $target_year['year']);
           }

        // 命日の年度が一致するデータを出力
        if ($search_era) {
            $query->where('deceased.deathanniversary', 'like', $search_era. '%');
        }
                        
        $nendoreibolists = $query->paginate(10);

        // 検索条件をセッションに保存
        session(['target_year', $target_year]);

        return view('nendoreibolists.index', compact('nendoreibolists', 'eras', 'currentEraId', 'currentEraYear'))
            ->with('target_year', $target_year);
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
        $pdf = new Fpdi($orientation='L', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8');
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');

        // セッションから検索条件を取得
        $target_year = session('target_year');

        // 元号の名前を取得
        $era_name = Era::where('id', $target_year['era'])->value('name');

        $print_flg = false;

        $query = Danka::query()
                    ->join('followers as chief', function($join) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                            ->where('chief.chiefmourner_flg', '=', 1);
                    })
                    ->leftJoin('followers as deceased', function($join) {
                        $join->on('dankas.id', '=', 'deceased.danka_id')
                            ->where('deceased.deceased_flg', '=', 1);
                    })
                    ->leftJoin('eras', 'deceased.death_era', '=', 'eras.id')
                    ->select('dankas.id',
                            'chief.name as chief_name',
                            'chief.address1',
                            'chief.address2',
                            'chief.tel',
                            'deceased.kaimyou',
                            'deceased.zokumyou',
                            'eras.name as death_era_name',
                            'deceased.death_year',
                            'deceased.death_month',
                            'deceased.death_day',
                            'deceased.ageatdeath')
                    ->where('chief.deceased_flg', '=', 0);

            if(!empty($target_year['era'])) {
                $query->where('deceased.death_era', '=', $target_year['era']);
            } 
            if(!empty($target_year['year'])) {
                $query->where('deceased.death_year', '=', $target_year['year']);
            }

            $nendoreibolists = $query->get();

            // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
            $tpl_name = 'Nendoreibo.pdf';
            $templatePath = resource_path('template/NendoreiboList.pdf');
            $pdf->setSourceFile($templatePath);

            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            
            // 新規ページをセット
            $pdf->AddPage();

            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            $pdf->setFont($f, '', 11);
            $pdf->Text(180, 282.5, $page);

            $y = 0;
            $no = 0;
            $rowcnt = 0;
            foreach ($nendoreibolists as $nendoreibolist) {
                if ($rowcnt >= 17) {
                    $templateId = $pdf->importPage(1);
                    $pdf->AddPage();
                    $pdf->useTemplate($templateId, null, null, null, null, true);
                    $rowcnt = 0;
                    $page += 1;
                    $pdf->setFont($f, '', 11);
                    $pdf->Text(180, 282.5, $page);
                    $y = 0;
                }

                $rowcnt += 1;
                // 年数
                $pdf->setFont($f, '', 17);
                $pdf->Text(160, 10, '（ '. $era_name. ' '. $target_year['year']   .' 年度 ）');

                // 現在日付（和暦）
                $currentDate = Carbon::now();
                if(!is_null($currentDate)) {
                    $target_date = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
                    $era_name = $target_date['era_name'] ?? '';
                    $era_year = $target_date['era_year'] ?? '';
                    $month = $target_date['month'] ?? '';
                    $day = $target_date['day'] ?? '';
                } else {
                    $era_name = '';
                    $era_year = '';
                    $month = '';
                    $day = '';
                }
                $pdf->setFont($f, '', 12);
                $pdf->Text(230, 9, '日付：　'. $era_name . $era_year . '年' . $month . '月' . $day . '日');

                // No
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(6.3, 32.3 + $y, $no += 1);

                // 代表者
                $pdf->setFont($f, '', 12);
                $pdf->Text(15, 32 + $y, $nendoreibolist->chief_name);

                // 住所
                $pdf->setFont($f, '', 10);
                $pdf->MultiCell(60, 43, $nendoreibolist->address1 . $nendoreibolist->address2, 0, 'L', 0, 0, 51.5, 30 + $y);

                // 電話番号
                $pdf->setFont($f, '', 12);
                $pdf->Text(110.5, 32 + $y, $nendoreibolist->tel);

                // 戒名
                $pdf->setFont($f, '', 12);
                $pdf->Text(147, 32 + $y, $nendoreibolist->kaimyou);

                // 俗名
                $pdf->setFont($f, '', 10);
                $pdf->Text(194, 30 + $y, $nendoreibolist->zokumyou);

                // 続柄
                $pdf->setFont($f, '', 10);
                $pdf->Text(194, 35 + $y, $nendoreibolist->relationship);

                // 命日
                if ($nendoreibolist->death_year && $nendoreibolist->death_month && $nendoreibolist->death_day) {        
                    $pdf->setFont($f, '', 12);
                    if ($nendoreibolist) {
                        $pdf->Text(234, 32 + $y, $nendoreibolist->death_era_name . $nendoreibolist->death_year . '年' . $nendoreibolist->death_month . '月' . $nendoreibolist->death_day . '日');
                    } else {
                        $pdf->Text(234, 32 + $y, '不明' . $nendoreibolist->death_year . '年' . $nendoreibolist->death_month . '月' . $nendoreibolist->death_day . '日');
                    }
                }

                // 行年
                $pdf->setFont($f, '', 12);
                $pdf->Text(282, 32 + $y, $nendoreibolist->ageatdeath);
                $y += 10.2;
            }   

        // PDFをブラウザに出力
        $pdf->Output('output.pdf', 'I');
        $result = 0;
    }
}
