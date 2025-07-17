<?php

namespace App\Http\Controllers;

use App\Facades\NenkiData;
use App\Http\Requests\NenkilistRequest;
use App\Models\Follower;
use App\Models\Kaiki;
use App\Models\NenkiDocument;
use App\Models\Nenkilist;
use App\Models\TempleMaster;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class NenkilistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $kakocho = Follower::find($id);

        $nenkilists = Follower::find($id)
                    ->join('nenkilists', 'followers.id', 'nenkilists.kakocho_id')
                    ->where('kakocho_id', $id)
                    ->get();
                    
        $kaikis = Kaiki::query()
                ->select('kaikis.id',
                        'kaikis.kaiki_kbn',
                        'kaikis.kaiki',
                        'kaikis.kaiki_name',
                        'kaikis.from_year_kbn',
                        'kaikis.from_month',
                        'kaikis.from_day',
                        'kaikis.to_year_kbn',
                        'kaikis.to_month',
                        'kaikis.to_day',
                        'kaikis.houyou_month',
                        'kaikis.houyou_day')
                ->where(function($query) {
                    $query->where('kaikis.kaiki_kbn', '=', 0)
                        ->where('kaikis.target_flg', '=', 1);
                })
                ->orWhere('kaikis.kaiki_kbn', '!=', 0)
                ->orderBy('kaikis.disp_order', 'asc')
                ->get();
    
        $houyouDates = [];
        
        foreach ($nenkilists as $nenki) {
            foreach ($kaikis as $kaiki) {
                $houyouData = $this->GetHouyouData($nenki->deathanniversary, $kaiki);
                if ($houyouData) {
                    $houyouDates[$kaiki->id] = CommonUtility::ADtoJACalendarConv($houyouData);
                }
            }
        }

        // IDをセッションに保存
        session(['kakocho_id' => $id]);

        return view('nenkilists.index', compact('kakocho', 'nenkilists', 'kaikis', 'houyouDates'))->with('danka_id', $id);
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
        $followers = Follower::find($id);
        $kakochos = Nenkilist::where('kakocho_id', $followers->id)->get();

        DB::beginTransaction();

        try {
            foreach ($kakochos as $kakocho) {
                $nenkilist = Nenkilist::where('kakocho_id', $kakocho->kakocho_id)
                                      ->where('kaiki_id', $kakocho->kaiki_id)
                                      ->first();

                if ($nenkilist) {
                    $nenkilist->kuyou = $request->input('kuyou')[$kakocho->kaiki_id] ?? 0;
                    $nenkilist->memo = $request->input('memo')[$kakocho->kaiki_id] ?? '';
                    $nenkilist->save();
                }
            }

            DB::commit();
            session()->flash('success', '年忌表情報を更新しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '年忌表情報を更新できませんでした。');
        };

        return redirect()->route('nenkilist.index', $id);
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

        $kakocho = Follower::find($id);
        
        $query = Follower::query()
                ->select('followers.id as kakocho_id',
                         'followers.kaimyou',
                         'followers.zokumyou',
                         'followers.ageatdeath',
                         'followers.deathanniversary',
                         'followers.death_era',
                         'followers.death_year',
                         'followers.death_month',
                         'followers.death_day')
                ->where('followers.id', $kakocho->id)
                ->where('followers.deceased_flg', '=', 1);       

        $nenkilists = $query->get();

        $kaikis = Kaiki::query()
                ->select('kaikis.id',
                         'kaikis.kaiki_kbn',
                         'kaikis.kaiki',
                         'kaikis.kaiki_name',
                         'kaikis.from_year_kbn',
                         'kaikis.from_month',
                         'kaikis.from_day',
                         'kaikis.to_year_kbn',
                         'kaikis.to_month',
                         'kaikis.to_day',
                         'kaikis.houyou_month',
                         'kaikis.houyou_day')
                ->where(function($query) {
                    $query->where('kaikis.kaiki_kbn', '=', 0)
                            ->where('kaikis.target_flg', '=', 1);
                })
                ->orWhere('kaikis.kaiki_kbn', '!=', 0)
                ->orderBy('kaikis.disp_order', 'asc')
                ->get();
        
        $houyouDates = [];

        foreach ($nenkilists as $nenki) {
            foreach ($kaikis as $kaiki) {
                $houyouData = $this->GetHouyouData($nenki->deathanniversary, $kaiki);
                if ($houyouData) {
                    $houyouDates[$kaiki->kaiki_kbn][] = [
                        'date' => CommonUtility::ADtoJACalendarConv($houyouData),
                        'kaiki_id' => $kaiki->id
                    ];
                }
            }
        }

        $nenkidocuments = NenkiDocument::query()->get();

        $templemasters = TempleMaster::query()->get();


        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $templatePath1 = resource_path('template/NenkiList1.pdf');
        $templatePath2 = resource_path('template/NenkiList2.pdf');
        $pdf->setSourceFile($templatePath1);
        $templateId1 = $pdf->importPage(1);

        $pdf->setSourceFile($templatePath2);
        $templateId2 = $pdf->importPage(1);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);
            
        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId1, null, null ,null, null, true);

        $page = 1;

        foreach ($nenkilists as $nenkilist) {            
            // 戒名
            $pdf->setFont($f, '', 20);
            $pdf->Text(58, 31, $nenkilist->kaimyou);
            // 俗名
            $pdf->setFont($f, '', 20);
            $pdf->Text(58, 42, $nenkilist->zokumyou);
            // 行年
            $pdf->setFont($f, '', 20);
            $pdf->Text(170, 42, $nenkilist->ageatdeath);
            // 命日
            $pdf->setFont($f, '', 20);
            $death_era = CommonUtility::ADtoJACalendarConv($nenkilist->deathanniversary);
            $death_era_name = $death_era['era_name'];
            $death_year = $death_era['era_year'];
            $pdf->Text(60, 53, $death_era_name);
            $pdf->Text(90, 53, $death_year. ' 年');
            $pdf->Text(120, 53, $nenkilist->death_month. ' 月');
            $pdf->Text(150, 53, $nenkilist->death_day. ' 日亡');
        }

        foreach ($nenkidocuments as $nenkidocument) {
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 194, $nenkidocument->document1);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 201, $nenkidocument->document2);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 208, $nenkidocument->document3);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 215, $nenkidocument->document4);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 222, $nenkidocument->document5);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 237, $nenkidocument->document6);
        }

        foreach ($templemasters as $templemaster)
        {
            // 山号
            $pdf->setFont($f, '', 16);
            $mountainName = $templemaster->mountainname;
            $x = 100;
            $y = 270;

            foreach (mb_str_split($mountainName) as $char) {
                $pdf->Text($x, $y, $char);
                $x += 11;
            }
            // 寺院名
            $pdf->setFont($f, '', 28);
            $templeName = $templemaster->templename;
            $x = 145;
            $y = 265;

            foreach (mb_str_split($templeName) as $char) {
                $pdf->Text($x, $y, $char);
                $x += 17;
            }
            // TEL
            $pdf->setFont($f, '', 12);
            $tel = 'TEL'. $templemaster->tel;
            $x = 112;
            $y = 280;

            foreach (mb_str_split($tel) as $char) {
                $pdf->Text($x, $y, $char);
                $x += 5;
            }
        }

        $y = 0;
        $rowcnt = 0;
        foreach ($kaikis as $kaiki) {
            if ($rowcnt >= 11) {
                $pdf->AddPage();
                $pdf->useTemplate($templateId2, null, null, null, null, true);
                $rowcnt = 0;
                $page += 1;
                $y = 0;
            }
            $rowcnt += 1;
            // 回忌名
            $pdf->setFont($f, '', 20);
            $pdf->Text(20, 64 + $y, $kaiki->kaiki_name);
            // 日付
            $pdf->setFont($f, '', 20);
            $era = '';
            $year = '';
            $month = '';
            $day = '';
            if (isset($houyouDates[$kaiki->kaiki_kbn])) {
                foreach ($houyouDates[$kaiki->kaiki_kbn] as $houyouData) {
                    if ($houyouData['kaiki_id'] == $kaiki->id) {
                        $era = $houyouData['date']['era_name'];
                        $year = $houyouData['date']['era_year'] . ' 年';
                        $month = $houyouData['date']['month'] . ' 月';
                        $day = $houyouData['date']['day'] . ' 日';
                        break;
                    }
                }
            }
            $pdf->Text(70, 64 + $y, $era);
            $pdf->Text(102, 64 + $y, $year);
            $pdf->Text(134, 64 + $y, $month);
            $pdf->Text(166, 64 + $y, $day);
            $y += 10.7;
        }
        // 2ページ目以降も戒名等を表示
        foreach ($nenkilists as $nenkilist) {            
            // 戒名
            $pdf->setFont($f, '', 20);
            $pdf->Text(58, 31, $nenkilist->kaimyou);
            // 俗名
            $pdf->setFont($f, '', 20);
            $pdf->Text(58, 42, $nenkilist->zokumyou);
            // 行年
            $pdf->setFont($f, '', 20);
            $pdf->Text(170, 42, $nenkilist->ageatdeath);
            // 命日
            $pdf->setFont($f, '', 20);
            $death_era = CommonUtility::ADtoJACalendarConv($nenkilist->deathanniversary);
            $death_era_name = $death_era['era_name'];
            $death_year = $death_era['era_year'];
            $pdf->Text(60, 53, $death_era_name);
            $pdf->Text(90, 53, $death_year. ' 年');
            $pdf->Text(120, 53, $nenkilist->death_month. ' 月');
            $pdf->Text(150, 53, $nenkilist->death_day. ' 日亡');
        }

        foreach ($nenkidocuments as $nenkidocument) {
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 194, $nenkidocument->document1);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 201, $nenkidocument->document2);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 208, $nenkidocument->document3);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 215, $nenkidocument->document4);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 222, $nenkidocument->document5);
            $pdf->setFont($f, '', 14.5);
            $pdf->Text(12, 237, $nenkidocument->document6);
        }

        foreach ($templemasters as $templemaster)
        {
            // 山号
            $pdf->setFont($f, '', 16);
            $mountainName = $templemaster->mountainname;
            $x = 100;
            $y = 270;

            foreach (mb_str_split($mountainName) as $char) {
                $pdf->Text($x, $y, $char);
                $x += 11;
            }
            // 寺院名
            $pdf->setFont($f, '', 28);
            $templeName = $templemaster->templename;
            $x = 145;
            $y = 265;

            foreach (mb_str_split($templeName) as $char) {
                $pdf->Text($x, $y, $char);
                $x += 17;
            }
            // TEL
            $pdf->setFont($f, '', 12);
            $tel = 'TEL'. $templemaster->tel;
            $x = 112;
            $y = 280;

            foreach (mb_str_split($tel) as $char) {
                $pdf->Text($x, $y, $char);
                $x += 5;
            }
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'NenkiList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
    private function GetHouyouData($target_date, $kaiki) {
        try {
            $result = null;
            $death_anniversary = Carbon::parse($target_date);
            switch ($kaiki->kaiki_kbn) {
                case '0':   // 年忌
                    if ($kaiki->kaiki == 1) {
                        $result = $death_anniversary->addYear($kaiki->kaiki);
                    } else {
                        $result = $death_anniversary->addYear($kaiki->kaiki-1);
                    }
                    break;
                case '1':   // 百箇日
                    $result = $death_anniversary->addDay(99);
                    break;
                case '2':   // 花初め
                case '3':   // 初盆施食会
                    $ymd    = Carbon::create($death_anniversary->year, $kaiki->to_month, $kaiki->to_day);
                    if ($death_anniversary->gt($ymd)) {
                        $result = Carbon::create($death_anniversary->year, $kaiki->houyou_month, $kaiki->houyou_day)->addYear(1);
                    } else {
                        $result = Carbon::create($death_anniversary->year, $kaiki->houyou_month, $kaiki->houyou_day);
                    }
                    break;
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}
