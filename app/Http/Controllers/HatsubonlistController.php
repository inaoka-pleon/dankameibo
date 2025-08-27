<?php

namespace App\Http\Controllers;

use App\Models\Danka;
use App\Models\Kaiki;
use App\Services\CommonUtility;
use App\Services\KaikiData;
use App\Services\PostcardPrint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class HatsubonlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $put_flg = false;
        if(strcmp($request->searchType, 'hatsubonlist_search') === 0) {
            $put_flg = true;
        }

        // 回忌のデータを取得
        $kaikis = Kaiki::query()
                       ->select('kaikis.id',
                                'kaikis.kaiki',
                                'kaikis.from_year_kbn',
                                'kaikis.from_month',
                                'kaikis.from_day',
                                'kaikis.to_year_kbn',
                                'kaikis.to_month',
                                'kaikis.to_day')
                        ->where('kaikis.kaiki_kbn', '=', 3)
                       ->get();

        if ($kaikis->isEmpty()) {
            return view('hatsubonlists.index', [
                'hatsubonlists' => collect(), // 空のコレクションを渡す
                'kaikis' => collect(), // 空のコレクションを渡す
                'hatsubonCount' => 0,
                // 初盆期間関連の変数をすべてnullで渡す
                'FromEraName' => null, 'FromEraYear' => null, 'FromMonth' => null, 'FromDay' => null,
                'ToEraName' => null, 'ToEraYear' => null, 'ToMonth' => null, 'ToDay' => null,
            ]);
        }
 
        $from_date = null;
        $to_date = null;

        if ($kaikis->isNotEmpty()) {
            // 初盆の期間を取得
            foreach ($kaikis as $kaiki) {
                $kaiki->from_date = KaikiData::GetYmd($kaiki->from_year_kbn,
                                                    $kaiki->from_month,
                                                    $kaiki->from_day);
                $kaiki->to_date = KaikiData::GetYmd($kaiki->to_year_kbn,
                                                    $kaiki->to_month,
                                                    $kaiki->to_day);
                if ($kaiki->from_date && $kaiki->to_date) {
                    $from_date = $kaiki->from_date;
                    $to_date = $kaiki->to_date;
                    break;
                }
            }
        }

        $FromEraName = null;
        $FromEraYear = null;
        $FromMonth = null;
        $FromDay = null;
        $ToEraName = null;
        $ToEraYear = null;
        $ToMonth = null;
        $ToDay = null;

        if ($from_date && $to_date) {
            // 初盆の期間を西暦から和暦に変換
            $FromEra = CommonUtility::ADtoJACalendarConv($kaiki->from_date);
            $FromEraName = $FromEra['era_name'] ?? '';
            $FromEraYear = $FromEra['era_year'] ?? '';
            $FromMonth = $FromEra['month'] ?? '';
            $FromDay = $FromEra['day'] ?? '';

            $ToEra = CommonUtility::ADtoJACalendarConv($kaiki->to_date);
            $ToEraName = $ToEra['era_name'] ?? '';
            $ToEraYear = $ToEra['era_year'] ?? '';
            $ToMonth = $ToEra['month'] ?? '';
            $ToDay = $ToEra['day'] ?? '';
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
                                 'chief.address1',
                                 'chief.address2',
                                 'chief.name as chief_name',
                                 'chief.tel',
                                 'dankas.postcard',
                                 'deceased.kaimyou',
                                 'deceased.zokumyou',
                                 'deceased.deathanniversary',
                                 'eras.name as death_era_name',
                                 'deceased.death_year',
                                 'deceased.death_month',
                                 'deceased.death_day',
                                 'deceased.ageatdeath')
                        ->where('chief.deceased_flg', '=', 0)
                        ->orderbyraw('YEAR(deceased.deathanniversary) asc')
                        ->orderby('deceased.death_month', 'asc')
                        ->orderby('deceased.death_day', 'asc');
        
        // フィルタリングをクエリビルダーで行う
        $query->where(function ($query) use ($kaikis) {
            foreach ($kaikis as $kaiki) {
                $query->whereBetween('deceased.deathanniversary', [$kaiki->from_date, $kaiki->to_date]);
            }
        });
                        
        $hatsubonlists = $query->paginate(10);

        // 該当件数表示
        $hatsubonCount = $hatsubonlists->total();

        // フィルタリングしたデータをセッションに保存
        session(['hatsubonlists' => $hatsubonlists]);

        return view('hatsubonlists.index', compact('hatsubonlists', 'kaikis', 'FromEraName', 'FromEraYear', 'FromMonth', 'FromDay', 'ToEraName', 'ToEraYear', 'ToMonth', 'ToDay', 'hatsubonCount'));
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
        $action = $request->query('action');
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
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

        // セッションからフィルタリングしたデータを取得
        $hatsubonlists = session('hatsubonlists');

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Hatsubonlist.pdf';
        $templatePath = resource_path('template/Hatsubonlist.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);
        
        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null ,null, null, true);

        $page = 1;

        $pdf->setFont($f, '', 11);
        $pdf->Text(255, 202, 'ページ：　'. $page);

        $y = 0;
        $no = 0;
        $rowcnt = 0;
        $hatsubonPrinted = false;
        foreach ($hatsubonlists as $hatsubonlist) {
            if ($rowcnt >= 15) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $page += 1;
                $pdf->setFont($f, '', 11);
                $pdf->Text(255, 202, 'ページ：　'. $page);
                $y = 0;
                $hatsubonPrinted = false;
            }

            $rowcnt += 1;
            if (!$hatsubonPrinted) {
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
                $hatsubonPrinted = true;
            }

            // No
            $pdf->setFont($f, '', 12);
            $pdf->Text(10, 33 + $y, $no += 1);

            // 氏名
            $pdf->setFont($f, '', 12);
            $pdf->Text(21, 33 + $y, $hatsubonlist->chief_name);

            // 住所
            $pdf->setFont($f, '', 10);
            $pdf->MultiCell(60, 43, $hatsubonlist->address1. $hatsubonlist->address2, 0, 'L', 0, 0, 57.5, 31.5 + $y);

            // 電話番号
            $pdf->setFont($f, '', 12);
            $pdf->Text(116, 33 + $y, $hatsubonlist->tel);

            // 戒名
            $pdf->setFont($f, '', 10.5);
            $pdf->Text(153, 31 + $y, $hatsubonlist->kaimyou);

            // 俗名
            $pdf->setFont($f, '', 10.5);
            $pdf->Text(153, 36 + $y, $hatsubonlist->zokumyou);

            // 命日
            if ($hatsubonlist->death_year && $hatsubonlist->death_month && $hatsubonlist->death_day) {        
                $pdf->setFont($f, '', 12);
                if ($hatsubonlist) {
                    $pdf->Text(208, 33 + $y, $hatsubonlist->death_era_name . $hatsubonlist->death_year . '年' . $hatsubonlist->death_month . '月' . $hatsubonlist->death_day . '日');
                } else {
                    $pdf->Text(200.5, 33 + $y, '不明' . $hatsubonlist->death_year . '年' . $hatsubonlist->death_month . '月' . $hatsubonlist->death_day . '日');
                }
            }

            // 行年
            $pdf->setFont($f, '', 12);
            $pdf->Text(250, 33 + $y, $hatsubonlist->ageatdeath. ' 歳');

            // はがき
            $pdf->setFont($f, '', 12);
            $pdf->Text(264, 33 + $y, $hatsubonlist->postcard);
            $y += 11.3;
        }   
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function yomikomicho_print(Request $request)
    {
        $action = $request->query('action');
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

        // セッションからフィルタリングしたデータを取得
        $hatsubonlists = session('hatsubonlists');

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Yomikomicho.pdf';
        $templatePath = resource_path('template/Yomikomicho.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);
        
        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null ,null, null, true);

        $page = 1;

        // 初盆忌
        $pdf->setFont($f, '', 35);
        $text = '初盆忌';
        $x = 270;
        $y = 80;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 17;
        }

        // 見出しを描画する関数
        function headings($pdf, $f, $page)
        {
            // ページ数に応じてx軸の位置を変更
            $x = ($page > 1) ? 267 : 252;

            // 戒名
            $pdf->setFont($f, '', 18);
            $text = '戒名';
            $y = 20;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 20;
            }

            // 俗名
            $pdf->setFont($f, '', 18);
            $text = '俗名';
            $y = 110;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 13;
            }

            // 代表者
            $pdf->setFont($f, '', 18);
            $text = '代表者';
            $y = 160;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 10;
            }
        }

        headings($pdf, $f, $page);

        $x = 237;
        $y = 0;
        $no = 0;
        $rowcnt = 0;
        foreach ($hatsubonlists as $hatsubonlist) {
            if ($page === 14) {
                if ($rowcnt >= 1) {
                    $templateId = $pdf->importPage(1);
                    $pdf->AddPage();
                    $pdf->useTemplate($templateId, null, null, null, null, true);
                    $rowcnt = 0;
                    $page += 1;
                    $pdf->setFont($f, '', 11);
                    $x = 252;
                    $y = 0;
                    headings($pdf, $f, $page);
                }
            } else {
                if ($rowcnt >= 15) {
                    $templateId = $pdf->importPage(1);
                    $pdf->AddPage();
                    $pdf->useTemplate($templateId, null, null, null, null, null, true);
                    $rowcnt = 0;
                    $page += 1;
                    $pdf->setFont($f. '', 11);
                    $x = 252;
                    $y = 0;   
                    headings($pdf, $f, $page);     
                }
            }

            $rowcnt += 1;
            // 戒名
            $pdf->setFont($f, '', 20);
            $text = $hatsubonlist->kaimyou;
            $y = 8;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 7;
            }

            // 俗名
            $pdf->setFont($f, '', 18);
            $text = $hatsubonlist->zokumyou;
            $y = 110;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 6;
            }

            // 代表者
            $pdf->setFont($f, '', 18);
            $text = $hatsubonlist->chief_name;
            $y = 160;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 6;
            }

            $x -= 15;
        }   
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'Yomikomicho_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // はがき印刷で表示するデータの取得
    public function getHatsubonData()
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $hatsubonlist = collect();

        // 回忌のデータを取得
        $kaikis = Kaiki::query()
                ->select('kaikis.id',
                        'kaikis.kaiki',
                        'kaikis.from_year_kbn',
                        'kaikis.from_month',
                        'kaikis.from_day',
                        'kaikis.to_year_kbn',
                        'kaikis.to_month',
                        'kaikis.to_day')
                ->where('kaikis.kaiki_kbn', '=', 3)
                ->get();
 
        // 初盆の期間を取得
        foreach ($kaikis as $kaiki) {
            $kaiki->from_date = KaikiData::GetYmd($kaiki->from_year_kbn,
                                                  $kaiki->from_month,
                                                  $kaiki->from_day);
            $kaiki->to_date = KaikiData::GetYmd($kaiki->to_year_kbn,
                                                $kaiki->to_month,
                                                $kaiki->to_day);
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
                         'chief.address1',
                         'chief.address2',
                         'chief.name as chief_name',
                         'chief.tel',
                         'chief.postcode',
                         'dankas.postcard',
                         'deceased.kaimyou',
                         'deceased.zokumyou',
                         'deceased.deathanniversary',
                         'eras.name as death_era_name',
                         'deceased.death_year',
                         'deceased.death_month',
                         'deceased.death_day',
                         'deceased.ageatdeath')
                ->where('chief.deceased_flg', '=', 0)
                ->where('dankas.postcard', '=', '出す')
                ->where('dankas.jiin_id', '=', $userJiinId)
                ->orderbyraw('YEAR(deceased.deathanniversary) desc')
                ->orderby('deceased.death_month', 'asc')
                ->orderby('deceased.death_day', 'asc');
        
        // フィルタリングをクエリビルダーで行う
        $query->where(function ($query) use ($kaikis) {
            foreach ($kaikis as $kaiki) {
                $query->whereBetween('deceased.deathanniversary', [$kaiki->from_date, $kaiki->to_date]);
            }
        });

        $hatsubonlists = $hatsubonlist->merge($query->get());

        return $hatsubonlists;
    }
    public function postcard_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hatsubonlists = $this->getHatsubonData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($hatsubonlists as $hatsubonlist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $hatsubonlist->chief_name. ' 様';
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $hatsubonlist->postcode;
            $text = str_replace('-', '', $hatsubonlist->postcode);
            $positions = [
                [45.7, 13],
                [52.7, 13],
                [59.7, 13],
                [66.7, 13],
                [73.5, 13],
                [80.45, 13],
                [87.4, 13],
            ];
            PostcardPrint::postcardPostcode($pdf, $f, $text, $positions, 12);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 25, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);     
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function envelope4_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createEnvelope4Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hatsubonlists = $this->getHatsubonData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hatsubonlists as $hatsubonlist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hatsubonlist->chief_name. ' 様';
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $hatsubonlist->postcode;
            $text = str_replace('-', '', $hatsubonlist->postcode);
            $positions = [
                [34.4, 13.5],
                [41.35, 13.5],
                [48.3, 13.5],
                [55.9, 13.5],
                [62.85, 13.5],
                [69.8, 13.5],
                [76.75, 13.5],
            ];
            PostcardPrint::Envelope4Postcode($pdf, $f, $text, $positions, 12);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);   
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function envelope3_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createEnvelope3Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hatsubonlists = $this->getHatsubonData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hatsubonlists as $hatsubonlist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hatsubonlist->chief_name. ' 様';
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $hatsubonlist->postcode;
            $text = str_replace('-', '', $hatsubonlist->postcode);
            $positions = [
                [64.45, 13.5],
                [71.45, 13.5],
                [78.45, 13.5],
                [86.1, 13.5],
                [93.15, 13.5],
                [99.95, 13.5],
                [106.65, 13.5],
            ];
            PostcardPrint::Envelope3Postcode($pdf, $f, $text, $positions, 12);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 90, 30, 26);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function square3_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createSquare3Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hatsubonlists = $this->getHatsubonData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hatsubonlists as $hatsubonlist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hatsubonlist->chief_name. ' 様';
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 50, 56);

            if (!is_null($hatsubonlist->postcode)) {
                // 〒
                $pdf->setFont($f, '', 15);
                $pdf->Text(136.5, 15, '〒');

                // ハイフン
                $pdf->setFont($f, '', 11);
                $pdf->Text(166.25, 16, '―');

                // // ハイフン
                // $pdf->setFont($f, '', 11);
                // $pdf->Text(165.5, 16, 'ー');
                // 文字を出力、位置指定(郵便番号)
                $text = $hatsubonlist->postcode;
                $text = str_replace('―', '', $hatsubonlist->postcode);
                $positions = [
                    [143.9, 14.2],
                    [152.05, 14.2],
                    [160.2, 14.2],
                    [172, 14.2],
                    [180.15, 14.2],
                    [188.3, 14.2],
                    [196.45, 14.2],
                ];
                PostcardPrint::Square3Postcode($pdf, $f, $text, $positions, 18);
            }

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function square2_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createSquare2Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hatsubonlists = $this->getHatsubonData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hatsubonlists as $hatsubonlist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hatsubonlist->chief_name. ' 様';
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);

            if (!is_null($hatsubonlist->postcode)) {
                // 〒
                $pdf->setFont($f, '', 18);
                $pdf->Text(154.5, 15, '〒');

                // ハイフン
                $pdf->setFont($f, '', 16);
                $pdf->Text(186.8, 15.5, '―');

                // // ハイフン
                // $pdf->setFont($f, '', 16);
                // $pdf->Text(189, 15.2, 'ー');
                // 文字を出力、位置指定(郵便番号)
                $text = str_replace('―', '', $hatsubonlist->postcode);
                $positions = [
                    [162.9, 13.5],
                    [171.55, 13.5],
                    [180.2, 13.5],
                    [194, 13.5],
                    [202.65, 13.5],
                    [211.3, 13.5],
                    [219.05, 13.5],
                ];
                PostcardPrint::Square2Postcode($pdf, $f, $text, $positions, 24);
            }

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hatsubonlist->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // ラベル
    public function label_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createLabelInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hatsubonlists = $this->getHatsubonData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($hatsubonlists as $index => $hatsubonlist) {
            if ($index % 12 == 0) {
                // 新規ページをセット
                $pdf->AddPage();
                // テンプレートPDFの1ページ目を読み込み
                $templateId = $pdf->importPage(1);
                // 読み込んだページをテンプレートに使用
                $pdf->useTemplate($templateId, null, null ,null, null, true);
            }

            $xOffset = ($column % 2) * 83;
            $yOffset = ($row % 6) * 42.5;
            $page = 1;

            // 表示させるデータ取得
            $name = $hatsubonlist->chief_name;
            $keishou = null;
            $postcode = $hatsubonlist->postcode;
            $address = ltrim($hatsubonlist->address1 . $hatsubonlist->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function back_print(Request $request)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();
        
        // データを取得
        $hatsubons = $this->getHatsubonData();
        $documents = DB::table('hatsubonlist_documents')
                    ->where('hatsubonlist_documents.jiin_id', '=', $userJiinId)
                    ->get();
        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($hatsubons as $hatsubon) {
            // 新しいページを追加
            $page = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($page);

            // 各位
            PostcardPrint::DocumentKakui($pdf, $f, $hatsubon->name, 10, 10, 18, 7);


            // documentsのデータを書き込む
            foreach ($documents as $document) {
                // 表題
                PostcardPrint::DocumentTitle($pdf, $f, $document->title, 85, 7, 22, 8);

                // 各位
                PostcardPrint::DocumentKakui($pdf, $f, $document->kakui, 10, 10, 18, 7);

                // 住所
                PostcardPrint::DocumentAddress($pdf, $f, $document->address, 17.7, 80, 12, 4.5);

                // 寺院名
                PostcardPrint::DocumentTempleName($pdf, $f, $document->templename, 10, 90, 18, 7);

                // 電話番号
                PostcardPrint::DocumentTel($pdf, $f, $document->tel, 4.8, 90, 11, 4);

                $kaimyou = $hatsubon->kaimyou;
                // 文書
                $backdocuments = ['　本年は貴家'. $kaimyou. '零位の初盆会にあたります。',
                                  $document->document2,
                                  $document->document3,
                                  $document->document4,
                                  $document->document5,
                                  $document->document6,
                                  $document->document7,
                                  $document->document8,
                                  $document->document9,
                                 ];

                PostcardPrint::DocumentDocument($pdf, $f, $backdocuments, 77, 10, 12, 4.2);
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonBackPrint_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
