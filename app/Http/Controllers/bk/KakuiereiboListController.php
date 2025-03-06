<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Danka;
use App\Services\CommonUtility;
use App\Services\ListData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class KakuiereiboListController extends Controller
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
        if(strcmp($request->searchType, 'kakuiereibo_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();

        // 地区名検索パラメータ取得
        $cond_kakuiereibo = CommonUtility::GetQueryParameter($request, 'cond_kakuiereibo', $put_flg);

        $kakuiereibolists = collect();

        if ($put_flg && !empty($cond_kakuiereibo['area'])) {
            $query = Danka::query()
                        ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                        ->select('dankas.id',
                                'dankas.dankadivision',
                                'dankas.area',
                                'followers.name',
                                'followers.tel',
                                'followers.postcode',
                                'followers.address1',
                                'followers.address2',
                                'dankas.postcard')
                        ->where('dankas.area', '!=', null)
                        ->where('chiefmourner_flg', '=', 1);

            if(!empty($cond_kakuiereibo['area'])) {
                $query->where('area', '=', $cond_kakuiereibo['area']);
            }
            
            $kakuiereibolists = $query->paginate(10);
        }

        return view('kakuiereibolists.index', compact('kakuiereibolists', 'areas'))
            ->with('cond_kakuiereibo', $cond_kakuiereibo);
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
        if(strcmp($request->searchType, 'kakuiereibo_search') === 0) {
            $put_flg = true;
        }

        $cond_kakuiereibo = CommonUtility::GetQueryParameter($request, 'cond_kakuiereibo', $put_flg);
    
        $keys = ListData::GetKaikireiboListKey($cond_kakuiereibo);

        $print_flg = false;

        foreach ($keys as $key) {
            $query = DB::table('dankas')
                        ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                        ->where('followers.chiefmourner_flg', '=', 1);

    
            if(!empty($cond_kakuiereibo['area'])) {
                $query->where('area', '=', $cond_kakuiereibo['area']);
            }

            $kakuiereibolists = $query->get();

            // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
            $tpl_name = 'Kakuiereibo.pdf';
            $templatePath = resource_path('template/Kakuiereibolist.pdf');
            $pdf->setSourceFile($templatePath);

            foreach ($kakuiereibolists as $kakuiereibolist) {
                // テンプレートPDFの1ページ目を読み込み
                $templateId = $pdf->importPage(1);
            
                // 新規ページをセット
                $pdf->AddPage();

                // 読み込んだページをテンプレートに使用
                $pdf->useTemplate($templateId, null, null ,null, null, true);

                // 年齢計算
                $birthdate = Carbon::parse($kakuiereibolist->birthdate);
                $age = $birthdate->age;

                // 現在日付（和暦）
                $currentDate = Carbon::now();
                $target_date = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
                $era_name = $target_date['era_name'] ?? '';
                $era_year = $target_date['era_year'] ?? '';
                $month = $target_date['month'] ?? '';
                $day = $target_date['day'] ?? '';
                $weekday = $currentDate->isoFormat('dddd');
                
                $pdf->setFont($f, '', 9.5);
                $pdf->Text(160, 15, $era_name . $era_year . '年' . $month . '月' . $day . '日'. $weekday);

                // 氏名(カナ)
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(33, 27.5, $kakuiereibolist->namekana);

                // 氏名(漢字)
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(121, 27.5, $kakuiereibolist->name);

                // 地区名
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(33, 35.5, $kakuiereibolist->area);

                // 檀家区分
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(91.5, 35.5, $kakuiereibolist->dankadivision);

                // 位牌区分
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(150, 35.5, $kakuiereibolist->mortuarytablet);

                // 墓地区分
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(33, 43.5, 'テスト');

                // 護持会
                $pdf->setFont($f, '', 10.5);
                $pdf->Rect(122, 44, 3.5, 3.5);
                if ($kakuiereibolist->gozikai == 1) {
                    $pdf->Text(120.8, 43.5, '✓');
                }

                // 会費
                $pdf->setFont($f, '', 10.5);
                $pdf->Rect(158, 44, 3.5, 3.5);
                if ($kakuiereibolist->membershipfee == 1) {
                    $pdf->Text(156.8, 43.5, '✓');
                }

                // 生前戒名
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(33, 51.5, $kakuiereibolist->seizenkaimyou);

                // 郵便番号
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(157, 51.5, $kakuiereibolist->postcode);

                // 住所１
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(33, 59.5, $kakuiereibolist->address1);

                // 住所２
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(33, 68, $kakuiereibolist->address2);

                // TEL
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(157, 59.5, $kakuiereibolist->tel);

                // TEL2
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(157, 68, '090-1234-5678');

                // 性別
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(25, 76, $kakuiereibolist->gender);

                // 生年月日
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(69, 76, $kakuiereibolist->birthdate);

                // 年齢
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(120, 76, $age);

                // 職業
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(150, 76, $kakuiereibolist->occupation);

                // 寺役職
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(25, 84, $kakuiereibolist->position);

                // はがき区分
                $pdf->setFont($f, '', 10.5);
                $pdf->Text(143, 84, $kakuiereibolist->postcard);

                // メモ
                $pdf->setFont($f, '', 10.5);
                $pdf->MultiCell(175, 20, $kakuiereibolist->memo, 0, 'L', 0, 0, 25, 91.5);
                
                // // 命日
                // if ($kakuiereibolist->death_year && $kakuiereibolist->death_month && $kakuiereibolist->death_day) {        
                //     $pdf->setFont($f, '', 11);
                //     if ($kakuiereibolist) {
                //         $pdf->Text(234, 37.5 + $y, $kakuiereibolist->death_era_name . $kakuiereibolist->death_year . '年' . $kakuiereibolist->death_month . '月' . $kakuiereibolist->death_day . '日');
                //     } else {
                //         $pdf->Text(234, 37.5 + $y, '不明' . $kakuiereibolist->death_year . '年' . $kakuiereibolist->death_month . '月' . $kakuiereibolist->death_day . '日');
                //     }
                // }

                // // 行年
                // $pdf->setFont($f, '', 11);
                // $pdf->Text(282, 37.5 + $y, $kakuiereibolist->ageatdeath);
                // $y += 8.5;
            }   
        }
    
        // PDFをブラウザに出力
        $pdf->Output('output.pdf', 'I');
        $result = 0;
    }
}
