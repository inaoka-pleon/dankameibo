<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\TempleMaster;
use App\Services\CommonUtility;
use Illuminate\Http\Request;
use App\Services\TaiyaData;
use DateTime;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class TaiyaListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $kakocho = Follower::find($id);

        $query = Follower::query()
                ->select('followers.id',
                'followers.kaimyou',
                'followers.zokumyou',
                'followers.deathanniversary',
                'followers.death_era',
                'followers.death_year',
                'followers.death_month',
                'followers.death_day')
        ->where('followers.id', $kakocho->id)
        ->where('followers.deceased_flg', '=', 1);  

        $taiyalists = $query->get();

        $taiyaTables = [];
        $values = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        foreach ($taiyalists as $taiyalist) {
            $taiyaTable = [];
            foreach ($values as $value) {
                $date = TaiyaData::calculateTaiyaDate($taiyalist->deathanniversary, $value);
                $taiyaTable[] = [
                    'title' => TaiyaData::getTaiyaTitle($value),
                    'date' => $date,
                    'dayOfWeek' => CommonUtility::getDayOfweef($date)
                ];
            }

            // 日付順に並び替え
            usort ($taiyaTable, function($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            $taiyaTables[] = $taiyaTable;
        }
        return view('taiyalists.index', compact('kakocho', 'taiyalists', 'taiyaTables'));
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
    public function print(Request $request, $id)
    {
        $action = $request->query('action');
        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation='L', $unit='mm', $format='B5', $unicode=true, $encoding='UTF-8');
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
                        'followers.deathanniversary',
                        'followers.death_month',
                        'followers.death_day')
               ->where('followers.id', $kakocho->id)
               ->where('followers.deceased_flg', '=', 1);

        $taiyalists = $query->get();
        $taiyaTables = [];
        $values = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        foreach ($taiyalists as $taiyalist) {
            $taiyaTable = [];
            foreach ($values as $value) {
                $dateString = TaiyaData::calculateTaiyaDate($taiyalist->deathanniversary, $value);
                $date = new DateTime($dateString);
                $month = $date->format('n');
                $day = $date->format('j');
                $taiyaTable[] = [
                    'date' => $dateString,
                    'month' => $month, 
                    'day' => $day
                ];
            }
            // 日付順に並び替え
            usort ($taiyaTable, function($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            $taiyaTables[] = $taiyaTable;
        }
        $templemasters = TempleMaster::query()->get();

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'TaiyaList.pdf';
        $templatePath = resource_path('template/TaiyaList.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);
    
        // 新規ページをセット
        $pdf->AddPage();

        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($templateId, null, null ,null, null, true);

        $page = 1;

        $pdf->setFont($f, '', 36);
        $text = '中陰逮夜表';
        $x = 234;
        $y = 55;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 15;
        }
        // 戒名
        $pdf->setFont($f, '', 28);
        $text = '戒名';
        $x = 214;
        $y = 16;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 15;
        }     
        // 俗名
        $pdf->setFont($f, '', 28);
        $text = '俗名';
        $x = 199.15;
        $y = 16;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 15;
        }     
        // 命日
        $pdf->setFont($f, '', 28);
        $text = '命日';
        $x = 184.3;
        $y = 16;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 15;
        }    
        // 初七日
        $pdf->setFont($f, '', 28);
        $text = '初七日';
        $x = 169.45;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }
        // 二七日
        $pdf->setFont($f, '', 28);
        $text = '二七日';
        $x = 154.6;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 三七日
        $pdf->setFont($f, '', 28);
        $text = '三七日';
        $x = 139.75;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 四七日
        $pdf->setFont($f, '', 28);
        $text = '四七日';
        $x = 124.9;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 初命日
        $pdf->setFont($f, '', 28);
        $text = '初命日';
        $x = 110.05;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 五七日
        $pdf->setFont($f, '', 28);
        $text = '五七日';
        $x = 95.2;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 六七日
        $pdf->setFont($f, '', 28);
        $text = '六七日';
        $x = 80.35;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 七七日
        $pdf->setFont($f, '', 28);
        $text = '七七日';
        $x = 65.5;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        // 百ヶ日
        $pdf->setFont($f, '', 28);
        $text = '百ヶ日';
        $x = 50.65;
        $y = 14;
        foreach (mb_str_split($text) as $char) {
            $pdf->Text($x, $y, $char);
            $y += 10;
        }  
        foreach ($taiyalists as $taiyalist) { 
            // 戒名
            $text = $taiyalist->kaimyou;
            $charCount = mb_strlen($text);
            $x = 214;
            $y = 51;
            // 戒名が11文字以下
            if ($charCount <= 11) {
                $pdf->setFont($f, '', 28);
                $lineHeight = 9;
            } else {
                // 12文字以上
                $pdf->setFont($f, '', 24);
                $x += 0.75;
                $lineHeight = 7.5;
            }
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += $lineHeight;
            } 

            // 俗名
            $text = $taiyalist->zokumyou;
            $charCount = mb_strlen($text);
            $x = 199.15;
            $y = 51;
            // 戒名が13文字以下
            if ($charCount <= 13) {
                $pdf->setFont($f, '', 28);
                $lineHeight = 9;
            } else {
                // 14文字以上18文字以下
                $pdf->setFont($f, '', 22);
                $x += 1;
                $lineHeight = 6.5;
            }
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += $lineHeight;
            } 

            // 命日
            $pdf->setFont($f, '', 28);
            $text = CommonUtility::parseNumber($taiyalist->death_month);
            $x = 184.3;
            $y = 73;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 9;
            }
            $pdf->setFont($f, '', 28);
            $text = '月';
            $x = 184.3;
            $y = 91;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 9;
            }
            $pdf->setFont($f, '', 28);
            $text = CommonUtility::parseNumber($taiyalist->death_day);
            $x = 184.3;
            $y = 110;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 9;
            }
            $pdf->setFont($f, '', 28);
            $text = '日';
            $x = 184.3;
            $y = 138;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 9;
            }
        }
        $x = 169.45;
        foreach ($taiyaTables as $taiyaTable) {
            foreach ($taiyaTable as $taiya) {
                $pdf->setFont($f, '', 28);
                $text = CommonUtility::parseNumber($taiya['month']);
                $y = 73;
                foreach (mb_str_split($text) as $char) {
                    $pdf->Text($x, $y, $char);
                    $y += 9;
                }
                $pdf->setFont($f, '', 28);
                $text = '月';
                $y = 91;
                foreach (mb_str_split($text) as $char) {
                    $pdf->Text($x, $y, $char);
                    $y += 9;
                }
                $pdf->setFont($f, '', 28);
                $text = CommonUtility::parseNumber($taiya['day']);
                $y = 110;
                foreach (mb_str_split($text) as $char) {
                    $pdf->Text($x, $y, $char);
                    $y += 9;
                }
                $pdf->setFont($f, '', 28);
                $text = '日';
                $y = 138;
                foreach (mb_str_split($text) as $char) {
                    $pdf->Text($x, $y, $char);
                    $y += 9;
                }
                $x -= 14.85;
            }
        }
        foreach ($templemasters as $templemaster)
        {
            // 山号
            $pdf->setFont($f, '', 20);
            $text = $templemaster->mountainname;
            $x = 30;
            $y = 70;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 11;
            }
            // 寺院名
            $pdf->setFont($f, '', 28);
            $text = $templemaster->templename;
            $x = 28.5;
            $y = 102;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 18;
            }
            // 住所
            $pdf->setFont($f, '', 20);
            $text = $templemaster->address1. $templemaster->address2;
            $x = 17;
            $y = 81;
            foreach (mb_str_split($text) as $char) {
                $pdf->Text($x, $y, $char);
                $y += 7;
            }
            // TEL
            $pdf->setFont($f, '', 16);
            $text = parse_number($templemaster->tel);
            $x = 9.5;
            $y = 81;
            foreach (mb_str_split($text) as $char) {
                if ($char === '-') {
                    $pdf->StartTransform();
                    $pdf->Rotate(270, $x + 3.3, $y + 4.8);
                    $pdf->Text($x, $y, $char);
                    $pdf->StopTransform();
                } else {
                    $pdf->Text($x, $y, $char);
                }
                $y += 5.5;
            }
        }
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TaiyaList_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
