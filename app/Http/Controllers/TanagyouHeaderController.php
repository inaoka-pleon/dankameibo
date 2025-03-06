<?php

namespace App\Http\Controllers;

use App\Http\Requests\TanagyouHeaderRequest;
use App\Models\Code;
use App\Models\Danka;
use App\Models\Era;
use App\Models\Kaiki;
use App\Models\Postcard;
use App\Models\TanagyouHeader;
use App\Models\TanagyouDetail;
use App\Services\CommonUtility;
use App\Services\KaikiData;
use App\Services\ListData;
use App\Services\PostcardPrint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class TanagyouHeaderController extends Controller
{
    /**
     * 棚経参一覧表示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = TanagyouHeader::query()
                            ->select('tanagyou_headers.id',
                                     'tanagyou_headers.era',
                                     'tanagyou_headers.year',
                                     'tanagyou_headers.created_at');

        $tanagyou_headers = $query->get();

        // 作成日を西暦から和暦に変換
        foreach ($tanagyou_headers as $tanagyou_header) {
            $created_at_date = Carbon::parse($tanagyou_header->created_at)->format('Y-m-d');
            $CreatedEra = CommonUtility::ADtoJACalendarConv($created_at_date);

            $tanagyou_header->CreatedEraName = $CreatedEra['era_name'] ?? '';
            $tanagyou_header->CreatedEraYear = $CreatedEra['era_year'] ?? '';
            $tanagyou_header->CreatedMonth = $CreatedEra['month'] ?? '';
            $tanagyou_header->CreatedDay = $CreatedEra['day'] ?? '';

            $tanagyou_header->ad_year = CommonUtility::JAtoADCalendarEraNameConv($tanagyou_header->era, $tanagyou_header->year);
        }

        $tanagyou_orderbys = $tanagyou_headers->sortByDesc(function ($tanagyou_header) {
            return $tanagyou_header->ad_year;
        });

        return view('tanagyouheaders.index', compact('tanagyou_headers', 'tanagyou_orderbys'));
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

        return view('tanagyouheaders.create', compact('eras', 'currentEraYear'));
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TanagyouHeaderRequest $request)
    {
        $era = $request->input('era');
        $year = $request->input('year');

        // erasテーブルから元号の最大年数を取得
        $maxYear = DB::table('eras')
                ->where('name', $era)
                ->value('years');

        if ($year > $maxYear) {
            return redirect()->back()->withErrors(['year' => '指定された元号の年数が無効です。']);
        }

        DB::beginTransaction();

        try {
            // 同じ年度の登録があるか確認
            $year_check = TanagyouHeader::where('era', $request->input('era'))
                                        ->where('year', $request->input('year'))
                                        ->first();

            if ($year_check) {
                session()->flash('info', '既に登録されています。');
                return redirect()->route('tanagyouheader.index');
            }
            $query  = DB::table('dankas')
                    ->join('followers as chief', function($join) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                             ->where('chief.chiefmourner_flg', '=', 1);
                    })
                    ->select('dankas.id',
                             'chief.name',
                             'chief.namekana',
                             'chief.postcode',
                             'chief.address1',
                             'chief.address2',
                             'chief.tel')
                    ->where('dankas.tanagyou', '=', 1)
                    ->where('dankas.postcard', '=', '出す');

            $dankas = $query->get();

            $tanagyou_header = new TanagyouHeader;
            $tanagyou_header->era = $request->input('era');
            $tanagyou_header->year =  $request->input('year');
            $tanagyou_header->save();

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

            // 棚経の年度を取得
            $tanagyou_year = CommonUtility::JAtoADCalendarEraNameConv($tanagyou_header->era, $tanagyou_header->year);
            // 初盆の期間を取得
            foreach ($kaikis as $kaiki) {
                $kaiki->from_date = KaikiData::HatsubonGetYmd($kaiki->from_year_kbn,
                                                              $tanagyou_year,
                                                              $kaiki->from_month,
                                                              $kaiki->from_day);
                $kaiki->to_date = KaikiData::HatsubonGetYmd($kaiki->to_year_kbn,
                                                            $tanagyou_year,
                                                            $kaiki->to_month,
                                                            $kaiki->to_day);
            }
            // 初盆の期間
            $FromDate = $kaiki->from_date;
            $ToDate = $kaiki->to_date;

            foreach ($dankas as $danka) {
                $tanagyou_detail = new TanagyouDetail();
                $tanagyou_detail->danka_id = $danka->id;
                $tanagyou_detail->name = $danka->name;
                $tanagyou_detail->namekana = $danka->namekana;
                $tanagyou_detail->postcode = $danka->postcode;
                $tanagyou_detail->address1 = $danka->address1;
                $tanagyou_detail->address2 = $danka->address2;
                $tanagyou_detail->tel = $danka->tel;
                $tanagyou_detail->tanagyou_header_id = $tanagyou_header->id;

                // 過去帳のデータを取得して初盆チェック
                $deceased_records = DB::table('followers')
                        ->where('danka_id', '=', $danka->id)
                        ->where('deceased_flg', '=', 1)
                        ->select('deathanniversary')
                        ->get();

                $hatsubon_flg = false;
                foreach($deceased_records as $record) {
                    if ($record->deathanniversary>= $FromDate && $record->deathanniversary <= $ToDate) {
                        $hatsubon_flg = true;
                        break;
                    }
                }

                if ($hatsubon_flg) {
                    $tanagyou_detail->hatsubon = 1;
                }

                $tanagyou_detail->save();
            }

            DB::commit();
            session()->flash('success', '棚経参情報を登録しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '棚経参情報を登録できませんでした。');
        }

        return redirect()->route('tanagyouheader.index');
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
        if(strcmp($request->searchType, 'tanagyou_header_search') === 0) {
            $put_flg = true;
        }

        // 担当者のデータ取得
        $managers = Code::query()
                        ->where('key1', '=', 'MANAGER')
                        ->get();

        // 一覧検索パラメータ取得
        $cond_tanagyou_header = CommonUtility::GetQueryParameter($request, 'cond_tanagyou_header', $put_flg);

        $tanagyou_headers = TanagyouHeader::query()
                                ->select('tanagyou_headers.id',
                                         'tanagyou_headers.era',
                                         'tanagyou_headers.year')
                                ->where('tanagyou_headers.id', '=', $id)
                                ->first();

        $query = TanagyouDetail::query()
                    ->join('followers', 'tanagyou_details.danka_id', '=', 'followers.danka_id')
                    ->select('tanagyou_details.id as tanagyou_detail_id',
                             'tanagyou_details.name',
                             'tanagyou_details.namekana',
                             'tanagyou_details.postcode',
                             'tanagyou_details.address1',
                             'tanagyou_details.address2',
                             'tanagyou_details.tel',
                             'tanagyou_details.month',
                             'tanagyou_details.day',
                             'tanagyou_details.ampm',
                             'tanagyou_details.hour',
                             'tanagyou_details.minute',
                             'tanagyou_details.hatsubon',
                             'tanagyou_details.time',
                             'tanagyou_details.manager')
                    ->where('tanagyou_details.tanagyou_header_id', '=', $id)
                    ->where('followers.chiefmourner_flg', '=', 1)
                    ->orderby('tanagyou_details.manager', 'asc')
                    ->orderBy('tanagyou_details.namekana', 'asc');

        if(!empty($cond_tanagyou_header['manager'])){
            $query->where('manager', '=', $cond_tanagyou_header['manager']);
        }

        $tanagyou_details = $query->paginate(10);

        // 該当件数表示
        $tanagyouCount = $tanagyou_details->total();

        // IDをセッションに保存
        session(['tanagyou_id' => $id]);

        return view('tanagyouheaders.edit', compact('tanagyou_headers', 'managers', 'tanagyou_details', 'tanagyouCount'))
            ->with('cond_tanagyou_header', $cond_tanagyou_header)
            ->with('tanagyou_header_id', $id);
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
    public function update(TanagyouHeaderRequest $request, $id)
    {
        DB::beginTransaction();
        $tanagyou_cnt = DB::table('tanagyou_details')->where('tanagyou_header_id', '=', $id)->count();
        try{
            // 棚経の年度を取得
            $tanagyou_header = TanagyouHeader::find($id);
            $tanagyou_year = CommonUtility::JAtoADCalendarEraNameConv($tanagyou_header->era, $tanagyou_header->year);

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
                $kaiki->from_date = KaikiData::HatsubonGetYmd($kaiki->from_year_kbn,
                                                              $tanagyou_year,
                                                              $kaiki->from_month,
                                                              $kaiki->from_day);
                $kaiki->to_date = KaikiData::HatsubonGetYmd($kaiki->to_year_kbn,
                                                            $tanagyou_year,
                                                            $kaiki->to_month,
                                                            $kaiki->to_day);
            }
            // 初盆の期間
            $FromDate = $kaiki->from_date;
            $ToDate = $kaiki->to_date;

            for($i = 1; $i <= $tanagyou_cnt; $i++) {
                $tanagyou_detail_id = $request->input('tanagyou_detail_id_'.$i);
                $tanagyou_detail = TanagyouDetail::find($tanagyou_detail_id);
                $tanagyou_detail->month = $request->input('month_'.$i);
                $tanagyou_detail->day = $request->input('day_'.$i);
                $tanagyou_detail->ampm = $request->input('ampm_'.$i);
                $tanagyou_detail->hour = $request->input('hour_'.$i);
                $tanagyou_detail->minute = $request->input('minute_'.$i);
                $tanagyou_detail->time = $request->input('time_'.$i);
                $tanagyou_detail->manager = $request->input('manager_'.$i);

                // 過去帳のデータを取得して初盆チェック
                $deceased_records = DB::table('followers')
                                  ->where('danka_id', '=', $tanagyou_detail->danka_id)
                                  ->where('deceased_flg', '=', 1)
                                  ->select('deathanniversary')
                                  ->get();

                $hatsubon_flg = false;
                foreach ($deceased_records as $record) {
                    if ($record->deathanniversary >= $FromDate && $record->deathanniversary <= $ToDate) {
                        $hatsubon_flg = true;
                        break;
                    }
                }

                if ($hatsubon_flg) {
                    $tanagyou_detail->hatsubon = 1;
                } else {
                    $tanagyou_detail->hatsubon = $request->input('hatsubon_'.$i);
                }
                $tanagyou_detail->save();
                }

                DB::commit();
                session()->flash('success', '棚経参情報を変更しました。');

            } catch(Exception $ex) {
                DB::rollBack();
                session()->flash('error', '棚経参情報を変更できませんでした。');

            }
            return redirect()->route('tanagyouheader.edit', $id);

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
        if (strcmp($request->searchType, 'tanagyou_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                ->where('key1', '=', 'AREA')
                ->get();

        //一覧検索パラメータ取得
        $cond_tanagyou = CommonUtility::GetQueryParameter($request, 'cond_tanagyou', $put_flg);

        $tanagyou_headers = TanagyouHeader::query()
                            ->select('tanagyou_headers.id',
                                     'tanagyou_headers.era',
                                     'tanagyou_headers.year')
                            ->where('tanagyou_headers.id', '=', $id)
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

        if(!empty($cond_tanagyou['area'])) {
            $query->where('area', '=', $cond_tanagyou['area']);
            }
            if(!empty($cond_tanagyou['name'])) {
            $query->where('name', 'like', '%'.$cond_tanagyou['name'].'%');
            }
            if (!empty($cond_tanagyou['namekana'])) {
            $query->where('namekana', 'like', '%'.$cond_tanagyou['namekana'].'%');
            }

        $danka_searchs = $query->get();

        // 該当件数表示
        $dankaSearchCount = $danka_searchs->count();

        return view('tanagyouheaders.danka_search', ['id' => $id], compact('tanagyou_headers', 'areas', 'danka_searchs', 'dankaSearchCount'))
            ->with('tanagyou_header_id', $id)
            ->with('cond_tanagyou', $cond_tanagyou);
    }
    // 檀信徒追加
    public function add_danka_data(Request $request, $id)
    {
        $selectedDankas = $request->input('selected_dankas');

        foreach ($selectedDankas as $selectedDanka) {
            $danka = Danka::with('followers')->find($selectedDanka);
            if ($danka && $danka->followers->isNotEmpty()) {
                $follower = $danka->followers->first();
                $tanagyou_detail = new TanagyouDetail();
                $tanagyou_detail->tanagyou_header_id = $id;
                $tanagyou_detail->danka_id = $danka->id;
                $tanagyou_detail->name = $follower->name;
                $tanagyou_detail->namekana = $follower->namekana;
                $tanagyou_detail->address1 = $follower->address1;
                $tanagyou_detail->address2 = $follower->address2;
                $tanagyou_detail->tel = $follower->tel;
                $tanagyou_detail->month = '8';

                $tanagyou_detail->save();
            }
        }
        return redirect()->route('tanagyouheader.edit', $id);
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
        $templatePath = resource_path('template/Tanagyouheader.pdf');
        $pdf->setSourceFile($templatePath);

        // 検索データを取得
        $put_flg = false;
        if(strcmp($request->searchType, 'tanagyou_header_search') === 0) {
            $put_flg = true;
        }

        $cond_tanagyou_header = CommonUtility::GetQueryParameter($request, 'cond_tanagyou_header', $put_flg);

        if (!empty($cond_tanagyou_header['manager'])) {
            // 検索条件に該当するデータを取得
            $tanagyou_headers = $this->getTanagyouHeaders($id, $cond_tanagyou_header['manager'], $cond_tanagyou_header);

            if(!$tanagyou_headers->isEmpty()) {
                $this->addTanagyouHeadersToPdf($pdf, $tanagyou_headers, $f);
            }
        } else {
            // nullのデータを最初に取得
            $tanagyou_headers_null = $this->getTanagyouHeaders($id, null, $cond_tanagyou_header);

            if(!$tanagyou_headers_null->isEmpty()) {
                $this->addTanagyouHeadersToPdf($pdf, $tanagyou_headers_null, $f);
            }

            // データを取得
            $keys = ListData::GetTanagyouHeaderKey($cond_tanagyou_header);
            foreach ($keys as $key) {
                $tanagyou_headers = $this->getTanagyouHeaders($id, $key->value1, $cond_tanagyou_header);

                if($tanagyou_headers->isEmpty()) {
                    continue;
                }

                $this->addTanagyouHeadersToPdf($pdf, $tanagyou_headers, $f);
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }

    private function getTanagyouHeaders($id, $manager, $cond_tanagyou_header)
    {
        $query = DB::table('tanagyou_headers')
                ->join('tanagyou_details', 'tanagyou_headers.id', '=', 'tanagyou_details.tanagyou_header_id')
                ->join('followers', 'tanagyou_details.danka_id', '=', 'followers.danka_id')
                ->where('tanagyou_details.tanagyou_header_id', '=', $id)
                ->where('followers.chiefmourner_flg', '=', 1)
                ->orderBy('followers.namekana', 'asc');

        if (is_null($manager)) {
            $query->whereNull('tanagyou_details.manager');
        } else {
            $query->where('tanagyou_details.manager', '=', $manager);
            if(!empty($cond_tanagyou_header['manager'])) {
                $query->where('manager', '=', $cond_tanagyou_header['manager']);
            }
        }

        return $query->get();
    }

    private function addTanagyouHeadersToPdf($pdf, $tanagyou_headers, $font)
    {
        $templateId = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        $pdf->setFont($font, '', 11);
        $y = 0;
        $no = 0;
        $rowcnt = 0;
        $tanagyouPrinted = false;

        foreach ($tanagyou_headers as $tanagyou_header) {
            if ($rowcnt >= 30) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $pdf->setFont($font, '', 11);
                $y = 0;
                $tanagyouPrinted = false;
            }

            $rowcnt += 1;
            if (!$tanagyouPrinted) {
                // 文字を出力、位置指定(元号)
                $pdf->setFont($font, '', 12);
                $pdf->text(10, 13, $tanagyou_header->era);

                // 文字を出力、位置指定(年度)
                $pdf->setFont($font, '', 12);
                $pdf->text(20, 13, $tanagyou_header->year . '年');

                $pdf->setFont($font, '', 13);
                $pdf->text(10, 25, '担当者 : '. $tanagyou_header->manager);
                $tanagyouPrinted = true;
            }

            // 文字を出力、位置指定(No)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(7, 42.8 + $y, $no += 1);

            // 文字を出力、位置指定(氏名)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(16, 44 + $y, $tanagyou_header->name);

            // 文字を出力、位置指定(氏名かな)
            $pdf->setFont($font, '', 7);
            $pdf->text(16.5, 41.5 + $y, $tanagyou_header->namekana);

            // 文字を出力、位置指定(住所)
            $pdf->setFont($font, '', 8.5);
            $pdf->MultiCell(66, 42, $tanagyou_header->address1 . $tanagyou_header->address2, 0, 'L', 0, 0, 45, 41.5 + $y);

            // 文字を出力、位置指定(電話番号)
            $pdf->setFont($font, '', 10);
            $pdf->text(111, 42.8 + $y, $tanagyou_header->tel);

            // 文字を出力、位置指定(月)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(141, 42.8 + $y, $tanagyou_header->month);

            // 文字を出力、位置指定(月)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(146, 42.8 + $y, '月');

            // 文字を出力、位置指定(日)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(152, 42.8 + $y, $tanagyou_header->day);

            // 文字を出力、位置指定(日)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(157, 42.8 + $y, '日');

            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(163.5, 42.8 + $y, $tanagyou_header->ampm);

            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(173, 42.8 + $y, $tanagyou_header->hour);

            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(177.5, 42.5 + $y, '：');

            // 文字を出力、位置指定(時間)
            $pdf->setFont($font, '', 10.5);
            $pdf->text(181, 42.8 + $y, $tanagyou_header->minute);

            // 文字を出力、位置指定(初盆)
            if ($tanagyou_header->hatsubon == 1) {
                $pdf->setFont($font, '', 10.5);
                $pdf->text(196, 42.8 + $y, '時');
            }
            $y += 8.135;
        }
    }
    // はがき印刷で表示するデータ取得
    public function getTanagyouData(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'tanagyou_header_search') === 0) {
            $put_flg = true;
        }

        $cond_tanagyou_header = CommonUtility::GetQueryParameter($request, 'cond_tanagyou_header', $put_flg);

        // 検索条件が空の場合のデフォルト設定
        if (empty($cond_tanagyou_header)) {
            $cond_tanagyou_header = [
                'manager' => null,
            ];
        }

        $keys = ListData::GetTanagyouHeaderKey($cond_tanagyou_header);

        $tanagyou_header = collect();

        foreach ($keys as $key) {
            $query = DB::table('tanagyou_headers')
                        ->leftJoin('tanagyou_details', 'tanagyou_headers.id', '=', 'tanagyou_details.tanagyou_header_id')
                        ->leftJoin('followers', 'tanagyou_details.danka_id', '=', 'followers.danka_id')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('tanagyou_details.id as tanagyou_detail_id',
                                 'tanagyou_details.name',
                                 'tanagyou_details.namekana',
                                 'tanagyou_details.postcode',
                                 'tanagyou_details.address1',
                                 'tanagyou_details.address2',
                                 'tanagyou_details.manager')
                        ->where('tanagyou_details.tanagyou_header_id', '=', $id)
                        // ->where('dankas.postcard', '=', '出す')
                        ->where('followers.chiefmourner_flg', '=', 1)
                        ->orderBy('tanagyou_details.manager', 'asc')
                        ->orderBy('tanagyou_details.namekana', 'asc')
                        ->distinct();

            if (!empty($cond_tanagyou_header['manager'])) {
                $query->where('manager', '=', $cond_tanagyou_header['manager']);
            }

            $tanagyou_headers = $tanagyou_header->merge($query->get());
        }

        return $tanagyou_headers;
    }
    public function postcard_print(Request $request, $id)
    {
        $action = $request->query('action');
        $pdf = PostcardPrint::createPostcardInstance();
        $f = PostcardPrint::loadFont();

        // データを取得
        $tanagyous = $this->getTanagyouData($request, $id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($tanagyous as $tanagyou) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            // 文字を出力、位置指定(氏名)
            $text = $tanagyou->name. ' 様';
            PostcardPrint::postcardName($pdf, $f, $text, 45, 32.77, 30);

            // 文字を出力、位置指定(郵便番号)
            $text = $tanagyou->postcode;
            $text = str_replace('-', '', $tanagyou->postcode);
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
            $text = parse_number($tanagyou->address1);
            PostcardPrint::postcardAddress($pdf, $f, $text, 85, 23, 20);

            // 文字を出力、位置指定(住所)
            $text = parse_number($tanagyou->address2);
            PostcardPrint::postcardAddress($pdf, $f, $text, 76.27, 25, 20);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouPostcard_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $tanagyous = $this->getTanagyouData($request, $id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope4Print.pdf';
        $templatePath = resource_path('template/Envelope4Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($tanagyous as $tanagyou) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $tanagyou->name. ' 様';
            PostcardPrint::Envelope4Name($pdf, $f, $text, 35, 40, 36);

            // 文字を出力、位置指定(郵便番号)
            $text = $tanagyou->postcode;
            $text = str_replace('-', '', $tanagyou->postcode);
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
            $text = parse_number($tanagyou->address1);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 71.5, 27, 24);

            // 文字を出力、位置指定(住所)
            $text = parse_number($tanagyou->address2);
            PostcardPrint::Envelope4Address($pdf, $f, $text, 61.5, 27, 24);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouEnvelope4_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $tanagyous = $this->getTanagyouData($request, $id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Envelope3Print.pdf';
        $templatePath = resource_path('template/Envelope3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($tanagyous as $tanagyou) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $tanagyou->name. ' 様';
            PostcardPrint::Envelope3Name($pdf, $f, $text, 50, 45, 48);

            // 文字を出力、位置指定(郵便番号)
            $text = $tanagyou->postcode;
            $text = str_replace('-', '', $tanagyou->postcode);
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
            $text = parse_number($tanagyou->address1);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 101, 30, 26);

            // 文字を出力、位置指定(住所)
            $text = parse_number($tanagyou->address2);
            PostcardPrint::Envelope3Address($pdf, $f, $text, 90, 30, 26);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouEnvelope3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $tanagyous = $this->getTanagyouData($request, $id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square3Print.pdf';
        $templatePath = resource_path('template/Square3Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($tanagyous as $tanagyou) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $tanagyou->name. ' 様';
            PostcardPrint::Square3Name($pdf, $f, $text, 100, 55, 56);
            if (!is_null($tanagyou->postcode)) {
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
                $text = $tanagyou->postcode;
                $text = str_replace('―', '', $tanagyou->postcode);
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
            $text = parse_number($tanagyou->address1);
            PostcardPrint::Square3Address($pdf, $f, $text, 190, 35, 36);

            // 文字を出力、位置指定(住所)
            $text = parse_number($tanagyou->address2);
            PostcardPrint::Square3Address($pdf, $f, $text, 177, 35, 36);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouSquare3_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $tanagyous = $this->getTanagyouData($request, $id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'Square2Print.pdf';
        $templatePath = resource_path('template/Square2Print.pdf');
        $pdf->setSourceFile($templatePath);

        foreach ($tanagyous as $tanagyou) {
            // 新規ページをセット
            $pdf->AddPage();
            // テンプレートPDFの1ページ目を読み込み
            $templateId = $pdf->importPage(1);
            // 読み込んだページをテンプレートに使用
            $pdf->useTemplate($templateId, null, null ,null, null, true);

            $page = 1;

            // 文字を出力、位置指定(氏名)
            $text = $tanagyou->name. ' 様';
            PostcardPrint::Square2Name($pdf, $f, $text, 110, 55, 72);

            if (!is_null($tanagyou->postcode)) {
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
                $text = str_replace('-', '', $tanagyou->postcode);
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
            $text = parse_number($tanagyou->address1);
            PostcardPrint::Square2Address($pdf, $f, $text, 210, 35, 44);

            // 文字を出力、位置指定(住所)
            $text = parse_number($tanagyou->address2);
            PostcardPrint::Square2Address($pdf, $f, $text, 192, 35, 44);
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouSquare2_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $tanagyous = $this->getTanagyouData($request, $id);

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'LabelPrint.pdf';
        $templatePath = resource_path('template/LabelPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $xOffset = 0;
        $yOffset = 0;
        $column = 0;
        $row = 0;

        foreach ($tanagyous as $index => $tanagyou) {
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
            $name = $tanagyou->name;
            $keishou = null;
            $postcode = $tanagyou->postcode;
            $address = ltrim($tanagyou->address1 . $tanagyou->address2);

            PostcardPrint::printLabel($pdf, $f, $name, $keishou, $postcode, $address, $xOffset, $yOffset);

            $column++;
            if ($column % 2 == 0) {
                $row++;
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouLabel_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
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
        $tanagyous = $this->getTanagyouBackData($request, $id);
        $tanagyou_documents = DB::table('tanagyou_documents')->get();
        $hatsubon_documents = DB::table('hatsubon_documents')->get();
        $kaikis = DB::table('kaikis')
                ->select('kaiki_kbn',
                         'houyou_month',
                         'houyou_day')
                ->where('kaikis.kaiki_kbn', '=', 3)
                ->get();

        $hasResults = true;

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PostcardPrint.pdf';
        $templatePath = resource_path('template/PostcardPrint.pdf');
        $pdf->setSourceFile($templatePath);

        $page = 1;

        foreach ($tanagyous as $tanagyou) {
            // 新しいページを追加
            $page = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($page);

            // 各位
            PostcardPrint::DocumentKakui($pdf, $f, $tanagyou->name. ' 様', 10, 10, 18, 7);

            // 月
            PostcardPrint::DocumentDate($pdf, $f, $tanagyou->month, 53, 14.2, 12, 4.2);
            // 日
            PostcardPrint::DocumentDate($pdf, $f, $tanagyou->day, 53, 31, 12, 4.2);

            // 午前午後
            PostcardPrint::DocumentKakui($pdf, $f, $tanagyou->ampm, 53, 52, 12, 4.2);

            if ($tanagyou->hatsubon == 1) {
                // hatsubon_documentsのデータを書き込む
                foreach ($hatsubon_documents as $hatsubon_document) {
                    // 表題
                    PostcardPrint::DocumentTitle($pdf, $f, $hatsubon_document->title, 85, 7, 22, 8);

                    // 住所
                    PostcardPrint::DocumentAddress($pdf, $f, $hatsubon_document->address, 17.7, 80, 12, 4.5);

                    // 寺院名
                    PostcardPrint::DocumentTempleName($pdf, $f, $hatsubon_document->templename, 10, 90, 18, 7);

                    // 電話番号
                    PostcardPrint::DocumentTel($pdf, $f, $hatsubon_document->tel, 4.8, 90, 11, 4);

                    // 法要日
                    foreach ($kaikis as $kaiki) {
                        PostcardPrint::DocumentDate($pdf, $f, $kaiki->houyou_month, 41, 31, 12, 4.2);
                        PostcardPrint::DocumentDate($pdf, $f, $kaiki->houyou_day, 41, 43.6, 12, 4.2);
                    }

                    // 文書
                    $backhatsubondocuments = [$hatsubon_document->document1,
                                              $hatsubon_document->document2,
                                              $hatsubon_document->document3,
                                              $hatsubon_document->document4,
                                              $hatsubon_document->document5,
                                              $hatsubon_document->document6,
                                              $hatsubon_document->document7,
                                              $hatsubon_document->document8,
                                              $hatsubon_document->document9,
                                             ];

                    PostcardPrint::DocumentDocument($pdf, $f, $backhatsubondocuments, 77, 10, 12, 4.2);
                }
            } else {
                // tanagyou_documentsのデータを書き込む
                foreach ($tanagyou_documents as $tanagyou_document) {
                    // 表題
                    PostcardPrint::DocumentTitle($pdf, $f, $tanagyou_document->title, 85, 7, 22, 8);

                    // 住所
                    PostcardPrint::DocumentAddress($pdf, $f, $tanagyou_document->address, 17.7, 80, 12, 4.5);

                    // 寺院名
                    PostcardPrint::DocumentTempleName($pdf, $f, $tanagyou_document->templename, 10, 90, 18, 7);

                    // 電話番号
                    PostcardPrint::DocumentTel($pdf, $f, $tanagyou_document->tel, 4.8, 90, 11, 4);

                    // 文書
                    $backtanagyoudocuments = [$tanagyou_document->document1,
                                              $tanagyou_document->document2,
                                              $tanagyou_document->document3,
                                              $tanagyou_document->document4,
                                              $tanagyou_document->document5,
                                              $tanagyou_document->document6,
                                              $tanagyou_document->document7,
                                              $tanagyou_document->document8,
                                              $tanagyou_document->document9,
                                             ];

                    PostcardPrint::DocumentDocument($pdf, $f, $backtanagyoudocuments, 77, 10, 12, 4.2);
                }
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouBackPrint_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    // 裏面印刷で表示するデータ取得
    public function getTanagyouBackData(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'tanagyou_header_search') === 0) {
            $put_flg = true;
        }

        $cond_tanagyou_header = CommonUtility::GetQueryParameter($request, 'cond_tanagyou_header', $put_flg);

        // 検索条件が空の場合のデフォルト設定
        if (empty($cond_tanagyou_header)) {
            $cond_tanagyou_header = [
                'manager' => null,
            ];
        }

        $keys = ListData::GetTanagyouHeaderKey($cond_tanagyou_header);

        $tanagyou_header = collect();

        foreach ($keys as $key) {
            $query = DB::table('tanagyou_headers')
                        ->leftJoin('tanagyou_details', 'tanagyou_headers.id', '=', 'tanagyou_details.tanagyou_header_id')
                        ->leftJoin('followers', 'tanagyou_details.danka_id', '=', 'followers.danka_id')
                        ->join('dankas', 'followers.danka_id', '=', 'dankas.id')
                        ->select('tanagyou_details.id as tanagyou_detail_id',
                                 'tanagyou_details.name',
                                 'tanagyou_details.namekana',
                                 'tanagyou_details.postcode',
                                 'tanagyou_details.address1',
                                 'tanagyou_details.address2',
                                 'tanagyou_details.manager',
                                 'tanagyou_details.month',
                                 'tanagyou_details.day',
                                 'tanagyou_details.ampm',
                                 'tanagyou_details.hatsubon')
                        ->where('tanagyou_details.tanagyou_header_id', '=', $id)
                        // ->where('dankas.postcard', '=', '出す')
                        ->where('followers.chiefmourner_flg', '=', 1)
                        ->orderBy('tanagyou_details.manager', 'desc')
                        ->orderBy('tanagyou_details.namekana', 'desc')
                        ->distinct();

            if (!empty($cond_tanagyou_header['manager'])) {
                $query->where('manager', '=', $cond_tanagyou_header['manager']);
            }

            $tanagyou_headers = $tanagyou_header->merge($query->get());
        }
        return $tanagyou_headers;
    }
}