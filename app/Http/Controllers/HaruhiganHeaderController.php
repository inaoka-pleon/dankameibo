<?php

namespace App\Http\Controllers;

use App\Http\Requests\HaruhiganDetailRequest;
use App\Http\Requests\HaruhiganHeaderRequest;
use App\Models\Code;
use App\Models\Danka;
use App\Models\Era;
use App\Models\HaruhiganDetail;
use App\Models\HaruhiganHeader;
use App\Services\CommonUtility;
use App\Services\ListData;
use App\Services\PostcardPrint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class HaruhiganHeaderController extends Controller
{
    /**
     * 春彼岸一覧表示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = HaruhiganHeader::query()
                            ->select('haruhigan_headers.id',
                                     'haruhigan_headers.era',
                                     'haruhigan_headers.year',
                                     'haruhigan_headers.created_at');

        $haruhigan_headers = $query->get();

        // 作成日を西暦から和暦に変換
        foreach ($haruhigan_headers as $haruhigan_header) {
            $created_at_date = Carbon::parse($haruhigan_header->created_at)->format('Y-m-d');
            $CreatedEra = CommonUtility::ADtoJACalendarConv($created_at_date);
    
            $haruhigan_header->CreatedEraName = $CreatedEra['era_name'] ?? '';
            $haruhigan_header->CreatedEraYear = $CreatedEra['era_year'] ?? '';
            $haruhigan_header->CreatedMonth = $CreatedEra['month'] ?? '';
            $haruhigan_header->CreatedDay = $CreatedEra['day'] ?? '';
            
            $haruhigan_header->ad_year = CommonUtility::JAtoADCalendarEraNameConv($haruhigan_header->era, $haruhigan_header->year);
        }

        $haruhigan_orderbys = $haruhigan_headers->sortByDesc(function ($haruhigan_header) {
            return $haruhigan_header->ad_year;
        });

        return view('haruhiganheaders.index', compact('haruhigan_headers', 'haruhigan_orderbys'));
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

        return view('haruhiganheaders.create', compact('eras', 'currentEraYear'));
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HaruhiganHeaderRequest $request)
    {
        $era = $request->input('era');
        $year = $request->input('year');
        $userJiinId = Auth::guard('web')->user()->jiin_id;

        // erasテーブルから元号の最大年数を取得
        $maxYear = DB::table('eras')
                ->where('name', $era)
                ->value('years');

        if ($maxYear === null) {
            return redirect()->back()->withErrors(['era' => '指定された元号が見つかりません。']);
        }
        if ($year> $maxYear) {
            return redirect()->back()->withErrors(['year' => '指定された元号の年数が無効です。']);
        }
        
        DB::beginTransaction();

        try{
            // 同じ年度の登録があるか確認
            $year_check = HaruhiganHeader::where('era', $request->input('era'))
                                        ->where('year', $request->input('year'))
                                        ->first();

            if ($year_check) {
                DB::rollBack();
                session()->flash('info', '既に登録されています。');
                return redirect()->route('haruhiganheader.index');
            }
            $dankas  = Danka::query()
                    ->join('followers as chief', function($join) use ($userJiinId) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                             ->where('chief.chiefmourner_flg', '=', 1)
                             ->where('chief.jiin_id', '=', $userJiinId);
                    })
                    ->select('dankas.id',
                             'chief.name',
                             'chief.namekana',
                             'chief.postcode',
                             'chief.address1',
                             'chief.address2',
                             'chief.tel')
                    ->where('dankas.haruhigan', '=', 1)
                    ->where('dankas.postcard', '=', '出す')
                    ->get();

            $haruhigan_header = new HaruhiganHeader;

            $haruhigan_header->era = $request->input('era');
            $haruhigan_header->year = $request->input('year');

            if (Auth::guard('web')->check()) {
                $haruhigan_header->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $haruhigan_header->save();

            foreach ($dankas as $danka){
                $haruhigan_detail = new HaruhiganDetail();

                $haruhigan_detail->danka_id = $danka->id;
                $haruhigan_detail->name = $danka->name;
                $haruhigan_detail->namekana = $danka->namekana;
                $haruhigan_detail->postcode = $danka->postcode;
                $haruhigan_detail->address1 = $danka->address1;
                $haruhigan_detail->address2 = $danka->address2;
                $haruhigan_detail->tel = $danka->tel;
                $haruhigan_detail->haruhigan_header_id = $haruhigan_header->id;
                $haruhigan_detail->jiin_id = $haruhigan_header->jiin_id;
                if (Auth::guard('web')->check()) {
                    $haruhigan_detail->jiin_id = Auth::guard('web')->user()->jiin_id;
                }
                $haruhigan_detail->save();
            }

            DB::commit();
            session()->flash('success', '春彼岸一覧情報を登録しました。');

        }catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '春彼岸一覧情報を登録できませんでした。');
        };

        return redirect()->route('haruhiganheader.index');
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
        if(strcmp($request->searchType, 'haruhigan_header_search') === 0) {
            $put_flg = true;
        }

        // 担当者のデータ取得
        $managers = Code::query()
                        ->where('key1', '=', 'MANAGER')
                        ->get();

        // 一覧検索パラメータ取得
        $cond_haruhigan_header = CommonUtility::GetQueryParameter($request, 'cond_haruhigan_header', $put_flg);
                            
        $haruhigan_headers = HaruhiganHeader::query()
                                ->select('haruhigan_headers.id',
                                         'haruhigan_headers.era',
                                         'haruhigan_headers.year')
                                ->where('haruhigan_headers.id', '=', $id)
                                ->first();

        $query = HaruhiganDetail::query()
                    ->join('followers', 'haruhigan_details.danka_id', '=', 'followers.danka_id')
                    ->select('haruhigan_details.id as haruhigan_detail_id',
                             'haruhigan_details.name',
                             'haruhigan_details.namekana',
                             'haruhigan_details.postcode',
                             'haruhigan_details.address1',
                             'haruhigan_details.address2',
                             'haruhigan_details.tel',
                             'haruhigan_details.month',
                             'haruhigan_details.day',
                             'haruhigan_details.ampm',
                             'haruhigan_details.hour',
                             'haruhigan_details.minute',
                             'haruhigan_details.manager')
                    ->where('haruhigan_details.haruhigan_header_id', '=', $id)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderby('haruhigan_details.manager', 'asc')
                    ->orderby('haruhigan_details.namekana', 'asc');
        
        if(!empty($cond_haruhigan_header['manager'])){
            $query->where('manager', '=', $cond_haruhigan_header['manager']);
        }

        $haruhigan_details = $query->paginate(10);

        // 該当件数表示
        $haruhiganCount = $haruhigan_details->total();

        // IDをセッションに保存
        session(['haruhigan_id' => $id]);

        return view('haruhiganheaders.edit', compact('haruhigan_headers', 'managers', 'haruhigan_details', 'haruhiganCount'))
            ->with('cond_haruhigan_header', $cond_haruhigan_header)
            ->with('haruhigan_header_id', $id);
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
    public function update(HaruhiganDetailRequest $request, $id)
    {
        DB::beginTransaction();
        $haruhigan_cnt = DB::table('haruhigan_details')->where('haruhigan_header_id', '=', $id)->count();
        try{            
            for($i = 1; $i <= $haruhigan_cnt; $i++) {
                $haruhigan_detail_id = $request->input('haruhigan_detail_id_'.$i);
                $haruhigan_detail = HaruhiganDetail::find($haruhigan_detail_id);
                $haruhigan_detail->month = $request->input('month_'.$i);
                $haruhigan_detail->day = $request->input('day_'.$i);
                $haruhigan_detail->ampm = $request->input('ampm_'.$i);
                $haruhigan_detail->hour = $request->input('hour_'.$i);
                $haruhigan_detail->minute = $request->input('minute_'.$i);
                $haruhigan_detail->manager = $request->input('manager_'.$i);

                $haruhigan_detail->save();
                }

                DB::commit();
                session()->flash('success', '春彼岸情報を変更しました。');
                
            } catch(Exception $ex) {
                DB::rollBack();
                session()->flash('error', '春彼岸情報を変更できませんでした。');
            }
            return redirect()->route('haruhiganheader.edit', $id);
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
        if (strcmp($request->searchType, 'haruhigan_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                ->where('key1', '=', 'AREA')
                ->get();

        //一覧検索パラメータ取得
        $cond_haruhigan = CommonUtility::GetQueryParameter($request, 'cond_haruhigan', $put_flg);

        $haruhigan_headers = HaruhiganHeader::query()
                            ->select('haruhigan_headers.id',
                                     'haruhigan_headers.era',
                                     'haruhigan_headers.year')
                            ->where('haruhigan_headers.id', '=', $id)
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

        if(!empty($cond_haruhigan['area'])) {
            $query->where('area', '=', $cond_haruhigan['area']);
            } 
            if(!empty($cond_haruhigan['name'])) {
            $query->where('name', 'like', '%'.$cond_haruhigan['name'].'%');
            }
            if (!empty($cond_haruhigan['namekana'])) {
            $query->where('namekana', 'like', '%'.$cond_haruhigan['namekana'].'%');
            }

        $danka_searchs = $query->get();

        // 該当件数表示
        $dankaSearchCount = $danka_searchs->count();

        return view('haruhiganheaders.danka_search', ['id' => $id], compact('haruhigan_headers', 'areas', 'danka_searchs', 'dankaSearchCount'))
            ->with('haruhigan_header_id', $id)
            ->with('cond_haruhigan', $cond_haruhigan);
    }
    // 檀信徒追加
    public function add_danka_data(Request $request, $id)
    {
        $selectedDankas = $request->input('selected_dankas');

        foreach ($selectedDankas as $selectedDanka) {
            $danka = Danka::with('followers')->find($selectedDanka);
            if ($danka && $danka->followers->isNotEmpty()) {
                $follower = $danka->followers->first();
                $haruhigan_detail = new HaruhiganDetail();
                $haruhigan_detail->haruhigan_header_id = $id;
                $haruhigan_detail->danka_id = $danka->id;
                $haruhigan_detail->name = $follower->name;
                $haruhigan_detail->namekana = $follower->namekana;
                $haruhigan_detail->address1 = $follower->address1;
                $haruhigan_detail->address2 = $follower->address2;
                $haruhigan_detail->tel = $follower->tel;
                $haruhigan_detail->month = '3';

                $haruhigan_detail->save();
            }
        }
        return redirect()->route('haruhiganheader.edit', $id);
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
        $templatePath = resource_path('template/Haruhiganheader.pdf');
        $pdf->setSourceFile($templatePath);
    
        // 検索データを取得
        $put_flg = false;
        if(strcmp($request->searchType, 'haruhigan_header_search') === 0) {
            $put_flg = true;
        }
    
        $cond_haruhigan_header = CommonUtility::GetQueryParameter($request, 'cond_haruhigan_header', $put_flg);
    
        if (!empty($cond_haruhigan_header['manager'])) {
            // 検索条件に該当するデータを取得
            $haruhigan_headers = $this->getHaruhiganHeaders($id, $cond_haruhigan_header['manager'], $cond_haruhigan_header);
    
            if(!$haruhigan_headers->isEmpty()) {
                $this->addHaruhiganHeadersToPdf($pdf, $haruhigan_headers, $f);
            }
        } else {
            // nullのデータを最初に取得
            $haruhigan_headers_null = $this->getHaruhiganHeaders($id, null, $cond_haruhigan_header);
    
            if(!$haruhigan_headers_null->isEmpty()) {
                $this->addHaruhiganHeadersToPdf($pdf, $haruhigan_headers_null, $f);
            }
    
            // データを取得
            $keys = ListData::GetHaruhiganHeaderKey($cond_haruhigan_header);
            foreach ($keys as $key) {
                $haruhigan_headers = $this->getHaruhiganHeaders($id, $key->value1, $cond_haruhigan_header);
    
                if($haruhigan_headers->isEmpty()) {
                    continue;
                }
    
                $this->addHaruhiganHeadersToPdf($pdf, $haruhigan_headers, $f);
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    
    private function getHaruhiganHeaders($id, $manager, $cond_haruhigan_header)
    {
        $query = DB::table('haruhigan_headers')
                   ->join('haruhigan_details', 'haruhigan_headers.id', '=', 'haruhigan_details.haruhigan_header_id')
                   ->join('followers', 'haruhigan_details.danka_id', '=', 'followers.danka_id')
                   ->where('haruhigan_details.haruhigan_header_id', '=', $id)
                   ->where('followers.chiefmourner_flg', '=', 1)
                   ->orderBy('followers.namekana', 'asc');
    
        if (is_null($manager)) {
            $query->whereNull('haruhigan_details.manager');
        } else {
            $query->where('haruhigan_details.manager', '=', $manager);
            if(!empty($cond_haruhigan_header['manager'])) {
                $query->where('manager', '=', $cond_haruhigan_header['manager']);
            }
        }
    
        return $query->get();
    }
    
    private function addHaruhiganHeadersToPdf($pdf, $haruhigan_headers, $font)
    {
        $templateId = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);
    
        $pdf->setFont($font, '', 11);
        $y = 0;
        $no = 0;
        $rowcnt = 0;
        $haruhiganPrinted = false;
    
        foreach ($haruhigan_headers as $haruhigan_header) {
            if ($rowcnt >= 30) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $pdf->setFont($font, '', 11);
                $y = 0;
                $haruhiganPrinted = false;
            }
    
            $rowcnt += 1;
            if (!$haruhiganPrinted) {
                // 文字を出力、位置指定(元号)
                $pdf->setFont($font, '', 12);
                $pdf->text(10, 13, $haruhigan_header->era);
        
                // 文字を出力、位置指定(年度)
                $pdf->setFont($font, '', 12);
                $pdf->text(20, 13, $haruhigan_header->year . '年');
        
                $pdf->setFont($font, '', 13);
                $pdf->text(10, 25, '担当者 : '. $haruhigan_header->manager);
                $haruhiganPrinted = true;
            }
    
            // 文字を出力、位置指定(No)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(7, 42.8 + $y, $no += 1);     
    
            // 文字を出力、位置指定(氏名)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(16, 44 + $y, $haruhigan_header->name);
    
            // 文字を出力、位置指定(氏名かな)
            $pdf->setFont($font, '', 7);
            $pdf->text(16.5, 41.5 + $y, $haruhigan_header->namekana);
    
            // 文字を出力、位置指定(住所)
            $pdf->setFont($font, '', 8.5);
            $pdf->MultiCell(66, 42, $haruhigan_header->address1 . $haruhigan_header->address2, 0, 'L', 0, 0, 52.5, 41.5 + $y);
    
            // 文字を出力、位置指定(電話番号)
            $pdf->setFont($font, '', 10);
            $pdf->text(118.5, 42.8 + $y, $haruhigan_header->tel);
    
            // 文字を出力、位置指定(月)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(155, 42.8 + $y, $haruhigan_header->month);
    
            // 文字を出力、位置指定(月)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(160, 42.8 + $y, '月');
            
            // 文字を出力、位置指定(日)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(167, 42.8 + $y, $haruhigan_header->day);
    
            // 文字を出力、位置指定(日)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(172, 42.8 + $y, '日');
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(178, 42.8 + $y, $haruhigan_header->ampm);
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(187.5, 42.8 + $y, $haruhigan_header->hour);
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(192, 42.5 + $y, '：');
    
            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(195.5, 42.8 + $y, $haruhigan_header->minute);

            $y += 8.135;
        }
    }
    // 春彼岸データ取得
    public function getHaruhiganData(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'haruhigan_header_search') === 0) {
            $put_flg = true;
        }

        $cond_haruhigan_header = CommonUtility::GetQueryParameter($request, 'cond_haruhigan_header', $put_flg);

        // 検索条件が空の場合のデフォルト設定
        if (empty($cond_haruhigan_header)) {
            $cond_haruhigan_header = [
                'manager' => null,
            ];
        }

        $keys = ListData::GetHaruhiganHeaderKey($cond_haruhigan_header);

        $haruhigan_header = collect();

        foreach ($keys as $key) {
            $query = DB::table('haruhigan_headers') 
                        ->leftJoin('haruhigan_details', 'haruhigan_headers.id', '=', 'haruhigan_details.haruhigan_header_id')
                        ->leftJoin('followers', 'haruhigan_details.danka_id', '=', 'followers.danka_id')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('haruhigan_details.id as haruhigan_detail_id',
                                 'haruhigan_details.name',
                                 'haruhigan_details.namekana',
                                 'haruhigan_details.postcode',
                                 'haruhigan_details.address1',
                                 'haruhigan_details.address2',
                                 'haruhigan_details.manager',
                                 'haruhigan_details.month',
                                 'haruhigan_details.day',
                                 'haruhigan_details.ampm',
                                 'haruhigan_details.hour')
                        ->where('haruhigan_details.haruhigan_header_id', '=', $id)
                        // ->where('dankas.postcard', '=', '出す')
                        ->where('followers.chiefmourner_flg', '=', 1)
                        ->orderBy('haruhigan_details.manager')
                        ->orderBy('haruhigan_details.namekana', 'asc')
                        ->distinct();

            if (!empty($cond_haruhigan_header['manager'])) {
                $query->where('manager', '=', $cond_haruhigan_header['manager']);
            }

            $haruhigan_headers = $haruhigan_header->merge($query->get());
        }

        return $haruhigan_headers;
    }
    public function postcard_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $haruhigans = $this->getHaruhiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($haruhigans as $haruhigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $haruhigan->name. ' 様';
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $haruhigan->postcode;
            $text = str_replace('-', '', $haruhigan->postcode);
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
            $text = parse_number($haruhigan->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 25, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($haruhigan->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);     
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $haruhigans = $this->getHaruhiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($haruhigans as $haruhigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $haruhigan->name. ' 様';
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $haruhigan->postcode;
            $text = str_replace('-', '', $haruhigan->postcode);
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
            $text = parse_number($haruhigan->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($haruhigan->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);   
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $haruhigans = $this->getHaruhiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($haruhigans as $haruhigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $haruhigan->name. ' 様';
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $haruhigan->postcode;
            $text = str_replace('-', '', $haruhigan->postcode);
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
            $text = parse_number($haruhigan->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($haruhigan->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 90, 30, 26);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $haruhigans = $this->getHaruhiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($haruhigans as $haruhigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $haruhigan->name. ' 様';
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 50, 56);
            if (!is_null($haruhigan->postcode)) {
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
                $text = $haruhigan->postcode;
                $text = str_replace('―', '', $haruhigan->postcode);
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
            $text = parse_number($haruhigan->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($haruhigan->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $haruhigans = $this->getHaruhiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($haruhigans as $haruhigan) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $haruhigan->name. ' 様';
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);

            if (!is_null($haruhigan->postcode)) {
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
                $text = str_replace('―', '', $haruhigan->postcode);
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
            $text = parse_number($haruhigan->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($haruhigan->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $haruhigans = $this->getHaruhiganData($request, $id);

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($haruhigans as $index => $haruhigan) {
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
            $name = $haruhigan->name;
            $keishou = null;
            $postcode = $haruhigan->postcode;
            $address = ltrim($haruhigan->address1 . $haruhigan->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }
    
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $haruhigans = $this->getHaruhiganBackData($request, $id);
        $documents = DB::table('haruhigan_documents')->get();

        $hasResults = true;
        
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($haruhigans as $haruhigan) {
            // 新しいページを追加
            $page = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($page);

            // 各位
            PostcardPrint::DocumentKakui($pdf, $f, $haruhigan->name. ' 様', 10, 10, 18, 7);

            // 月
            PostcardPrint::DocumentDate($pdf, $f, $haruhigan->month, 47, 14.2, 12, 4.2);
            // 日
            PostcardPrint::DocumentDate($pdf, $f, $haruhigan->day, 47, 31, 12, 4.2);

            // 午前午後
            PostcardPrint::DocumentKakui($pdf, $f, $haruhigan->ampm, 47, 52, 12, 4.2);

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
            $pdf_path = 'HaruhiganBackPrint_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // 春彼岸裏面データ取得
    public function getHaruhiganBackData(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'haruhigan_header_search') === 0) {
            $put_flg = true;
        }

        $cond_haruhigan_header = CommonUtility::GetQueryParameter($request, 'cond_haruhigan_header', $put_flg);

        // 検索条件が空の場合のデフォルト設定
        if (empty($cond_haruhigan_header)) {
            $cond_haruhigan_header = [
                'manager' => null,
            ];
        }

        $keys = ListData::GetHaruhiganHeaderKey($cond_haruhigan_header);

        $haruhigan_header = collect();

        foreach ($keys as $key) {
            $query = DB::table('haruhigan_headers') 
                        ->leftJoin('haruhigan_details', 'haruhigan_headers.id', '=', 'haruhigan_details.haruhigan_header_id')
                        ->leftJoin('followers', 'haruhigan_details.danka_id', '=', 'followers.danka_id')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('haruhigan_details.id as haruhigan_detail_id',
                                 'haruhigan_details.name',
                                 'haruhigan_details.namekana',
                                 'haruhigan_details.postcode',
                                 'haruhigan_details.address1',
                                 'haruhigan_details.address2',
                                 'haruhigan_details.manager',
                                 'haruhigan_details.month',
                                 'haruhigan_details.day',
                                 'haruhigan_details.ampm',
                                 'haruhigan_details.hour')
                        ->where('haruhigan_details.haruhigan_header_id', '=', $id)
                        // ->where('dankas.postcard', '=', '出す')
                        ->where('followers.chiefmourner_flg', '=', 1)
                        ->orderBy('haruhigan_details.manager', 'desc')
                        ->orderBy('haruhigan_details.namekana', 'desc')
                        ->distinct();

            if (!empty($cond_haruhigan_header['manager'])) {
                $query->where('manager', '=', $cond_haruhigan_header['manager']);
            }

            $haruhigan_headers = $haruhigan_header->merge($query->get());
        }

        return $haruhigan_headers;
    }
}
