<?php

namespace App\Http\Controllers;

use App\Http\Requests\HaruhiganDocumentRequest;
use App\Models\HaruhiganDocument;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HaruhiganDocumentController extends Controller
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
        $haruhigan_id = session('haruhigan_id');
        return view('haruhigandocuments.create', compact('haruhigan_id'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HaruhiganDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $haruhigan_document = new HaruhiganDocument();

            // モデル->カラム名 = 値でデータを割り当てる
            $haruhigan_document->title = $request->input('title');
            $haruhigan_document->document1 = $request->input('document1');
            $haruhigan_document->document2 = $request->input('document2');
            $haruhigan_document->document3 = $request->input('document3');
            $haruhigan_document->document4 = $request->input('document4');
            $haruhigan_document->document5 = $request->input('document5');
            $haruhigan_document->document6 = $request->input('document6');
            $haruhigan_document->document7 = $request->input('document7');
            $haruhigan_document->document8 = $request->input('document8');
            $haruhigan_document->document9 = $request->input('document9');
            $haruhigan_document->document10 = $request->input('document10');
            $haruhigan_document->document11 = $request->input('document11');
            $haruhigan_document->document12 = $request->input('document12');
            $haruhigan_document->kakui = $request->input('kakui');
            $haruhigan_document->templename = $request->input('templename');
            $haruhigan_document->address = $request->input('address');
            $haruhigan_document->tel = $request->input('tel');

            // データベースに保存
            $haruhigan_document->save();
            DB::commit();
            session()->flash('success', 'はがき文書を登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'はがき文書を登録できませんでした。');
        }

        // リダイレクト
        return redirect()->route('haruhigandocument.edit', ['id' => $haruhigan_document->id]);
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
        $haruhigan_document = HaruhiganDocument::findOrFail($id);
        // セッションからharuhigan_idを取得
        $haruhigan_id = session('haruhigan_id');
        return view('haruhigandocuments.edit', compact('haruhigan_document', 'haruhigan_id'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HaruhiganDocumentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $haruhigan_document = HaruhiganDocument::findOrFail($id);

            // モデル->カラム名 = 値でデータを割り当てる
            $haruhigan_document->title = $request->input('title');
            $haruhigan_document->document1 = $request->input('document1');
            $haruhigan_document->document2 = $request->input('document2');
            $haruhigan_document->document3 = $request->input('document3');
            $haruhigan_document->document4 = $request->input('document4');
            $haruhigan_document->document5 = $request->input('document5');
            $haruhigan_document->document6 = $request->input('document6');
            $haruhigan_document->document7 = $request->input('document7');
            $haruhigan_document->document8 = $request->input('document8');
            $haruhigan_document->document9 = $request->input('document9');
            $haruhigan_document->document10 = $request->input('document10');
            $haruhigan_document->document11 = $request->input('document11');
            $haruhigan_document->document12 = $request->input('document12');
            $haruhigan_document->kakui = $request->input('kakui');
            $haruhigan_document->templename = $request->input('templename');
            $haruhigan_document->address = $request->input('address');
            $haruhigan_document->tel = $request->input('tel');

            // データベースに保存
            $haruhigan_document->save();
            DB::commit();
            session()->flash('success', 'はがき文書を変更しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'はがき文書を変更できませんでした。');
        }

        // リダイレクト
        return redirect()->route('haruhigandocument.edit', ['id' => $id]);
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
        $haruhigan_document = HaruhiganDocument::first();

        if ($haruhigan_document) {
            return redirect()->route('haruhigandocument.edit', $haruhigan_document->id);
        } else {
            return redirect()->route('haruhigandocument.create');
        }
    }
    public function print(Request $request, $id)
    {
        $action = $request->query('action');
        list($pdf, $font, $templateId) = PostcardPrint::createDocumentInstance();
        $haruhigandocument = HaruhiganDocument::findOrFail($id);

        $pdf->addPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        // 表題
        PostcardPrint::DocumentTitle($pdf, $font, $haruhigandocument->title, 85, 7, 22, 8);

        // 各位
        PostcardPrint::DocumentKakui($pdf, $font, $haruhigandocument->kakui, 10, 10, 18, 7);

        // 住所
        PostcardPrint::DocumentAddress($pdf, $font, $haruhigandocument->address, 17.7, 80, 12, 4.5);

        // 寺院名
        PostcardPrint::DocumentTempleName($pdf, $font, $haruhigandocument->templename, 10, 90, 18, 7);

        // 電話番号
        PostcardPrint::DocumentTel($pdf, $font, $haruhigandocument->tel, 4.8, 90, 11, 4);

        // 文書
        $documents = [$haruhigandocument->document1,
                      $haruhigandocument->document2,
                      $haruhigandocument->document3,
                      $haruhigandocument->document4,
                      $haruhigandocument->document5,
                      $haruhigandocument->document6,
                      $haruhigandocument->document7,
                      $haruhigandocument->document8,
                      $haruhigandocument->document9,
                    ];

        PostcardPrint::DocumentDocument($pdf, $font, $documents, 77, 10, 12, 4.2);

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HaruhiganDocument_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
