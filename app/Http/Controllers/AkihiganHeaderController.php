<?php

namespace App\Http\Controllers;

use App\Http\Requests\AkihiganHeaderRequest;
use App\Models\AkihiganDetail;
use App\Models\AkihiganHeader;
use App\Models\Code;
use App\Models\Danka;
use App\Models\Era;
use App\Services\CommonUtility;
use App\Services\ListData;
use App\Services\PostcardPrint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AkihiganHeaderController extends Controller
{
    /**
     * 秋彼岸一覧表示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = AkihiganHeader::query()
                            ->select('akihigan_headers.id',
                                     'akihigan_headers.era',
                                     'akihigan_headers.year',
                                     'akihigan_headers.created_at');

        $akihigan_headers = $query->get();

        // 作成日を西暦から和暦に変換
        foreach ($akihigan_headers as $akihigan_header) {
            $created_at_date = Carbon::parse($akihigan_header->created_at)->format('Y-m-d');
            $CreatedEra = CommonUtility::ADtoJACalendarConv($created_at_date);
    
            $akihigan_header->CreatedEraName = $CreatedEra['era_name'] ?? '';
            $akihigan_header->CreatedEraYear = $CreatedEra['era_year'] ?? '';
            $akihigan_header->CreatedMonth = $CreatedEra['month'] ?? '';
            $akihigan_header->CreatedDay = $CreatedEra['day'] ?? '';

            $akihigan_header->ad_year = CommonUtility::JAtoADCalendarEraNameConv($akihigan_header->era, $akihigan_header->year);
        }

        $akihigan_orderbys = $akihigan_headers->sortByDesc(function ($akihigan_header) {
            return $akihigan_header->ad_year;
        });
        return view('akihiganheaders.index', compact('akihigan_headers', 'akihigan_orderbys'));
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
        $eras = Era::query()
                    ->orderBy('eras.ad_start', 'desc')
                    ->get();

        // 現在の日付を取得して、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraYear = $currentEra['era_year'] ?? '';

        return view('akihiganheaders.create', compact('eras', 'currentEraYear'));
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AkihiganHeaderRequest $request)
    {
        $era = $request->input('era');
        $year = $request->input('year');

        // erasテーブルから元号の最大年数を取得
        $maxYear = DB::table('eras')
                ->where('name', $era)
                ->value('years');

        if ($year> $maxYear) {
            return redirect()->back()->withErrors(['year' => '指定された元号の年数が無効です。']);
        }
        
        DB::beginTransaction();

        try{
            // 同じ年度の登録があるか確認
            $year_check = AkihiganHeader::where('era', $request->input('era'))
                                        ->where('year', $request->input('year'))
                                        ->first();

            if ($year_check) {
                session()->flash('info', '既に登録されています。');
                return redirect()->route('akihiganheader.index');
            }
            $query = DB::table('dankas')
                    ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                    ->select('dankas.id',
                            'followers.name',
                            'followers.namekana',
                            'followers.postcode',
                            'followers.address1',
                            'followers.address2',
                            'followers.tel')
                    ->where('dankas.akihigan', '=', 1)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->where('dankas.postcard', '=', '出す');
            
            $dankas = $query->get();

            $akihigan_header = new AkihiganHeader();

            $akihigan_header->era = $request->input('era');
            $akihigan_header->year = $request->input('year');

            $akihigan_header->save();

            foreach ($dankas as $danka){
                $akihigan_detail = new AkihiganDetail();

                $akihigan_detail->danka_id = $danka->id;
                $akihigan_detail->name = $danka->name;
                $akihigan_detail->namekana = $danka->namekana;
                $akihigan_detail->postcode = $danka->postcode;
                $akihigan_detail->address1 = $danka->address1;
                $akihigan_detail->address2 = $danka->address2;
                $akihigan_detail->tel = $danka->tel;
                $akihigan_detail->akihigan_header_id = $akihigan_header->id;
                $akihigan_detail->save();
            }
            DB::commit();
            session()->flash('success', '秋彼岸一覧情報を登録しました。');

        }catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '秋彼岸一覧情報を登録できませんでした。');
        };

        return redirect()->route('akihiganheader.index');
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
     * 詳細画面/編集
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $put_flg = false;
        if(strcmp($request->searchType, 'akihigan_header_search') === 0) {
            $put_flg = true;
        }

        // 担当者のデータ取得
        $managers = Code::query()
                        ->where('key1', '=', 'MANAGER')
                        ->get();

        // 一覧検索パラメータ取得
        $cond_akihigan_header = CommonUtility::GetQueryParameter($request, 'cond_akihigan_header', $put_flg);
                            
        $akihigan_headers = AkihiganHeader::query()
                                ->select('akihigan_headers.id',
                                         'akihigan_headers.era',
                                         'akihigan_headers.year')
                                ->where('akihigan_headers.id', '=', $id)
                                ->first();

        $query = AkihiganDetail::query()
                    ->join('followers', 'akihigan_details.danka_id', '=', 'followers.danka_id')
                    ->select('akihigan_details.id as akihigan_detail_id',
                             'akihigan_details.name',
                             'akihigan_details.namekana',
                             'akihigan_details.postcode',
                             'akihigan_details.address1',
                             'akihigan_details.address2',
                             'akihigan_details.tel',
                             'akihigan_details.month',
                             'akihigan_details.day',
                             'akihigan_details.ampm',
                             'akihigan_details.hour',
                             'akihigan_details.minute',
                             'akihigan_details.manager')
                    ->where('akihigan_details.akihigan_header_id', '=', $id)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderby('akihigan_details.manager', 'asc')
                    ->orderby('akihigan_details.namekana', 'asc');
        
        if(!empty($cond_akihigan_header['manager'])){
            $query->where('manager', '=', $cond_akihigan_header['manager']);
        }

        $akihigan_details = $query->paginate(10);

        // 該当件数表示
        $akihiganCount = $akihigan_details->total();

        // IDをセッションに保存
        session(['akihigan_id' => $id]);

        return view('akihiganheaders.edit', compact('akihigan_headers', 'managers', 'akihigan_details', 'akihiganCount'))
            ->with('cond_akihigan_header', $cond_akihigan_header)
            ->with('akihigan_header_id', $id);
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
    public function update(AkihiganHeaderRequest $request, $id)
    {
        DB::beginTransaction();
        $akihigan_cnt = DB::table('akihigan_details')->where('akihigan_header_id', '=', $id)->count();
        try {   
            for($i = 1; $i <= $akihigan_cnt; $i++) {
                $akihigan_detail_id = $request->input('akihigan_detail_id_'.$i);
                $akihigan_detail = AkihiganDetail::find($akihigan_detail_id);
                $akihigan_detail->month = $request->input('month_'.$i);
                $akihigan_detail->day = $request->input('day_'.$i);
                $akihigan_detail->ampm = $request->input('ampm_'.$i);
                $akihigan_detail->hour = $request->input('hour_'.$i);
                $akihigan_detail->minute = $request->input('minute_'.$i);
                $akihigan_detail->manager = $request->input('manager_'.$i);

                $akihigan_detail->save();
            }
            DB::commit();
            session()->flash('success', '秋彼岸情報を変更しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '秋彼岸情報を変更できませんでした。');

        }
        return redirect()->route('akihiganheader.edit', $id);
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

    // 檀信徒検索
    public function danka_search(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'akihigan_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                ->where('key1', '=', 'AREA')
                ->get();

        //一覧検索パラメータ取得
        $cond_akihigan = CommonUtility::GetQueryParameter($request, 'cond_akihigan', $put_flg);

        $akihigan_headers = AkihiganHeader::query()
                            ->select('akihigan_headers.id',
                                     'akihigan_headers.era',
                                     'akihigan_headers.year')
                            ->where('akihigan_headers.id', '=', $id)
                            ->first();

        $query = Danka::query()
                ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                ->select('dankas.id', 
                         'followers.name',
                         'followers.namekana',
                         'followers.postcode',
                         'followers.address1',
                         'followers.address2',
                         'followers.tel',
                         'followers.chiefmourner_flg',
                         'followers.deceased_flg',
                         'followers.gender',
                         'dankas.area')
                ->where('followers.chiefmourner_flg', '=', 1)
                ->where('followers.deceased_flg', '=', 0)
                ->orderby('followers.namekana', 'asc');

        if(!empty($cond_akihigan['area'])) {
            $query->where('area', '=', $cond_akihigan['area']);
            } 
            if(!empty($cond_akihigan['name'])) {
            $query->where('name', 'like', '%'.$cond_akihigan['name'].'%');
            }
            if (!empty($cond_akihigan['namekana'])) {
            $query->where('namekana', 'like', '%'.$cond_akihigan['namekana'].'%');
            }

        $danka_searchs = $query->get();

        // 該当件数表示
        $dankaSearchCount = $danka_searchs->count();

        return view('akihiganheaders.danka_search', ['id' => $id], compact('akihigan_headers', 'areas', 'danka_searchs', 'dankaSearchCount'))
            ->with('akihigan_header_id', $id)
            ->with('cond_akihigan', $cond_akihigan);
    }
    // 檀信徒追加
    public function add_danka_data(Request $request, $id)
    {
        $selectedDankas = $request->input('selected_dankas');

        foreach ($selectedDankas as $selectedDanka) {
            $danka = Danka::with('followers')->find($selectedDanka);
            if ($danka && $danka->followers->isNotEmpty()) {
                $follower = $danka->followers->first();
                $akihigan_detail = new AkihiganDetail();
                $akihigan_detail->akihigan_header_id = $id;
                $akihigan_detail->danka_id = $danka->id;
                $akihigan_detail->name = $follower->name;
                $akihigan_detail->namekana = $follower->namekana;
                $akihigan_detail->address1 = $follower->address1;
                $akihigan_detail->address2 = $follower->address2;
                $akihigan_detail->tel = $follower->tel;
                $akihigan_detail->month = '9';

                $akihigan_detail->save();
            }
        }
        return redirect()->route('akihiganheader.edit', $id);
    }

    public function print(Request $request, $id)
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
    
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $templatePath = resource_path('template/Akihiganheader.pdf');
        $pdf->setSourceFile($templatePath);
    
        // 検索データを取得
        $put_flg = false;
        if(strcmp($request->searchType, 'akihigan_header_search') === 0) {
            $put_flg = true;
        }
    
        $cond_akihigan_header = CommonUtility::GetQueryParameter($request, 'cond_akihigan_header', $put_flg);
        
        if(!empty($cond_akihigan_header['manager'])) {
            // 検索条件に該当するデータを取得
            $akihigan_headers = $this->getAkihiganHeaders($id, $cond_akihigan_header['manager'], $cond_akihigan_header);

            if(!$akihigan_headers->isEmpty()) {
                $this->addAkihiganHeadersToPdf($pdf, $akihigan_headers, $f);
            }
        } else {
            // nullのデータを最初に取得
            $akihigan_headers_null = $this->getAkihiganHeaders($id, null, $cond_akihigan_header);

            if(!$akihigan_headers_null->isEmpty()) {
                $this->addAkihiganHeadersToPdf($pdf, $akihigan_headers_null, $f);
            }

            // データを取得
            $keys = ListData::GetAkihiganHeaderKey($cond_akihigan_header);
            foreach ($keys as $key) {
                $akihigan_headers = $this->getAkihiganHeaders($id, $key->value1, $cond_akihigan_header);

                if($akihigan_headers->isEmpty()) {
                    continue;
                }

                $this->addAkihiganHeadersToPdf($pdf, $akihigan_headers, $f);
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    
    private function getAkihiganHeaders($id, $manager, $cond_akihigan_header)
    {
        $query = DB::table('akihigan_headers')
                   ->join('akihigan_details', 'akihigan_headers.id', '=', 'akihigan_details.akihigan_header_id')
                   ->join('followers', 'akihigan_details.danka_id', '=', 'followers.danka_id')
                   ->where('akihigan_details.akihigan_header_id', '=', $id)
                   ->where('followers.chiefmourner_flg', '=', 1)
                   ->orderBy('followers.namekana', 'asc');
    
        if (is_null($manager)) {
            $query->whereNull('akihigan_details.manager');
        } else {
            $query->where('akihigan_details.manager', '=', $manager);
            if(!empty($cond_akihigan_header['manager'])) {
                $query->where('manager', '=', $cond_akihigan_header['manager']);
            }
        }
    
        return $query->get();
    }
    
    private function addAkihiganHeadersToPdf($pdf, $akihigan_headers, $font)
    {
        $templateId = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);
    
        $pdf->setFont($font, '', 11);
        $y = 0;
        $no = 0;
        $rowcnt = 0;
        $akihiganPrinted = false;
    
        foreach ($akihigan_headers as $akihigan_header) {
            if ($rowcnt >= 30) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $pdf->setFont($font, '', 11);
                $y = 0;
                $akihiganPrinted = false;
            }
    
            $rowcnt += 1;
            if (!$akihiganPrinted) {
                // 文字を出力、位置指定(元号)
                $pdf->setFont($font, '', 12);
                $pdf->text(10, 13, $akihigan_header->era);
        
                // 文字を出力、位置指定(年度)
                $pdf->setFont($font, '', 12);
                $pdf->text(20, 13, $akihigan_header->year . '年');
        
                $pdf->setFont($font, '', 13);
                $pdf->text(10, 25, '担当者 : '. $akihigan_header->manager);
                $akihiganPrinted = true;
            }

    
            // 文字を出力、位置指定(No)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(7, 42.8 + $y, $no += 1);     
    
            // 文字を出力、位置指定(氏名)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(16, 44 + $y, $akihigan_header->name);
    
            // 文字を出力、位置指定(氏名かな)
            $pdf->setFont($font, '', 7);
            $pdf->text(16.5, 41.5 + $y, $akihigan_header->namekana);
    
            // 文字を出力、位置指定(住所)
            $pdf->setFont($font, '', 8.5);
            $pdf->MultiCell(66, 42, $akihigan_header->address1 . $akihigan_header->address2, 0, 'L', 0, 0, 52.5, 41.5 + $y);
    
            // 文字を出力、位置指定(電話番号)
            $pdf->setFont($font, '', 10);
            $pdf->text(118.5, 42.8 + $y, $akihigan_header->tel);
    
            // 文字を出力、位置指定(月)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(155, 42.8 + $y, $akihigan_header->month);
    
            // 文字を出力、位置指定(月)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(160, 42.8 + $y, '月');
            
            // 文字を出力、位置指定(日)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(167, 42.8 + $y, $akihigan_header->day);
    
            // 文字を出力、位置指定(日)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(172, 42.8 + $y, '日');
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(178, 42.8 + $y, $akihigan_header->ampm);
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(187.5, 42.8 + $y, $akihigan_header->hour);
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(192, 42.5 + $y, '：');
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(195.5, 42.8 + $y, $akihigan_header->minute);

            $y += 8.135;
        }
    }
    // 秋彼岸データ取得
    public function getAkihiganData(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'akihigan_header_search') === 0) {
            $put_flg = true;
        }

        $cond_akihigan_header = CommonUtility::GetQueryParameter($request, 'cond_akihigan_header', $put_flg);

        // 検索条件が空の場合のデフォルト設定
        if (empty($cond_akihigan_header)) {
            $cond_akihigan_header = [
                'manager' => null,
            ];
        }

        $keys = ListData::GetAkihiganHeaderKey($cond_akihigan_header);

        $akihigan_header = collect();

        foreach ($keys as $key) {
            $query = DB::table('akihigan_headers') 
                        ->leftJoin('akihigan_details', 'akihigan_headers.id', '=', 'akihigan_details.akihigan_header_id')
                        ->leftJoin('followers', 'akihigan_details.danka_id', '=', 'followers.danka_id')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('akihigan_details.id as akihigan_detail_id',
                                 'akihigan_details.name',
                                 'akihigan_details.namekana',
                                 'akihigan_details.postcode',
                                 'akihigan_details.address1',
                                 'akihigan_details.address2',
                                 'akihigan_details.manager')
                        ->where('akihigan_details.akihigan_header_id', '=', $id)
                        // ->where('dankas.postcard', '=', '出す')
                        ->where('followers.chiefmourner_flg', '=', 1)
                        ->orderBy('akihigan_details.manager')
                        ->orderBy('akihigan_details.namekana', 'asc')
                        ->distinct();

            if (!empty($cond_akihigan_header['manager'])) {
                $query->where('manager', '=', $cond_akihigan_header['manager']);
            }

            $akihigan_headers = $akihigan_header->merge($query->get());
        }

        return $akihigan_headers;
    }
    public function postcard_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $akihigans = $this->getAkihiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($akihigans as $akihigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $akihigan->name. ' 様';
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $akihigan->postcode;
            $text = str_replace('-', '', $akihigan->postcode);
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
            $text = parse_number($akihigan->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 25, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($akihigan->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);     
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $akihigans = $this->getAkihiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($akihigans as $akihigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $akihigan->name. ' 様';
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $akihigan->postcode;
            $text = str_replace('-', '', $akihigan->postcode);
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
            $text = parse_number($akihigan->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($akihigan->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);   
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $akihigans = $this->getAkihiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($akihigans as $akihigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $akihigan->name. ' 様';
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $akihigan->postcode;
            $text = str_replace('-', '', $akihigan->postcode);
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
            $text = parse_number($akihigan->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($akihigan->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 90, 30, 26);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $akihigans = $this->getAkihiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($akihigans as $akihigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $akihigan->name. ' 様';
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 50, 56);
            if (!is_null($akihigan->postcode)) {
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
                $text = $akihigan->postcode;
                $text = str_replace('―', '', $akihigan->postcode);
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
            $text = parse_number($akihigan->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($akihigan->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $akihigans = $this->getAkihiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($akihigans as $akihigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $akihigan->name. ' 様';
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);
            if (!is_null($akihigan->postcode)) {
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
                $text = str_replace('-', '', $akihigan->postcode);
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
            $text = parse_number($akihigan->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($akihigan->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $akihigans = $this->getAkihiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($akihigans as $index => $akihigan) {
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
            $name = $akihigan->name;
            $keishou = null;
            $postcode = $akihigan->postcode;
            $address = ltrim($akihigan->address1 . $akihigan->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    public function back_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();
        
        // データを取得
        $akihigans = $this->getAkihiganBackData($request, $id);
        $documents = DB::table('akihigan_documents')->get();

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($akihigans as $akihigan) {
            // 新しいページを追加
            $page = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($page);

            // 各位
            PostcardPrint::DocumentKakui($pdf, $f, $akihigan->name. ' 様', 10, 10, 18, 7);

            // 月
            PostcardPrint::DocumentDate($pdf, $f, $akihigan->month, 47, 14.2, 12, 4.2);
            // 日
            PostcardPrint::DocumentDate($pdf, $f, $akihigan->day, 47, 31, 12, 4.2);

            // 午前午後
            PostcardPrint::DocumentKakui($pdf, $f, $akihigan->ampm, 47, 52, 12, 4.2);

            // documentsのデータを書き込む
            foreach ($documents as $document) {
                // 表題
                PostcardPrint::DocumentTitle($pdf, $f, $document->title, 85, 7, 22, 8);

                // 住所
                PostcardPrint::DocumentAddress($pdf, $f, $document->address, 17.7, 80, 12, 4.5);

                // 寺院名
                PostcardPrint::DocumentTempleName($pdf, $f, $document->templename, 10, 90, 18, 7);

                // 電話番号
                PostcardPrint::DocumentTel($pdf, $f, $document->tel, 4.8, 90, 11, 4);

                // 文書
                $backdocuments = [$document->document1,
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
            $pdf_path = 'AkihiganBackPrint_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // 秋彼岸裏面データ取得
    public function getAkihiganBackData(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'akihigan_header_search') === 0) {
            $put_flg = true;
        }

        $cond_akihigan_header = CommonUtility::GetQueryParameter($request, 'cond_akihigan_header', $put_flg);

        // 検索条件が空の場合のデフォルト設定
        if (empty($cond_akihigan_header)) {
            $cond_akihigan_header = [
                'manager' => null,
            ];
        }

        $keys = ListData::GetAkihiganHeaderKey($cond_akihigan_header);

        $akihigan_header = collect();

        foreach ($keys as $key) {
            $query = DB::table('akihigan_headers') 
                        ->leftJoin('akihigan_details', 'akihigan_headers.id', '=', 'akihigan_details.akihigan_header_id')
                        ->leftJoin('followers', 'akihigan_details.danka_id', '=', 'followers.danka_id')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('akihigan_details.id as akihigan_detail_id',
                                 'akihigan_details.name',
                                 'akihigan_details.namekana',
                                 'akihigan_details.postcode',
                                 'akihigan_details.address1',
                                 'akihigan_details.address2',
                                 'akihigan_details.manager',
                                 'akihigan_details.month',
                                 'akihigan_details.day',
                                 'akihigan_details.ampm')
                        ->where('akihigan_details.akihigan_header_id', '=', $id)
                        // ->where('dankas.postcard', '=', '出す')
                        ->where('followers.chiefmourner_flg', '=', 1)
                        ->orderBy('akihigan_details.manager', 'desc')
                        ->orderBy('akihigan_details.namekana', 'desc')
                        ->distinct();

            if (!empty($cond_akihigan_header['manager'])) {
                $query->where('manager', '=', $cond_akihigan_header['manager']);
            }

            $akihigan_headers = $akihigan_header->merge($query->get());
        }

        return $akihigan_headers;
    }
}
