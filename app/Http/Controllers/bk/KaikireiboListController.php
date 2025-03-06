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

class KaikireiboListController extends Controller
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
        if(strcmp($request->searchType, 'kaikireibo_search') === 0) {
            $put_flg = true;
        }

        // 元号のデータを取得
        $eras = Era::query()
                    ->select('eras.id',
                             'eras.name')
                    ->get();

        $kaikis = Kaiki::query()
                        ->select('kaikis.id',
                                 'kaikis.kaiki')
                        ->get();

        // 現在の日付を取得し、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraId = $currentEra['era_id'] ?? '';
        $currentEraYear = $currentEra['era_year'] ?? '';

        $target_year = CommonUtility::GetQueryParameter($request, 'target_year', $put_flg);
        
        // 一覧検索パラメータ取得
        $cond_kaikireibo = CommonUtility::GetQueryParameter($request, 'cond_kaikireibo', $put_flg);

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
                                 'chief.tel',
                                 'deceased.kaimyou',
                                 'deceased.zokumyou',
                                 'eras.name as death_era_name',
                                 'deceased.death_year',
                                 'deceased.death_month',
                                 'deceased.death_day',
                                 'deceased.ageatdeath')
                        ->where('chief.deceased_flg', '=', 0);


        if(!empty($cond_kaikireibo['area'])) {
            $query->where('area', '=', $cond_kaikireibo['area']);
        }
                        
        $kaikireibolists = $query->paginate(10);

        return view('kaikireibolists.index', compact('kaikireibolists', 'eras', 'kaikis', 'currentEraId', 'currentEraYear'))
            ->with('cond_kaikireibo', $cond_kaikireibo);
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

        // 検索データを取得
        $put_flg = false;
        if(strcmp($request->searchType, 'kaikireibo_search') === 0) {
            $put_flg = true;
        }

        $cond_kaikireibo = CommonUtility::GetQueryParameter($request, 'cond_kaikireibo', $put_flg);
    
        $keys = ListData::GetKaikireiboListKey($cond_kaikireibo);

        $print_flg = false;

        foreach ($keys as $key) {
            $print_flg = true;

/*            
            $query = DB::table('dankas')
                        ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                        ->where('dankas.area', '=', $key->value1)
                        ->where('deceased_flg', '=', 1);
*/

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
    
            if(!empty($cond_kaikireibo['area'])) {
                $query->where('area', '=', $cond_kaikireibo['area']);
            }

            $kaikireibolists = $query->get();

            // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
            $tpl_name = 'Kaikireibo.pdf';
            $templatePath = resource_path('template/KaikireiboList.pdf');
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
            foreach ($kaikireibolists as $kaikireibolist) {
                if ($rowcnt >= 30) {
                    $templateId = $pdf->importPage(2);
                    $pdf->AddPage();
                    $pdf->useTemplate($templateId, null, null, null, null, true);
                    $rowcnt = 0;
                    $page += 1;
                    $pdf->setFont($f, '', 11);
                    $pdf->Text(180, 282.5, $page);
                    $y = 15;
                }

                $rowcnt += 1;
                // 年数
                $pdf->setFont($f, '', 15);
                $pdf->Text(175, 13, '（平成３０年）');

                // 年度
                $pdf->setFont($f, '', 15);
                $pdf->Text(5, 19, '令和　6年');

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
                $pdf->setFont($f, '', 11);
                $pdf->Text(6, 37.5 + $y, $no += 1);

                // 代表者
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(14.5, 37.5 + $y, $kaikireibolist->chief_name);

                // 住所
                $pdf->setFont($f, '', 9.5);
                $pdf->MultiCell(60, 43, $kaikireibolist->address1 . $kaikireibolist->address2, 0, 'L', 0, 0, 51, 36 + $y);

                // 電話番号
                $pdf->setFont($f, '', 11);
                $pdf->Text(110.5, 37.5 + $y, $kaikireibolist->tel);

                // 戒名
                $pdf->setFont($f, '', 11);
                $pdf->Text(147, 37.5 + $y, $kaikireibolist->kaimyou);

                // 俗名
                $pdf->setFont($f, '', 11);
                $pdf->Text(194, 37.5 + $y, $kaikireibolist->zokumyou);

                // 命日
                if ($kaikireibolist->death_year && $kaikireibolist->death_month && $kaikireibolist->death_day) {        
                    $pdf->setFont($f, '', 11);
                    if ($kaikireibolist) {
                        $pdf->Text(234, 37.5 + $y, $kaikireibolist->death_era_name . $kaikireibolist->death_year . '年' . $kaikireibolist->death_month . '月' . $kaikireibolist->death_day . '日');
                    } else {
                        $pdf->Text(234, 37.5 + $y, '不明' . $kaikireibolist->death_year . '年' . $kaikireibolist->death_month . '月' . $kaikireibolist->death_day . '日');
                    }
                }

                // 行年
                $pdf->setFont($f, '', 11);
                $pdf->Text(282, 37.5 + $y, $kaikireibolist->ageatdeath);
                $y += 8.5;
            }   
        }
    
        // PDFをブラウザに出力
        $pdf->Output('output.pdf', 'I');
        $result = 0;
    }
}
