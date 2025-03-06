<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Danka;
use App\Models\Era;
use App\Models\Follower;
use App\Models\Kaiki;
use App\Services\CommonUtility;
use App\Services\ListData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AreakaikiListController extends Controller
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
        if(strcmp($request->searchType, 'areakaiki_search') === 0) {
            $put_flg = true;
        }

        // 元号のデータを取得
        $eras = Era::query()
                    ->select('eras.id',
                             'eras.name',
                             'eras.ad_start',
                             'eras.start_ymd',
                             'eras.end_ymd')
                    ->get();

        // 地区名のデータを取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();
        
        // 現在の日付を取得し、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraId = $currentEra['era_id'] ?? '';
        $currentEraYear = $currentEra['era_year'] ?? '';

        $target_year = CommonUtility::GetQueryParameter($request, 'target_year', $put_flg);

        // 一覧検索パラメータ取得
        $cond_areakaiki = CommonUtility::GetQueryParameter($request, 'cond_areakaiki', $put_flg);

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
        // dd($search_era);


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
                            'chief.namekana as chief_namekana',
                            'dankas.area',
                            'deceased.kaimyou',
                            'deceased.zokumyou',
                            'deceased.deathanniversary',
                            'eras.name as death_era_name',
                            'deceased.death_year',
                            'deceased.death_month',
                            'deceased.death_day',
                            'chief.address1',
                            'chief.address2')
                    ->where('chief.deceased_flg', '=', 0);
                    
        if(!empty($cond_areakaiki['area'])) {
            $query->where('area', '=', $cond_areakaiki['area']);
        }
                        
        $areakaikilists = $query->paginate(10);
        // dd($search_era, $areakaikilists);

        // 回忌のデータを取得
        $kaikis = Kaiki::all();

        // 回忌を計算して追加
        foreach ($areakaikilists as $areakaiki) {
            $deathYear = Carbon::parse($areakaiki->deathanniversary)->year;
            $kaikiYear = $search_era - $deathYear + 1;

            // 回忌年数が回忌テーブルの値と一致するか確認
            $kaiki = $kaikis->firstwhere('kaiki', $kaikiYear);
            if($kaiki) {
                $areakaiki->kaiki = $kaikiYear;
            } else {
                $areakaiki->kaiki = null;
            }
        }

        // 一致するデータのみフィルタリング
        $areakaikilists = $areakaikilists->filter(function($areakaiki) {
            return $areakaiki->kaiki !== null;
        });

        // 検索条件をセッションに保存
        session(['target_year', $target_year]);

        return view('areakaikilists.index', compact('areakaikilists', 'eras', 'areas', 'currentEraId', 'currentEraYear'))
            ->with('cond_areakaiki', $cond_areakaiki)
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

        $era = $target_year['era'] ?? null;
        $year = $target_year['year'] ?? null;

        // 和暦を西暦に変換
        $search_era = null;
        if($era && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era, $year);
        }

        // 元号の名前を取得
        $era_name = Era::where('id', $target_year['era'])->value('name');

        // 検索データを取得
        $put_flg = false;
        if(strcmp($request->searchType, 'areakaiki_search') === 0) {
            $put_flg = true;
        }

        $cond_areakaiki = CommonUtility::GetQueryParameter($request, 'cond_areakaiki', $put_flg);
    
        $keys = ListData::GetAreaListKey($cond_areakaiki);

        $print_flg = false;

        foreach ($keys as $key) {
            $print_flg = true;

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
                                'chief.namekana as chief_namekana',
                                'dankas.area',
                                'deceased.kaimyou',
                                'deceased.zokumyou',
                                'deceased.deathanniversary',
                                'eras.name as death_era_name',
                                'deceased.death_year',
                                'deceased.death_month',
                                'deceased.death_day',
                                'chief.address1',
                                'chief.address2',
                                'chief.tel')
                        ->where('dankas.area', '=', $key->value1)
                        ->where('chief.deceased_flg', '=', 0);
    
            if(!empty($cond_areakaiki['area'])) {
                $query->where('area', '=', $cond_areakaiki['area']);
            }
    
            $areakaikilists = $query->get();
            // dd($areakaikilists);

            // 回忌のデータを取得
            $kaikis = Kaiki::all();

            // 回忌を計算して追加
            foreach ($areakaikilists as $areakaiki) {
                $deathYear = Carbon::parse($areakaiki->deathanniversary)->year;
                $kaikiYear = $search_era - $deathYear + 1;
                // dd($kaikiYear, $deathYear);
    
                // 回忌年数が一致するか確認
                $kaiki = $kaikis->firstWhere('kaiki', $kaikiYear);
                if ($kaiki) {
                    $areakaiki->kaiki = $kaikiYear;
                } else {
                    $areakaiki->kaiki = null;
                }
            }

            // 一致するデータのみフィルタリング
            $areakaikilists = $areakaikilists->filter(function($areakaiki) {
                return $areakaiki->kaiki !== null;
            });


            if($areakaikilists->isEmpty()) {
                continue;
            }

            // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
            $tpl_name = 'Arealist.pdf';
            $templatePath = resource_path('template/AreakaikiList.pdf');
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
            foreach ($areakaikilists as $areakaikilist) {
                if ($rowcnt >= 14) {
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
                // 地区名
                $pdf->setFont($f, '', 20);
                $pdf->Text(175, 14, '（'. $areakaikilist->area. '地区）');

                // 年度
                $pdf->setFont($f, '', 17);
                $pdf->Text(15, 22, $era_name . ' '. $target_year['year'] .' 年');

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
                $pdf->Text(230, 7, '日付：　'. $era_name . $era_year . '年' . $month . '月' . $day . '日');

                // No
                $pdf->setFont($f, '', 12);
                $pdf->Text(11, 43 + $y, $no += 1);

                // 代表者
                $pdf->setFont($f, '', 12);
                $pdf->Text(24, 43 + $y, $areakaikilist->chief_name);

                // 住所
                $pdf->setFont($f, '', 11.5);
                $pdf->MultiCell(82, 43, $areakaikilist->address1 . $areakaikilist->address2, 0, 'L', 0, 0, 61, 43 + $y);

                // 電話番号
                $pdf->setFont($f, '', 12);
                $pdf->Text(141, 43 + $y, $areakaikilist->tel);

                // 回忌
                $pdf->setFont($f, '', 12);
                $pdf->Text(182, 43 + $y, $areakaikilist->kaiki);

                // 戒名
                $pdf->setFont($f, '', 12);
                $pdf->Text(194, 43 + $y, $areakaikilist->kaimyou);

                // 命日
                if ($areakaikilist->death_year && $areakaikilist->death_month && $areakaikilist->death_day) {
                    $pdf->setFont($f, '', 11.5);
                    if ($areakaikilist) {
                        $pdf->Text(249, 43 + $y, $areakaikilist->death_era_name . $areakaikilist->death_year . '年' . $areakaikilist->death_month . '月' . $areakaikilist->death_day . '日');
                    } else {
                        $pdf->Text(249, 43 + $y, '不明' . $areakaikilist->death_year . '年' . $areakaikilist->death_month . '月' . $areakaikilist->death_day . '日');
                    }
                }
                $y += 11;
            }   
        }
    
        // PDFをブラウザに出力
        $pdf->Output('output.pdf', 'I');
        $result = 0;

    }
}
