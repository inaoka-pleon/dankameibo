<?php

namespace App\Http\Controllers;

use App\Models\Danka;
use App\Models\Era;
use App\Models\Kaiki;
use App\Services\CommonUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class HondoulistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $put_flg = false;
    if(strcmp($request->searchType, 'hondoulist_search') === 0) {
        $put_flg = true;
    }

    // 元号のデータを取得
    $eras = Era::query()
                ->select('eras.id', 'eras.name', 'eras.ad_start', 'eras.start_ymd', 'eras.end_ymd')
                ->get();

    // 回忌のデータを取得
    $kaikis = Kaiki::query()
                    ->select('kaikis.id', 'kaikis.kaiki')
                    ->where('kaikis.kaiki_kbn', '=', 0)
                    ->get();

    // 現在の日付を取得し、和暦に変換
    $currentDate = Carbon::now();
    $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
    $currentEraId = $currentEra['era_id'] ?? '';
    $currentEraYear = $currentEra['era_year'] ?? '';

    $hondou_target_year = CommonUtility::GetQueryParameter($request, 'hondou_target_year', $put_flg);

    // 初期表示時に現在年度を設定
    if (empty($hondou_target_year['era'])) {
        $hondou_target_year['era'] = $currentEraId;
    }
    // 現在年数＋1で表示
    if (empty($hondou_target_year['year'])) {
        $hondou_target_year['year'] = $currentEraYear + 1;
    }

    $era = $hondou_target_year['era'] ?? null;
    $year = $hondou_target_year['year'] ?? null;

    // 和暦を西暦に変換
    $search_era = null;
    if($era && $year) {
        $search_era = CommonUtility::JAtoADCalendarYearConv($era, $year);
    }
    
    // 一覧検索パラメータ取得
    $cond_hondoulist = CommonUtility::GetQueryParameter($request, 'cond_hondoulist', $put_flg);

    $query = Danka::query()
                    ->join('followers as deceased', function($join) {
                        $join->on('dankas.id', '=', 'deceased.danka_id')
                            ->where('deceased.deceased_flg', '=', 1);
                    })
                    ->leftjoin('followers as chief', function($join) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                            ->where('chief.chiefmourner_flg', '=', 1);
                    })
                    ->leftJoin('eras', 'deceased.death_era', '=', 'eras.id')
                    ->select('dankas.id', 'chief.name as chief_name', 'deceased.kaimyou', 'deceased.zokumyou', 'deceased.deathanniversary', 'eras.name as death_era_name', 'deceased.death_year', 'deceased.death_month', 'deceased.death_day', 'deceased.ageatdeath')
                    ->where('chief.deceased_flg', '=', 0)
                    ->orderbyraw('YEAR(deceased.deathanniversary) desc')
                    ->orderby('deceased.death_month', 'asc')
                    ->orderby('deceased.death_day', 'asc')
                    ->orderby('deceased.namekana', 'asc');
                        
    $initialHondoulists = $query->get();

    // 回忌を計算して追加
    foreach ($initialHondoulists as $hondoulist) {
        $deathYear = Carbon::parse($hondoulist->deathanniversary)->year;

        // 1周忌の場合、翌年の回忌が1
        // それ以外の場合、検索西暦-命日西暦+1で処理
        if ($search_era == $deathYear) {
            $kaikiYear = 0;
        } elseif ($search_era == $deathYear + 1) {
            $kaikiYear = 1;
        } elseif ($search_era != $deathYear) {
            $kaikiYear = $search_era - $deathYear + 1;
        }

        // 回忌年数が回忌テーブルの値と一致するか確認
        $kaiki = $kaikis->firstwhere('kaiki', $kaikiYear);
        if($kaiki) {
            $hondoulist->kaiki = $kaikiYear;
            $hondoulist->kaiki_name = $kaiki->kaiki_name;
        } else {
            $hondoulist->kaiki = null;
            $hondoulist->kaiki_name = null;
        }
    }

    // 一致するデータのみ表示
    $filteredHondoulists = $initialHondoulists->filter(function($hondoulist) use ($cond_hondoulist) {
        if (!empty($cond_hondoulist['kaiki'])) {
            return $hondoulist->kaiki == $cond_hondoulist['kaiki'];
        }
        return $hondoulist->kaiki != null;
    });

    // ページネーションを適用
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentItems = $filteredHondoulists->slice(($currentPage - 1) * $perPage, $perPage)->all();
    $hondoulists = new LengthAwarePaginator($currentItems, $filteredHondoulists->count(), $perPage, $currentPage, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
        'query' => $request->query(),
    ]);

    // 該当件数表示
    $hondouCount = $filteredHondoulists->count();

    // 検索条件をセッションに保存
    $request->session()->put('hondou_target_year', $hondou_target_year, 'cond_hondoulist', $cond_hondoulist);

    return view('hondoulists.index', compact('hondoulists', 'eras', 'kaikis', 'currentEraId', 'currentEraYear', 'hondouCount'))
        ->with('cond_hondoulist', $cond_hondoulist)
        ->with('hondou_target_year', $hondou_target_year);
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
        $pdf = new Fpdi($orientation='L', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8');
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');

        $hondou_target_year = $request->session()->get('hondou_target_year', []);
        $cond_hondoulist = $request->session()->get('cond_hondoulist', []);

        $era_id = $hondou_target_year['era'] ?? null;
        $year = $hondou_target_year['year'] ?? null;

        $era = Era::find($era_id);
        $era_name = $era ? $era->name : '';

        // 和暦を西暦に変換
        $search_era = null;
        if ($era_id && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era_id, $year);
        }

        $query = Danka::query()
                    ->join('followers as deceased', function($join) {
                        $join->on('dankas.id', '=', 'deceased.danka_id')
                            ->where('deceased.deceased_flg', '=', 1);
                    })
                    ->leftjoin('followers as chief', function($join) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                            ->where('chief.chiefmourner_flg', '=', 1);
                    })
                    ->leftJoin('eras', 'deceased.death_era', '=', 'eras.id')
                    ->select('dankas.id',
                            'chief.name as chief_name',
                            'deceased.kaimyou',
                            'deceased.zokumyou',
                            'deceased.deathanniversary',
                            'eras.name as death_era_name',
                            'deceased.death_year',
                            'deceased.death_month',
                            'deceased.death_day',
                            'deceased.ageatdeath')
                    ->where('chief.deceased_flg', '=', 0)
                    ->orderbyraw('YEAR(deceased.deathanniversary) desc')
                    ->orderby('deceased.death_month', 'asc')
                    ->orderby('deceased.death_day', 'asc');

        $hondoulists = $query->get();

        // 回忌のデータを取得
        $kaikis = Kaiki::all();

        // 回忌を計算して追加
        foreach ($hondoulists as $hondoulist) {
            $deathYear = Carbon::parse($hondoulist->deathanniversary)->year;

            if ($search_era == $deathYear) {
                $kaikiYear = 0;
            } elseif ($search_era == $deathYear + 1) {
                $kaikiYear = 1;
            } elseif ($search_era != $deathYear) {
                $kaikiYear = $search_era - $deathYear + 1;
            }
    
            $kaiki = $kaikis->firstwhere('kaiki', $kaikiYear);
            if($kaiki) {
                $hondoulist->kaiki = $kaikiYear;
                $hondoulist->kaiki_name = $kaiki->kaiki_name;
            } else {
                $hondoulist->kaiki = null;
                $hondoulist->kaiki_name = null;
            }
        }

        // 回忌が設定されているデータのみを表示
        $hondoulists = $hondoulists->filter(function($hondoulist) use ($cond_hondoulist) {
            if (!empty($cond_hondoulist['kaiki'])) {
                return $hondoulist->kaiki == $cond_hondoulist['kaiki'];
            }
            return $hondoulist->kaiki != null;
        });

        // 現在の日付を取得し、和暦に変換
        $currentDate = Carbon::now();
        $currentEra = CommonUtility::ADtoJACalendarConv($currentDate->format('Y-m-d'));
        $currentEraName = $currentEra['era_name'] ?? '';
        $currentEraYear = $currentEra['era_year'] ?? '';

        // 画像のパスを指定
        $imageFile1 = './images/HondouList1.png';
        $imageFile2 = './images/HondouList2.png';


        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'HondouList.pdf';
        $templatePath = resource_path('template/HondouList.pdf');
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
        $pdf->setFont($f, '', 30);
        $era_id = $hondou_target_year['era'] ?? null;
        $era = Era::find($era_id);
        $era_name = $era ? $era->name : '';
        $kanjiYear = CommonUtility::parseNumber($hondou_target_year['year']);
        $text = $era_name . $kanjiYear . '年度回忌一覧表';
        $x = 275;
        $y = 48;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10; // 文字の間隔を調整（上から下へ移動）
        }

        $no = 0;
        $x = 255;
        $y = 0;
        $rowcnt = 0;
        $previousKaikiName = null;

        foreach ($hondoulists as $hondoulist) {
            if ($page === 1) {
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
            } else {
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
            }
                        
            // 回忌が変わった場合に回忌名を表示
            if ($hondoulist->kaiki_name !== $previousKaikiName) {
                // 回忌名と年度の表示
                $pdf->setFont($f, '', 22);
                $deathEra = $hondoulist->death_era_name;
                $deathYear = $hondoulist->death_year;
                $deathYear = CommonUtility::parseNumber($deathYear);
                $text = $hondoulist->kaiki_name. '　（'. $deathEra. $deathYear. '年度）';
                $y = 48;
                foreach (mb_str_split($text) as $char) {
                    if ($char === '（' || $char === '）') {
                        $pdf->StartTransform();
                        $pdf->Rotate(270, $x + 4.9, $y + 4.9);
                        $pdf->Text($x, $y, $char);
                        $pdf->StopTransform();
                    } else {
                        $pdf->Text($x, $y, $char);
                    }
                    $y += 8; // 文字の間隔を調整
                }
                $previousKaikiName = $hondoulist->kaiki_name;
                $x -= 15;
                $rowcnt += 1;
            }

            $rowcnt += 1;

            // 画像
            $pdf->Image($imageFile1, $x - 3.75, 5, 12.5, 9.395);
            $pdf->Image($imageFile2, $x - 3.95, 100, 13.4, 7); // 画像の位置とサイズを調整
            

            // 戒名
            $text = $hondoulist->kaimyou;
            $charCount = mb_strlen($text);
            // 戒名が9文字以下
            if ($charCount <= 9) {
                $pdf->setFont($f, '', 21);
                $currentX = $x - 2;
                $y = 19;
                $lineHeight = 7.7; // 文字の間隔
            // 10文字以上
            } else {
                $pdf->setFont($f, '', 17);
                $currentX = $x - 1.2;
                $y = 14;
                $lineHeight = 6; // 文字の間隔
            }
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($currentX, $y, $char);
                $y += $lineHeight; // 文字の間隔を調整
            }

            // 施主名
            $pdf->setFont($f, '', 14);
            $text = $hondoulist->chief_name. ' 家';
            $x -= 0.6;
            $y = 110;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 5; // 文字の間隔を調整
            }

            // 命日（月）
            $pdf->setFont($f, '', 12);
            $kanjiMonth = CommonUtility::parseNumber($hondoulist->death_month);
            $text = $kanjiMonth;
            $x += 0.35;
            $y = 170;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 3.5; // 文字の間隔を調整
            }

            // 命日（月）
            $pdf->setFont($f, '', 12);
            $text = '月';
            $y = 180;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 5; // 文字の間隔を調整
            }

            // 命日（日）
            $pdf->setFont($f, '', 12);
            $kanjiDay = CommonUtility::parseNumber($hondoulist->death_day);
            $text = $kanjiDay;
            $y = 187;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $pdf->StopTransform();
                $y += 3.5; // 文字の間隔を調整
            }

            // 命日（日）
            $pdf->setFont($f, '', 12);
            $text = '日';
            $y = 198;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 5; // 文字の間隔を調整
            }

            // 回忌
            $pdf->setFont($f, '', 8.5);
            $text = $hondoulist->kaiki_name;
            $x -= 3;
            $y = 155;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 3.5; // 文字の間隔を調整
            }
            $x -= 15;
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'hondouList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }

    public function amulet_print(Request $request)
    {
        $action = $request->query('action');
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation='L', $unit='mm', $format='B4', $unicode=true, $encoding='UTF-8');
        // ページ設定（最初に設定しないとヘッダーに罫線が入ってしまう）
        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');

        $hondou_target_year = $request->session()->get('hondou_target_year', []);
        $cond_hondoulist = $request->session()->get('cond_hondoulist', []);

        $era_id = $hondou_target_year['era'] ?? null;
        $year = $hondou_target_year['year'] ?? null;

        $era = Era::find($era_id);
        $era_name = $era ? $era->name : '';

        // 和暦を西暦に変換
        $search_era = null;
        if ($era_id && $year) {
            $search_era = CommonUtility::JAtoADCalendarYearConv($era_id, $year);
        }

        $query = Danka::query()
                    ->join('followers as deceased', function($join) {
                        $join->on('dankas.id', '=', 'deceased.danka_id')
                            ->where('deceased.deceased_flg', '=', 1);
                    })
                    ->leftjoin('followers as chief', function($join) {
                        $join->on('dankas.id', '=', 'chief.danka_id')
                            ->where('chief.chiefmourner_flg', '=', 1);
                    })
                    ->leftJoin('eras', 'deceased.death_era', '=', 'eras.id')
                    ->select('dankas.id',
                            'chief.name as chief_name',
                            'deceased.kaimyou',
                            'deceased.zokumyou',
                            'deceased.deathanniversary',
                            'eras.name as death_era_name',
                            'deceased.death_year',
                            'deceased.death_month',
                            'deceased.death_day',
                            'deceased.ageatdeath')
                    ->where('chief.deceased_flg', '=', 0)
                    ->orderbyraw('YEAR(deceased.deathanniversary) desc')
                    ->orderby('deceased.death_month', 'asc')
                    ->orderby('deceased.death_day', 'asc');

        $hondoulists = $query->get();

        // 回忌のデータを取得
        $kaikis = Kaiki::all();

        // 回忌を計算して追加
        foreach ($hondoulists as $hondoulist) {
            $deathYear = Carbon::parse($hondoulist->deathanniversary)->year;

            if ($search_era == $deathYear) {
                $kaikiYear = 0;
            } elseif ($search_era == $deathYear + 1) {
                $kaikiYear = 1;
            } elseif ($search_era != $deathYear) {
                $kaikiYear = $search_era - $deathYear + 1;
            }

            $kaiki = $kaikis->firstwhere('kaiki', $kaikiYear);
            if($kaiki) {
                $hondoulist->kaiki = $kaikiYear;
                $hondoulist->kaiki_name = $kaiki->kaiki_name;
            } else {
                $hondoulist->kaiki = null;
                $hondoulist->kaiki_name = null;
            }
        }

        // 回忌が設定されているデータのみを表示
        $hondoulists = $hondoulists->filter(function($hondoulist) use ($cond_hondoulist) {
            if (!empty($cond_hondoulist['kaiki'])) {
                return $hondoulist->kaiki == $cond_hondoulist['kaiki'];
            }
            return $hondoulist->kaiki != null;
        });

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'HondouAmulet.pdf';
        $templatePath = resource_path('template/HondouAmulet.pdf');
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

        $no = 0;
        $x = 335;
        $y = 0;
        $rowcnt = 0;

        foreach ($hondoulists as $hondoulist) {
            if ($rowcnt >= 5) {
                $templateId = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, null, null, null, null, true);
                $rowcnt = 0;
                $page += 1;
                $pdf->setFont($f, '', 11);
                $pdf->Text(180, 282.5, $page);
                $x = 335;
            }

            $rowcnt += 1;

            // 命日(和暦年月日)
            $pdf->setFont($f, '', 24);
            $kanjiYear = CommonUtility::parseNumber($hondoulist->death_year);
            $kanjiMonth = CommonUtility::parseNumber($hondoulist->death_month);
            $kanjiDay = CommonUtility::parseNumber($hondoulist->death_day);
            $text = $hondoulist->death_era_name. $kanjiYear. '年'. $kanjiMonth. '月'. $kanjiDay. '日亡';
            // $x = 335;
            $y = 63;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 8.5;
            }

            // 戒名
            $text = $hondoulist->kaimyou;
            $charCount = mb_strlen($text);
            // 戒名が7文字以下
            if ($charCount <= 7) {
                $pdf->setFont($f, '', 64);
                // $x = 305.5;
                $currentX = $x - 29.5;
                $y = 54;
                $lineHeight = 21.5;   // 文字の間隔を調整
            // 10文字以上
            } elseif ($charCount >= 10) {
                $pdf->setFont($f, '', 31);
                // $x = 311.05;
                $currentX = $x - 24;
                $y = 57;
                $lineHeight = 10.2;   // 文字の間隔を調整
            } else {
                $pdf->setFont($f, '', 48);
                // $x = 308;
                $currentX = $x - 27;
                $y = 58;
                $lineHeight = 16;   // 文字の間隔を調整
            }
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($currentX, $y, $char);
                $y += $lineHeight; // 文字の間隔を調整
            }

            // 俗名
            $pdf->setFont($f, '', 24);
            $text = '俗名';
            // $x = 289;
            $currentX = $x - 46;
            $y = 57;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($currentX, $y, $char);
                $y += 8.5; // 文字の間隔を調整
            }

            // 俗名
            $pdf->setFont($f, '', 24);
            $text = $hondoulist->zokumyou;
            // $x = 289;
            $currentX = $x - 46;
            $y = 80;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($currentX, $y, $char);
                $y += 8.5; // 文字の間隔を調整
            }

            // 行年
            $pdf->setFont($f, '', 24);
            $text = '行年';
            $currentX = $x - 46;
            $y = 155;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($currentX, $y, $char);
                $y += 8.5; // 文字の間隔を調整
            }

            // 行年
            $pdf->setFont($f, '', 24);
            if ($hondoulist->ageatdeath !== null) {
                $kanjiAge = CommonUtility::parseNumber($hondoulist->ageatdeath);
                $text = $kanjiAge . '才';
                $currentX = $x - 46;
                $y = 180;
                foreach (mb_str_split($text) as $char) {
                    $pdf->Text($currentX, $y, $char);
                    $y += 8.5; // 文字の間隔を調整
                }
            }
            $x -= 67.85;
        }

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HondouList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}