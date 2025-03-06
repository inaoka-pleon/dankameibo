<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Temple;
use App\Models\Templelist;
use App\Services\CommonUtility;
use App\Services\ListData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use setasign\Fpdi\PdfReader\Page;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class TemplelistController extends Controller
{
    /**一覧表示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if(strcmp($request->searchType, 'templelist_search') === 0) {
            $put_flg = true;
        }

        // 宗務所のデータを取得
        $templeoffices = Code::query()
                            ->where('key1', '=', 'TEMPLEOFFICE')
                            ->get();
        
        // 一覧検索パラメータ取得
        $cond_templelist = CommonUtility::GetQueryParameter($request, 'cond_templelist', $put_flg);

        $query = Temple::query()
                        ->join('members', 'temples.id', '=', 'members.temple_id')
                        ->select('temples.id',
                                    'temples.templeoffice',
                                    'temples.parish',
                                    'temples.no',
                                    'members.postcode',
                                    'temples.templename',
                                    'members.qualification',
                                    'members.name',
                                    'members.tel',
                                    'members.address1',
                                    'members.address2')
                        ->where('chiefpriest_flg', '=', 1);

        if(!empty($cond_templelist['templeoffice'])){
            $query->where('templeoffice', '=', $cond_templelist['templeoffice']);
        }

        if(!empty($cond_templelist['parish'])) {
            $query->where('parish', '=', $cond_templelist['parish']);
        }

        $templelists = $query->paginate(10);

        return view('templelists.index', compact('templelists', 'templeoffices'))
            ->with('cond_templelist', $cond_templelist);
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
        // try{
            // FPDIインスタンス生成
            $pdf = new Fpdi($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8');
            // ページ設定（最初に設定しないとヘッダに罫線が入ってしまう）
            $pdf->setAutoPageBreak(false);
            $pdf->setTopMargin(0);
            $pdf->setPrintHeader(false);
            $pdf->setFooterMargin(0);
            $pdf->setPrintFooter(false);

            $font = new TCPDF_FONTS();
            $f = $font->addTTFfont('./fonts/ipaexm.ttf');

            // 検索結果データを取得
            $put_flg = false;
            if(strcmp($request->searchType, 'templelist_search') === 0) {
                $put_flg = true;
            }

            $cond_templelist = CommonUtility::GetQueryParameter($request, 'cond_templelist', $put_flg);
            
            $keys = ListData::GetTempleListKey($cond_templelist);

            $print_flg = false;

            foreach ($keys as $key) {
                $print_flg = true;

                $query = DB::table('temples')
                            ->join('members', 'temples.id', '=', 'members.temple_id')
                            ->where('temples.templeoffice', '=', $key->value1)
                            ->where('members.chiefpriest_flg',  '=',  1)
                            ->orderBy('temples.no');
                
                if(!empty($cond_templelist['templeoffice'])) {
                    $query->where('templeoffice', '=', $cond_templelist['templeoffice']);
                }

                if(!empty($cond_templelist['parish'])) {
                    $query->where('parish', '=', $cond_templelist['parish']);
                }

                $temples = $query->get();

                // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す)
                $tpl_name = 'Templelist.pdf';
                $templatePath = resource_path('template/Templelist.pdf');
                $pdf->setSourceFile($templatePath);

                // 教区ごとにページを分ける
                $currentParish = null;
                $page = 1;
                $rowcnt = 0;
                $y = 0;

                foreach ($temples as $temple) {
                    // 宗務所または教区が変わった場合、新しいページを追加
                    if ($currentParish !== $temple->parish) {
                        $currentParish = $temple->parish;
                        $templateId = $pdf->importPage(1);
                        $pdf->AddPage();
                        $pdf->useTemplate($templateId, null, null, null, null, true);
                        $pdf->setFont($f, '', 11);
                        $pdf->Text(185, 283.5, $page);
                        $y = 0;
                        $rowcnt = 0; 
                    }

                    if ($rowcnt >= 28) {
                        $templateId = $pdf->importPage(1);
                        $pdf->addpage();
                        $pdf->useTemplate($templateId, null, null, null, null, true);
                        $rowcnt = 0;
                        $page += 1;
                        $pdf->setFont($f, '', 11);
                        $pdf->Text(185, 283.5, $page);
                        $y = 0;
                    }

                    $rowcnt += 1;
                    // 文字を出力、位置指定(宗務所)
                    $pdf->setFont($f, '', 17);
                    $pdf->text(11, 25, '第 ' .$temple->parish. ' 教区');

                    // 文字を出力、位置指定(教区)
                    $pdf->setFont($f, '', 17);
                    $pdf->text(35, 25, '（' .$temple->templeoffice .'）');

                    // 現在日付（和暦）
                    $currentDate = Carbon::now();
                    if(!is_null($currentDate)) {
                        $target_date = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
                        $era_name = $target_date['era_name'] ?? '';
                        $era_year = $target_date['era_year'] ?? '';
                        $month = $target_date['month'] ?? '';
                        $day = $target_date['day'] ?? '';
                        $weekday = $currentDate->isoFormat('dddd');
                    } else {
                        $era_name = '';
                        $era_year = '';
                        $month = '';
                        $day = '';
                        $weekday = '';
                    }
                    $pdf->setFont($f, '', 9.5);
                    $pdf->Text(157, 20, $era_name . $era_year . '年' . $month . '月' . $day . '日'. $weekday);

                    // 文字を出力、位置指定(寺籍)
                    $pdf->setFont($f, '', 9.5);
                    $pdf->text(11, 44.5 + $y, $temple->no);     

                    // 文字を出力、位置指定(寺院名)
                    $pdf->setFont($f, '', 10);
                    $pdf->text(19, 44.5 + $y, $temple->templename);

                    // 文字を出力、位置指定(氏名)
                    $pdf->setFont($f, '', 10.5);
                    $pdf->text(36, 44.5 + $y, $temple->name);

                    // 文字を出力、位置指定(電話番号)
                    $pdf->setFont($f, '', 10.5);
                    $pdf->text(72, 44.5 + $y, $temple->tel);

                    // 文字を出力、位置指定(郵便番号)
                    $pdf->setFont($f, '', 10.5);
                    $pdf->text(109, 44.5 + $y, $temple->postcode);

                    // 文字を出力、位置指定(住所)
                    $pdf->setFont($f, '', 8.5);
                    $pdf->MultiCell(67, 42, $temple->address1 . $temple->address2, 0, 'L', 0, 0, 133, 43 + $y);
                    $y += 8.2;
                }
            }
                // PDFをブラウザに出力
                $pdf->Output('output.pdf', 'I');

    }
}
