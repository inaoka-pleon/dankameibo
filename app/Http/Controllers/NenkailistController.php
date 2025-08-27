<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Models\Kaiki;
use App\Models\NenkaiDocument;
use App\Models\TempleMaster;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\HttpKernel\Debug\VirtualRequestStack;
use TCPDF_FONTS;

class NenkailistController extends Controller
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
        if(strcmp($request->searchType, 'nenkailist_search') === 0) {
            $put_flg = true;
        }

        // 元号のデータを取得
        $eras = Era::query()
                    ->select('eras.id',
                             'eras.name',
                             'eras.years')
                    ->get();

        // 現在の日付を取得し、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraId = $currentEra['era_id'] ?? '';
        $currentEraYear = $currentEra['era_year'] ?? '';

        $nenkai_target_year = CommonUtility::GetQueryParameter($request, 'nenkai_target_year', $put_flg);
        
        // 初期表示時に現在年度を設定
        if (empty($nenkai_target_year['era'])) {
            $nenkai_target_year['era'] = $currentEraId;
        }
        if (empty($nenkai_target_year['year'])) {
            $nenkai_target_year['year'] = $currentEraYear;
        }

        $era = $nenkai_target_year['era'] ?? null;
        $year = $nenkai_target_year['year'] ?? null;

        // erasテーブルから元号の最大年数を取得
        $maxYear = DB::table('eras')
                ->where('id', $era)
                ->value('years');

        // バリデーション
        if ($year > $maxYear) {
            return redirect()->back()->withErrors(['nenkai_target_year' => '指定された元号の年数が無効です。']);
        }

        // 和暦を西暦に変換
        $search_era = null;
        if($era && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era, $year);
        }

        $query = Kaiki::query()
                    ->select('kaikis.id',
                             'kaikis.kaiki',
                             'kaikis.kaiki_name')
                    ->where('kaikis.kaiki_kbn', '=', 0)
                    ->where('kaikis.jiin_id', '=', $userJiinId)
                    ->where('kaikis.target_flg', '=', 1);

        $nenkailists = $query->get();

        foreach ($nenkailists as $nenkailist) {
            if ($nenkailist->kaiki == 1) {
                $deathYear = $search_era - $nenkailist->kaiki;
            } else {
                $deathYear = $search_era - $nenkailist->kaiki + 1;
            }
            $deathEra = CommonUtility::ADtoJACalendarConv($deathYear . '-01-01');
            $nenkailist->death_year = CommonUtility::parseNumber($deathEra['era_year']);
            $nenkailist->death_era_name = $deathEra['era_name'];
        }

        // フィルタリングしたデータをセッションに保存
        session(['nenkailists' => $nenkailists]);
        
        $request->session()->put('nenkai_target_year', $nenkai_target_year);

        return view('nenkailists.index', compact('nenkailists', 'eras', 'currentEraId', 'currentEraYear', 'currentEra'))
                    ->with('nenkai_target_year', $nenkai_target_year);
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

        $nenkai_target_year = $request->session()->get('nenkai_target_year', []);
        $nenkailists = session('nenkailists');

        $nenkaidocuments = NenkaiDocument::query()
                         ->get();

        $templemasters = TempleMaster::query()
                        ->get();

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'NenkaiList.pdf';
        $templatePath = resource_path('template/NenkaiList.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);

        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null, null, null, true);

        $page = 1;

        $pdf->setFont($f, '', 12);
        $pdf->Text(180, 282.5, $page);

        // 年度
        $pdf->setFont($f, '', 35);
        $era_id = $nenkai_target_year['era'] ?? null;
        $era = Era::find($era_id);
        $era_name = $era ? $era->name : '';
        $kanjiYear = CommonUtility::parseNumber($nenkai_target_year['year']);
        $text = $era_name . $kanjiYear . '年度年回表';
        $x = 265;
        $y = 48;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 12; // 文字の間隔を調整（上から下へ移動）
        }
        
        $no = 0;
        $x = 245;
        $y = 0;
        $rowcnt = 0;

        foreach ($nenkailists as $nenkailist) {
            if ($rowcnt >= 15) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $page += 1;
                $pdf->setFont($f, '', 11);
                $pdf->Text(180, 282.5, $page);
                $x = 280;
            }

            $rowcnt += 1;

            // 回忌名
            $pdf->setFont($f, '', 25);
            $text = $nenkailist->kaiki_name;
            $y = 30;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 10;
            }

            // 年数
            $pdf->setFont($f, '', 25);
            $text = $nenkailist->death_era_name . $nenkailist->death_year . '年亡';
            $y = 110;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 10;
            }
            $x -= 14;
        }

        // 2ページ目を追加して文書を表示
        $pdf->addPage();
        $page += 1;
        $pdf->setFont($f, '', 12);
        $pdf->Text(180, 282.5, $page);

        $pdf->setFont($f, '', 20);
        $x = 265;
        foreach ($nenkaidocuments as $nenkaidocument) {
            $documents = [
                $nenkaidocument->document1,
                $nenkaidocument->document2,
                $nenkaidocument->document3,
                $nenkaidocument->document4,
                $nenkaidocument->document5,
                $nenkaidocument->document6,
                $nenkaidocument->document7,
                $nenkaidocument->document8,
                $nenkaidocument->document9,
            ];
            
            foreach ($documents as $document) {
                if ($document) {
                    $pdf->setFont($f, '', 13);
                    $text = $document;
                    $y = 18;
                    foreach (mb_str_split($text) as $char) {
                        if ($char === '（' || $char === '）' || $char === '「' || $char === '」') {
                            $pdf->StartTransform();
                            $pdf->Rotate(270, $x + 3.1, $y + 3.1);
                            $pdf->Text($x, $y, $char);
                            $pdf->StopTransform();
                        } elseif ($char === '、' || $char === '。') {
                            $pdf->StartTransform();;
                            $pdf->Text($x + 3.1, $y - 3.1, $char);
                            $pdf->StopTransform();
                        } else {
                            $pdf->Text($x, $y, $char);
                        }
                    $y += 5.2;
                    }
                }
                $x -= 6;
            }
        }


        foreach ($templemasters as $templemaster)
        {
            // 山号
            $pdf->setFont($f, '', 16);
            $text = $templemaster->mountainname;
            $x = 200;
            $y = 105;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 11;
            }
            // 寺院名
            $pdf->setFont($f, '', 28);
            $text = $templemaster->templename;
            $x = 198;
            $y = 137;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 19;
            }
            // 住所
            $pdf->setFont($f, '', 16);
            $text = $templemaster->address1. $templemaster->address2;
            $x = 189.5;
            $y = 126;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 5.5;
            }
            // TEL
            $pdf->setFont($f, '', 12);
            $text = 'TEL';
            $x = 184.2;
            $y = 126.5;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 3.75;
            }
            // TEL
            $pdf->setFont($f, '', 12);
            $text = parse_number($templemaster->tel);
            $x = 183.5;
            $y = 139.5;

            foreach (mb_str_split($text) as $char) {
                if ($char === '-') {
                    $pdf->StartTransform();
                    $pdf->Rotate(270, $x + 2.4, $y + 3.8);
                    $pdf->Text($x, $y, $char);
                    $pdf->StopTransform();
                } else {
                    $pdf->Text($x, $y, $char);
                }
                $y += 3.9;
            }
            // FAX
            $pdf->setFont($f, '', 12);
            $text = 'FAX';
            $x = 179;
            $y = 126.5;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 3.75;
            }
            // FAX
            $pdf->setFont($f, '', 12);
            $text = parse_number($templemaster->fax);
            $x = 178.3;
            $y = 139.5;
            foreach (mb_str_split($text) as $char) {
                if ($char === '-') {
                    $pdf->StartTransform();
                    $pdf->Rotate(270, $x + 2.4, $y + 3.8);
                    $pdf->Text($x, $y, $char);
                    $pdf->StopTransform();
                } else {
                    $pdf->Text($x, $y, $char);
                }
                $y += 3.9;
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'NenkaiList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}