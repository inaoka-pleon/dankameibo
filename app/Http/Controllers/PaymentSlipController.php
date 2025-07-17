<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentSlipRequest;
use App\Models\PaymentSlip;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class PaymentSlipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $copies = $request->input('copies') ?: session('copies', 1);
        session()->put('copies', $copies);
        return view('paymentslips.create')->with('copies', $copies);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentSlipRequest $request)
    {
        DB::beginTransaction();

        try{
            //モデルをインスタンス化
            $paymentslip = new PaymentSlip();

            //モデル->カラム名=値で、データを割り当てる
            $paymentslip->accountno1 = $request->input('accountno1');
            $paymentslip->accountno2 = $request->input('accountno2');
            $paymentslip->accountno3 = $request->input('accountno3');
            $paymentslip->name = $request->input('name');
            $paymentslip->price = $request->input('price');

            if (Auth::guard('web')->check()) {
                $paymentslip->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }

            //データベースに保存
            $paymentslip->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '払込票を登録しました。');
            
        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '払込票を登録できませんでした。');
        };

        //リダイレクト
        return redirect()->route('paymentslip.createOrEdit');
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
    public function edit(Request $request, $id)
    {
        $paymentslip = PaymentSlip::findOrFail($id);
        $copies = $request->input('copies') ?: session('copies', 1);
        session()->put('copies', $copies);

        return view('paymentslips.edit', compact('paymentslip'))->with('copies', $copies);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentSlipRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $paymentslip = PaymentSlip::findOrFail($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $paymentslip->accountno1 = $request->input('accountno1');
            $paymentslip->accountno2 = $request->input('accountno2');
            $paymentslip->accountno3 = $request->input('accountno3');
            $paymentslip->name = $request->input('name');
            $paymentslip->price = $request->input('price');

            //データベースに保存
            $paymentslip->save();

            //正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '払込票を変更しました。');
            
        } catch(Exception $ex) {
            //正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '払込票を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('paymentslip.createOrEdit');
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
    public function createOrEdit()
    {
        $paymentslip = PaymentSlip::first(); // ここでデータの存在を確認

        if ($paymentslip) {
            return view('paymentslips.edit', compact('paymentslip'));
        } else {
            return view('paymentslips.create');
        }
    }
    public function updateCopies(Request $request)
    {
        // 部数をセッションに保存
        $copies = $request->input('copies');
        session()->put('copies', $copies);

        return response()->json(['status' => 'success', 'copies' => $copies]);
    }
    public function print(Request $request)
    {
        $action = $request->query('action');
        $copies = $request->input('copies', session('copies', 1));

        // FPDIインスタンス生成
        $pdf = new Fpdi($orientation='L', $unit='mm', $format = array(114, 180), $unicode=true, $encoding='UTF-8');
        // ページ設定（最初に設置しないとヘッダーに罫線が入ってしまう）
        $pdf = new Fpdi($orientation, $unit, $format, $unicode, $encoding);

        $pdf->setAutoPageBreak(false);
        $pdf->setTopMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(0);
        $pdf->setPrintFooter(false);

        $font = new TCPDF_FONTS();
        $f = $font->addTTFfont('./fonts/ipaexm.ttf');

        $paymentslips = PaymentSlip::query()
                    ->get();

        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $tpl_name = 'PaymentSlip.pdf';
        $templatePath = resource_path('template/PaymentSlip.pdf');
        $pdf->setSourceFile($templatePath);

        // テンプレートPDFの1ページ目を読み込み
        $templateId = $pdf->importPage(1);

        $page = 1;

        foreach ($paymentslips as $paymentslip) {
            for ($i = 0; $i < $copies; $i++) {
                // 新規ページをセット
                $pdf->AddPage();
                // 読み込んだページをテンプレートに使用
                $pdf->useTemplate($templateId, null, null, null, null, true);

                // 口座番号１
                $pdf->setFont($f, '', 14);
                $endX = 24.2; // 最後の文字のX座標
                $spacing = 5.05; // 文字間隔
                $x = $endX - (strlen($paymentslip->accountno1) - 1) * $spacing;
                foreach (str_split($paymentslip->accountno1) as $char) {
                    $pdf->Text($x, 16, $char);
                    $x += $spacing;
                }
                // 口座番号２
                $pdf->setFont($f, '', 14);
                $pdf->Text(31.75, 16, $paymentslip->accountno2);

                // 口座番号３
                $pdf->setFont($f, '', 14);
                $endX = 69.65; // 最後の文字のX座標
                $spacing = 5.05; // 文字間隔
                $x = $endX - (strlen($paymentslip->accountno3) - 1) * $spacing;
                foreach (str_split($paymentslip->accountno3) as $char) {
                    $pdf->Text($x, 16, $char);
                    $x += $spacing;
                }

                // 金額
                $pdf->setFont($f, '', 14);
                $endX = 115.5; // 最後の文字のX座標
                $spacing = 5.05; // 文字間隔
                $x = $endX - (strlen($paymentslip->price) - 1) * $spacing;
                foreach (str_split($paymentslip->price) as $char) {
                    $pdf->Text($x, 16, $char);
                    $x += $spacing;
                }

                // 加入者名
                $pdf->setFont($f, '', 18);
                $pdf->Text(13, 24, $paymentslip->name);

                // 口座番号１
                $pdf->setFont($f, '', 14);
                $endX = 156.2; // 最後の文字のX座標
                $spacing = 5.05; // 文字間隔
                $x = $endX - (strlen($paymentslip->accountno1) - 1) * $spacing;
                foreach (str_split($paymentslip->accountno1) as $char) {
                    $pdf->Text($x, 16, $char);
                    $x += $spacing;
                }

                // 口座番号２
                $pdf->setFont($f, '', 14);
                $pdf->Text(163.95, 16, $paymentslip->accountno2);

                // 口座番号３
                $pdf->setFont($f, '', 14);
                $endX = 171.3; // 最後の文字のX座標
                $spacing = 5.05; // 文字間隔
                $x = $endX - (strlen($paymentslip->accountno3) - 1) * $spacing;
                foreach (str_split($paymentslip->accountno3) as $char) {
                    $pdf->Text($x, 25.5, $char);
                    $x += $spacing;
                }

                // 金額
                $pdf->setFont($f, '', 14);
                $endX = 171.3; // 最後の文字のX座標
                $spacing = 5.05; // 文字間隔
                $x = $endX - (strlen($paymentslip->price) - 1) * $spacing;
                foreach (str_split($paymentslip->price) as $char) {
                    $pdf->Text($x, 47.6, $char);
                    $x += $spacing;
                }

                // 加入者名
                $pdf->setFont($f, '', 18);
                $pdf->Text(138, 35, $paymentslip->name);
                $page += 1;
            }
        }
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'PaymentSlip_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}