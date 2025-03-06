<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralPostcardRequest;
use App\Models\GeneralPostcard;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class GeneralPostcardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = GeneralPostcard::query()
                ->select('general_postcards.id',
                         'general_postcards.title',
                         'general_postcards.created_at')
                ->orderby('general_postcards.created_at', 'desc');

        $general_postcards = $query->get();

        // 作成日を西暦から和暦に変換
        foreach ($general_postcards as $general_postcard) {
            $created_at_date = Carbon::parse($general_postcard->created_at)->format('Y-m-d');
            $CreatedEra = CommonUtility::ADtoJACalendarConv($created_at_date);
    
            $general_postcard->CreatedEraName = $CreatedEra['era_name'] ?? '';
            $general_postcard->CreatedEraYear = $CreatedEra['era_year'] ?? '';
            $general_postcard->CreatedMonth = $CreatedEra['month'] ?? '';
            $general_postcard->CreatedDay = $CreatedEra['day'] ?? '';
        }

        return view('generalpostcards.index', compact('general_postcards'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('generalpostcards.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GeneralPostcardRequest $request)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $general_postcard = new GeneralPostcard();

            // モデル->カラム名 = 値でデータを割り当てる
            $general_postcard->title = $request->input('title');
            $general_postcard->document1 = $request->input('document1');
            $general_postcard->document2 = $request->input('document2');
            $general_postcard->document3 = $request->input('document3');
            $general_postcard->document4 = $request->input('document4');
            $general_postcard->document5 = $request->input('document5');
            $general_postcard->document6 = $request->input('document6');
            $general_postcard->document7 = $request->input('document7');
            $general_postcard->document8 = $request->input('document8');
            $general_postcard->document9 = $request->input('document9');
            $general_postcard->document10 = $request->input('document10');
            $general_postcard->document11 = $request->input('document11');
            $general_postcard->document12 = $request->input('document12');
            $general_postcard->kakui = $request->input('kakui');
            $general_postcard->templename = $request->input('templename');
            $general_postcard->address = $request->input('address');
            $general_postcard->tel = $request->input('tel');

            // データベースに保存
            $general_postcard->save();
            DB::commit();
            session()->flash('success', '汎用はがきを登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '汎用はがきを登録できませんでした。');

        }

        // リダイレクト
        return redirect()->route('generalpostcard.index');
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
        $general_postcard = GeneralPostcard::findOrFail($id);
        // dd($general_postcard);
        return view('generalpostcards.edit', compact('general_postcard'))->with('id', $id);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GeneralPostcardRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $general_postcard = GeneralPostcard::findOrFail($id);

            // モデル->カラム名 = 値でデータを割り当てる
            $general_postcard->title = $request->input('title');
            $general_postcard->document1 = $request->input('document1');
            $general_postcard->document2 = $request->input('document2');
            $general_postcard->document3 = $request->input('document3');
            $general_postcard->document4 = $request->input('document4');
            $general_postcard->document5 = $request->input('document5');
            $general_postcard->document6 = $request->input('document6');
            $general_postcard->document7 = $request->input('document7');
            $general_postcard->document8 = $request->input('document8');
            $general_postcard->document9 = $request->input('document9');
            $general_postcard->document10 = $request->input('document10');
            $general_postcard->document11 = $request->input('document11');
            $general_postcard->document12 = $request->input('document12');
            $general_postcard->kakui = $request->input('kakui');
            $general_postcard->templename = $request->input('templename');
            $general_postcard->address = $request->input('address');
            $general_postcard->tel = $request->input('tel');

            // データベースに保存
            $general_postcard->save();
            DB::commit();
            session()->flash('success', '汎用はがきを変更しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '汎用はがきを変更できませんでした。');

        }

        // リダイレクト
        return redirect()->route('generalpostcard.edit', $id);
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
        list($pdf, $font, $templateId) = PostcardPrint::createDocumentInstance();
        $generalpostcard = GeneralPostcard::findOrFail($id);

        $pdf->addPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        // 表題
        PostcardPrint::DocumentTitle($pdf, $font, $generalpostcard->title, 85, 7, 22, 8);

        // 各位
        PostcardPrint::DocumentKakui($pdf, $font, $generalpostcard->kakui, 10, 10, 18, 7);

        // 住所
        PostcardPrint::DocumentAddress($pdf, $font, $generalpostcard->address, 17.7, 80, 12, 4.5);

        // 寺院名
        PostcardPrint::DocumentTempleName($pdf, $font, $generalpostcard->templename, 10, 90, 18, 7);

        // 電話番号
        PostcardPrint::DocumentTel($pdf, $font, $generalpostcard->tel, 4.8, 90, 11, 4);

        // 文書
        $documents = [$generalpostcard->document1,
                      $generalpostcard->document2,
                      $generalpostcard->document3,
                      $generalpostcard->document4,
                      $generalpostcard->document5,
                      $generalpostcard->document6,
                      $generalpostcard->document7,
                      $generalpostcard->document8,
                      $generalpostcard->document9,
                    ];

        PostcardPrint::DocumentDocument($pdf, $font, $documents, 77, 10, 12, 4.2);
        
        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'Backprint_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
