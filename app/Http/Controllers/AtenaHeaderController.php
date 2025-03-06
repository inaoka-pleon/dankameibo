<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtenaHeaderRequest;
use App\Models\AtenaDetail;
use App\Models\AtenaHeader;
use App\Models\Code;
use App\Models\Danka;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AtenaHeaderController extends Controller
{
    /**
     * 宛名印刷一覧表示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = AtenaHeader::query()
                        ->select('atena_headers.id',
                                 'atena_headers.title',
                                 'atena_headers.created_at')
                        ->orderBy('atena_headers.created_at', 'asc');

        $atena_headers = $query->get();

        // 作成日を西暦から和暦に変換
        foreach ($atena_headers as $atena_header) {
            $created_at_date = Carbon::parse($atena_header->created_at)->format('Y-m-d');
            $CreatedEra = CommonUtility::ADtoJACalendarConv($created_at_date);
    
            $atena_header->CreatedEraName = $CreatedEra['era_name'] ?? '';
            $atena_header->CreatedEraYear = $CreatedEra['era_year'] ?? '';
            $atena_header->CreatedMonth = $CreatedEra['month'] ?? '';
            $atena_header->CreatedDay = $CreatedEra['day'] ?? '';
        }

        return view('atenaheaders.index', compact('atena_headers'));
        //
    }

    /**
     * 新規登録
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('atenaheaders.create');
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AtenaHeaderRequest $request)
    {
        DB::beginTransaction();

        try{
            $atena_header = new AtenaHeader;

            $atena_header->title = $request->input('title');

            $atena_header->save();

            $atena_detail = new AtenaDetail();

            $atena_detail->atena_header_id = $atena_header->id;
            $atena_detail->save();

            DB::commit();
            session()->flash('success', '宛名印刷情報を登録しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '宛名印刷情報を登録できませんでした。');
            return redirect()->route('atenaheader.index');
        };

        return redirect()->route('atenaheader.show', $atena_header->id);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $atena_headers = AtenaHeader::query()
                ->select('atena_headers.id',
                        'atena_headers.title')
                ->where('atena_headers.id', '=', $id)
                ->first();

        $query = AtenaDetail::query()
            ->select('atena_details.id as atena_detail_id',
                     'atena_details.name',
                     'atena_details.keishou',
                     'atena_details.postcode',
                     'atena_details.address1',
                     'atena_details.address2',
                     'atena_details.postcard',
                     'atena_details.atena_header_id')
            ->where('atena_details.atena_header_id', '=', $id)
            ->orderby('atena_details.namekana', 'asc');

        $atena_details = $query->paginate(10);

        // 該当件数表示
        $atenaCount = $atena_details->total();

        return view('atenaheaders.show', compact('atena_headers', 'atena_details', 'atenaCount'))
            ->with('atena_header_id', $id);
        //
    }

    /**
     * 詳細画面/編集
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * 更新
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
    // はがき印刷で表示するデータの取得
    public function getAtenaData($id)
    {
        $atena = collect();

        $query  = DB::table('atena_headers')
                ->leftJoin('atena_details', 'atena_headers.id', '=', 'atena_details.atena_header_id')
                ->where('atena_details.atena_header_id', '=', $id)
                ->where('atena_details.postcard', '=', '出す')
                ->orderBy('atena_details.namekana', 'asc');
        $atenas = $atena->merge($query->get());
        return $atenas;
    }
    public function postcard_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $atenas = $this->getAtenaData($id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($atenas as $atena) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $atena->name. ' '. $atena->keishou;
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $atena->postcode;
            $text = str_replace('-', '', $atena->postcode);
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
            $text = parse_number($atena->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 25, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($atena->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AtenaPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function envelope4_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createEnvelope4Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $atenas = $this->getAtenaData($id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($atenas as $atena) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $atena->name. ' '. $atena->keishou;
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $atena->postcode;
            $text = str_replace('-', '', $atena->postcode);
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
            $text = parse_number($atena->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($atena->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AtenaEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function envelope3_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createEnvelope3Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $atenas = $this->getAtenaData($id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($atenas as $atena) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $atena->name. ' '. $atena->keishou;
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $atena->postcode;
            $text = str_replace('-', '', $atena->postcode);
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
            $text = parse_number($atena->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($atena->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 90, 30, 26);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AtenaEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function square3_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createSquare3Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $atenas = $this->getAtenaData($id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($atenas as $atena) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $atena->name. ' '. $atena->keishou;
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 50, 56);

            if (!is_null($atena->postcode)) {
                // 〒
                $pdf->setFont($f, '', 15);
                $pdf->Text(136.5, 15, '〒');
            
                // ハイフン
                $pdf->setFont($f, '', 11);
                $pdf->Text(166.25, 16, '―');
            
                // 文字を出力、位置指定(郵便番号)
                $text = str_replace('-', '', $atena->postcode);
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
            $text = parse_number($atena->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($atena->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AtenaSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function square2_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createSquare2Instance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $atenas = $this->getAtenaData($id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($atenas as $atena) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $atena->name. ' '. $atena->keishou;
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);

            if (!is_null($atena->postcode)) {
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
                $text = str_replace('-', '', $atena->postcode);
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
            $text = parse_number($atena->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($atena->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AtenaSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // ラベル
    public function label_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createLabelInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $atenas = $this->getAtenaData($id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($atenas as $index => $atena) {
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
            $name = $atena->name;
            $keishou = $atena->keishou;
            $postcode = $atena->postcode;
            $address = ltrim($atena->address1 . $atena->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AtenaLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
