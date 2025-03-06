<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Danka;
use App\Services\CommonUtility;
use App\Services\ListData;
use App\Services\PostcardPrint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class GozikailistsController extends Controller
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
        if(strcmp($request->searchType, 'gozikailist_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                    ->where('key1', '=', 'AREA')
                    ->get();

        // 地区名検索パラメータ取得
        $cond_gozikailist = CommonUtility::GetQueryParameter($request, 'cond_gozikailist', $put_flg);

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
                    ->where('dankas.gozikai', '=', 1)
                    ->where('chiefmourner_flg', '=', 1)
                    ->orderBy('dankas.area', 'asc')
                    ->orderBy('followers.namekana', 'asc');

        if(!empty($cond_gozikailist['area'])) {
            $query->where('area', '=', $cond_gozikailist['area']);
        }
        
        $gozikailists = $query->paginate(10);

        // 該当件数表示
        $gozikaiCount = $gozikailists->total();

        return view('gozikailists.index', compact('gozikailists', 'areas', 'gozikaiCount'))
            ->with('cond_gozikailist', $cond_gozikailist);
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

         // 検索データを取得
         $put_flg = false;
         if(strcmp($request->searchType, 'gozikailist_search') === 0) {
             $put_flg = true;
         }
 
         $cond_gozikailist = CommonUtility::GetQueryParameter($request, 'cond_gozikailist', $put_flg);
     
         $keys = ListData::GetGozikaiListKey($cond_gozikailist);

         $print_flg = false;
         $hasResults = false;

         foreach ($keys as $key) {
             $query  = DB::table('dankas')
                         ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                         ->where('dankas.area', '=', $key->value1)
                         ->where('dankas.gozikai', '=', 1)
                         ->where('followers.chiefmourner_flg', '=', 1)
                         ->orderBy('dankas.area')
                         ->orderBy('followers.namekana', 'asc');
     
             if(!empty($cond_gozikailist['area'])) {
                 $query->where('area', '=', $cond_gozikailist['area']);
             }
     
             $gozikailists = $query->get();

             if($gozikailists->isEmpty()) {
                continue;
             }

             $hasResults = true;

             // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
             $tpl_name = 'Gozikailist.pdf';
             $templatePath = resource_path('template/Gozikailist.pdf');
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
             $gozikaiPrinted = false;
             foreach ($gozikailists as $gozikai) {
                 if ($rowcnt >= 30) {
                     $templateId = $pdf->importPage(1);
                     $pdf->AddPage();
                     $pdf->useTemplate($templateId, null, null, null, null, true);
                     $rowcnt = 0;
                     $page += 1;
                     $pdf->setFont($f, '', 11);
                     $pdf->Text(180, 282.5, $page);
                     $y = 0;
                     $gozikaiPrinted = false;
                 }

                 $rowcnt += 1;
                 if (!$gozikaiPrinted) {
                    // 文字を出力、位置指定(地 区名)
                    $pdf->setFont($f, '', 20);
                    $pdf->text(112, 9, $gozikai->area);
                    $gozikaiPrinted = true;
                 }


                 // 文字を出力、位置指定(No)
                 $pdf->setFont($f, '', 10.5);
                 $pdf->text(12.5, 35.5 + $y, $no += 1);     

                 // 文字を出力、位置指定(氏名)
                 $pdf->setFont($f, '', 10.5);
                 $pdf->text(21.5, 37 + $y, $gozikai->name);

                 // 文字を出力、位置指定(氏名かな)
                 $pdf->setFont($f, '', 7);
                 $pdf->text(22, 34.2 + $y, $gozikai->namekana);

                 // 文字を出力、位置指定(檀家)
                 $pdf->setFont($f, '', 10.5);
                 $pdf->text(58, 35.5 + $y, $gozikai->dankadivision);

                 // 文字を出力、位置指定(郵便番号)
                 $pdf->setFont($f, '', 10.5);
                 $pdf->text(72.5, 35.5 + $y, $gozikai->postcode);

                 // 文字を出力、位置指定(住所)
                 $pdf->setFont($f, '', 8.5);
                 $pdf->MultiCell(67, 42, $gozikai->address1 . $gozikai->address2, 0, 'L', 0, 0, 94, 34 + $y);

                 // 文字を出力、位置指定(電話番号)
                 $pdf->setFont($f, '', 10.5);
                 $pdf->text(161,  35.5 + $y, $gozikai->tel);
                 $y += 8.135;
             }   
        }
     
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // はがき印刷で表示するデータの取得
    public function getGozikaiData(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'gozikailist_search') === 0) {
            $put_flg = true;
        }

        $cond_gozikailist = CommonUtility::GetQueryParameter($request, 'cond_gozikailist', $put_flg);
        $keys = ListData::GetGozikaiListKey($cond_gozikailist);

        $gozikailist = collect();

        foreach ($keys as $key) {
            $query = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->where('dankas.gozikai', '=', 1)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->where('dankas.postcard', '=', '出す')
                    ->orderBy('dankas.area')
                    ->orderBy('followers.namekana', 'asc');

            if (!empty($cond_gozikailist['area'])) {
                $query->where('dankas.area', '=', $cond_gozikailist['area']);
            }

            $gozikailists = $gozikailist->merge($query->get());
        }

        return $gozikailists;
    }
    public function postcard_print(Request $request)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $gozikailists = $this->getGozikaiData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($gozikailists as $gozikailist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $gozikailist->name. ' 様';
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $gozikailist->postcode;
            $text = str_replace('-', '', $gozikailist->postcode);
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
            $text = parse_number($gozikailist->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 25, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($gozikailist->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);     
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $gozikailists = $this->getGozikaiData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($gozikailists as $gozikailist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $gozikailist->name. ' 様';
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $gozikailist->postcode;
            $text = str_replace('-', '', $gozikailist->postcode);
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
            $text = parse_number($gozikailist->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($gozikailist->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);   
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $gozikailists = $this->getGozikaiData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($gozikailists as $gozikailist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $gozikailist->name. ' 様';
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $gozikailist->postcode;
            $text = str_replace('-', '', $gozikailist->postcode);
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
            $text = parse_number($gozikailist->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($gozikailist->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 90, 30, 26);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $gozikailists = $this->getGozikaiData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($gozikailists as $gozikailist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $gozikailist->name. ' 様';
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 50, 56);

            if (!is_null($gozikailist->postcode)) {
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
                $text = $gozikailist->postcode;
                $text = str_replace('―', '', $gozikailist->postcode);
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
            $text = parse_number($gozikailist->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($gozikailist->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $gozikailists = $this->getGozikaiData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($gozikailists as $gozikailist) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $gozikailist->name. ' 様';
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);

            if (!is_null($gozikailist->postcode)) {
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
                $text = str_replace('-', '', $gozikailist->postcode);
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
            $text = parse_number($gozikailist->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($gozikailist->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $gozikailists = $this->getGozikaiData($request);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($gozikailists as $index => $gozikailist) {
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
            $name = $gozikailist->name;
            $keishou = null;
            $postcode = $gozikailist->postcode;
            $address = ltrim($gozikailist->address1 . $gozikailist->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'GozikaiLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
