<?php

namespace App\Http\Controllers;

use App\Models\Danka;
use App\Services\CommonUtility;
use App\Services\ListData;
use App\Services\PostcardPrint;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class HanamatsurilistController extends Controller
{
    /**
     * 一覧表示
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
        $query = Danka::query()
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->select('dankas.id',
                             'followers.name', 
                             'dankas.area',
                             'followers.postcode',
                             'followers.address1',
                             'followers.address2',
                             'followers.tel',
                             'dankas.gozikai',
                             'dankas.postcard')
                    ->where('dankas.hanamatsuri', '=', 1)
                    ->where('dankas.jiin_id', '=', $userJiinId)
                    ->where('chiefmourner_flg', '=', 1)
                    ->orderBy('dankas.area', 'asc')
                    ->orderBy('followers.namekana', 'asc');

        $hanamatsurilists = $query->paginate(10);

        // 該当件数表示
        $hanamatsuriCount = $hanamatsurilists->total();

        return view('hanamatsurilists.index', compact('hanamatsurilists', 'hanamatsuriCount'));
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
        $pdf = new Fpdi($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8');
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');
        
        $query  = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->where('dankas.hanamatsuri', '=', 1)
                    ->where('dankas.jiin_id', '=', $userJiinId)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderBy('followers.namekana', 'asc');

        $hanamatsurilists = $query->get();

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Hanamatsurilist.pdf';
        $templatePath = resource_path('template/Hanamatsurilist.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);
        
        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null ,null, null, true);

        $page = 1;

        $pdf->setFont($f, '', 11);
        $pdf->Text(185, 282.5, $page);

        $y = 0;
        $no = 0;
        $rowcnt = 0;
        foreach ($hanamatsurilists as $hanamatsurilist) {
            if ($rowcnt >= 30) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $page += 1;
                $pdf->setFont($f, '', 11);
                $pdf->Text(185, 282.5, $page);
                $y = 0;
            }

            $rowcnt += 1;
            // 文字を出力、位置指定(No)
            $pdf->setFont($f, '', 10.5);
            $pdf->text(12.5, 35.5 + $y, $no += 1);     

            // 文字を出力、位置指定(氏名)
            $pdf->setFont($f, '', 10.5);
            $pdf->text(21.5, 37 + $y, $hanamatsurilist->name);

            // 文字を出力、位置指定(氏名かな)
            $pdf->setFont($f, '', 7);
            $pdf->text(22, 34.2 + $y, $hanamatsurilist->namekana);

            // 文字を出力、位置指定(郵便番号)
            $pdf->setFont($f, '', 10.5);
            $pdf->text(58, 35.5 + $y, $hanamatsurilist->postcode);

            // 文字を出力、位置指定(住所)
            $pdf->setFont($f, '', 8.5);
            $pdf->MultiCell(67, 42, $hanamatsurilist->address1 . $hanamatsurilist->address2, 0, 'L', 0, 0, 79.5, 34.2 + $y);

            // 文字を出力、位置指定(電話番号)
            $pdf->setFont($f, '', 10.5);
            $pdf->text(146, 35.5 + $y, $hanamatsurilist->tel);
            
            // 文字を出力、位置指定(護持会)
            $pdf->setFont($f, '', 12);
            $pdf->Rect(187.5, 36 + $y, 4, 4); // チェックボックスの枠を描画
            if ($hanamatsurilist->gozikai == 1) {
                $pdf->text(186.5, 35.5 + $y, '✓'); // チェックマークを描画
            }
            $y += 8.135;
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // はがき印刷で表示するデータの取得
    public function getHanamatsuriData()
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $hanamatsurilist = collect();

        $query  = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->where('dankas.jiin_id', '=', $userJiinId)
                    ->where('dankas.hanamatsuri', '=', 1)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->where('dankas.postcard', '=', '出す')
                    ->orderBy('followers.namekana', 'asc');

        $hanamatsurilists = $hanamatsurilist->merge($query->get());

        return $hanamatsurilists;
    }
    public function postcard_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $hanamatsuris = $this->getHanamatsuriData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($hanamatsuris as $hanamatsuri) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $hanamatsuri->name. ' 様';
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $hanamatsuri->postcode;
            $text = str_replace('-', '', $hanamatsuri->postcode);
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
            $text = parse_number($hanamatsuri->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 25, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hanamatsuri->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);     
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $hanamatsuris = $this->getHanamatsuriData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hanamatsuris as $hanamatsuri) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hanamatsuri->name. ' 様';
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $hanamatsuri->postcode;
            $text = str_replace('-', '', $hanamatsuri->postcode);
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
            $text = parse_number($hanamatsuri->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hanamatsuri->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);   
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $hanamatsuris = $this->getHanamatsuriData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hanamatsuris as $hanamatsuri) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hanamatsuri->name. ' 様';
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $hanamatsuri->postcode;
            $text = str_replace('-', '', $hanamatsuri->postcode);
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
            $text = parse_number($hanamatsuri->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hanamatsuri->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 91, 30, 26);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $hanamatsuris = $this->getHanamatsuriData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hanamatsuris as $hanamatsuri) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hanamatsuri->name. ' 様';
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 50, 56);
            if (!is_null($hanamatsuri->postcode)) {
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
                $text = $hanamatsuri->postcode;
                $text = str_replace('―', '', $hanamatsuri->postcode);
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
            $text = parse_number($hanamatsuri->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hanamatsuri->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $hanamatsuris = $this->getHanamatsuriData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($hanamatsuris as $hanamatsuri) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $hanamatsuri->name. ' 様';
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);

            if (!is_null($hanamatsuri->postcode)) {
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
                $text = str_replace('―', '', $hanamatsuri->postcode);
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
            $text = parse_number($hanamatsuri->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($hanamatsuri->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $hanamatsuris = $this->getHanamatsuriData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($hanamatsuris as $index => $hanamatsuri) {
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
            $name = $hanamatsuri->name;
            $keishou = null;
            $postcode = $hanamatsuri->postcode;
            $address = ltrim($hanamatsuri->address1 . $hanamatsuri->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HanamatsuriLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
