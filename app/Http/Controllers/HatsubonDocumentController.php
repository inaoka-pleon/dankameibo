<?php

namespace App\Http\Controllers;

use App\Http\Requests\HatsubonDocumentRequest;
use App\Models\HatsubonDocument;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HatsubonDocumentController extends Controller
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
    public function create()
    {
        $tanagyou_id = session('tanagyou_id');
        return view('hatsubondocuments.create', compact('tanagyou_id'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HatsubonDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $hatsubon_document = new HatsubonDocument();

            // モデル->カラム名 = 値でデータを割り当てる
            $hatsubon_document->title = $request->input('title');
            $hatsubon_document->document1 = $request->input('document1');
            $hatsubon_document->document2 = $request->input('document2');
            $hatsubon_document->document3 = $request->input('document3');
            $hatsubon_document->document4 = $request->input('document4');
            $hatsubon_document->document5 = $request->input('document5');
            $hatsubon_document->document6 = $request->input('document6');
            $hatsubon_document->document7 = $request->input('document7');
            $hatsubon_document->document8 = $request->input('document8');
            $hatsubon_document->document9 = $request->input('document9');
            $hatsubon_document->document10 = $request->input('document10');
            $hatsubon_document->document11 = $request->input('document11');
            $hatsubon_document->document12 = $request->input('document12');
            $hatsubon_document->kakui = $request->input('kakui');
            $hatsubon_document->templename = $request->input('templename');
            $hatsubon_document->address = $request->input('address');
            $hatsubon_document->tel = $request->input('tel');

            // データベースに保存
            $hatsubon_document->save();
            DB::commit();
            session()->flash('success', '初盆文書を登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '初盆文書を登録できませんでした。');
        }

        // リダイレクト
        return redirect()->route('hatsubondocument.edit', ['id' => $hatsubon_document->id]);
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
        $hatsubon_document = HatsubonDocument::findOrFail($id);
        // セッションからtanagyou_idを取得
        $tanagyou_id = session('tanagyou_id');
        return view('hatsubondocuments.edit', compact('hatsubon_document', 'tanagyou_id'))->with('id', $id);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HatsubonDocumentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $hatsubon_document = HatsubonDocument::findOrFail($id);

            // モデル->カラム名 = 値でデータを割り当てる
            $hatsubon_document->title = $request->input('title');
            $hatsubon_document->document1 = $request->input('document1');
            $hatsubon_document->document2 = $request->input('document2');
            $hatsubon_document->document3 = $request->input('document3');
            $hatsubon_document->document4 = $request->input('document4');
            $hatsubon_document->document5 = $request->input('document5');
            $hatsubon_document->document6 = $request->input('document6');
            $hatsubon_document->document7 = $request->input('document7');
            $hatsubon_document->document8 = $request->input('document8');
            $hatsubon_document->document9 = $request->input('document9');
            $hatsubon_document->document10 = $request->input('document10');
            $hatsubon_document->document11 = $request->input('document11');
            $hatsubon_document->document12 = $request->input('document12');
            $hatsubon_document->kakui = $request->input('kakui');
            $hatsubon_document->templename = $request->input('templename');
            $hatsubon_document->address = $request->input('address');
            $hatsubon_document->tel = $request->input('tel');

            // データベースに保存
            $hatsubon_document->save();
            DB::commit();
            session()->flash('success', '初盆文書を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '初盆文書を変更しました。');
        }

        // リダイレクト
        return redirect()->route('hatsubondocument.edit', ['id' => $id]);
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
        $hatsubon_document = HatsubonDocument::first();

        if ($hatsubon_document) {
            return redirect()->route('hatsubondocument.edit', $hatsubon_document->id);
        } else {
            return redirect()->route('hatsubondocument.create');
        }
    }
    public function print(Request $request, $id)
    {
        $action = $request->query('action');
        list($pdf, $font, $templateId) = PostcardPrint::createDocumentInstance();
        $hatsubondocument = HatsubonDocument::findOrFail($id);

        $pdf->addPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        // 表題
        PostcardPrint::DocumentTitle($pdf, $font, $hatsubondocument->title, 85, 7, 22, 8);

        // 各位
        PostcardPrint::DocumentKakui($pdf, $font, $hatsubondocument->kakui, 10, 10, 18, 7);

        // 住所
        PostcardPrint::DocumentAddress($pdf, $font, $hatsubondocument->address, 17.7, 80, 12, 4.5);

        // 寺院名
        PostcardPrint::DocumentTempleName($pdf, $font, $hatsubondocument->templename, 10, 90, 18, 7);

        // 電話番号
        PostcardPrint::DocumentTel($pdf, $font, $hatsubondocument->tel, 4.8, 90, 11, 4);

        // 文書
        $documents = [$hatsubondocument->document1,
                      $hatsubondocument->document2,
                      $hatsubondocument->document3,
                      $hatsubondocument->document4,
                      $hatsubondocument->document5,
                      $hatsubondocument->document6,
                      $hatsubondocument->document7,
                      $hatsubondocument->document8,
                      $hatsubondocument->document9,
                    ];

        PostcardPrint::DocumentDocument($pdf, $font, $documents, 77, 10, 12, 4.2);

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonDocument_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
