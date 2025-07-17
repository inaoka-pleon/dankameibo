<?php

namespace App\Http\Controllers;

use App\Http\Requests\AkihiganDocumentRequest;
use App\Models\AkihiganDocument;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AkihiganDocumentController extends Controller
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
        $akihigan_id = session('akihigan_id');
        return view('akihigandocuments.create', compact('akihigan_id'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AkihiganDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $akihigan_document = new AkihiganDocument();

            // モデル->カラム名 = 値でデータを割り当てる
            $akihigan_document->title = $request->input('title');
            $akihigan_document->document1 = $request->input('document1');
            $akihigan_document->document2 = $request->input('document2');
            $akihigan_document->document3 = $request->input('document3');
            $akihigan_document->document4 = $request->input('document4');
            $akihigan_document->document5 = $request->input('document5');
            $akihigan_document->document6 = $request->input('document6');
            $akihigan_document->document7 = $request->input('document7');
            $akihigan_document->document8 = $request->input('document8');
            $akihigan_document->document9 = $request->input('document9');
            $akihigan_document->document10 = $request->input('document10');
            $akihigan_document->document11 = $request->input('document11');
            $akihigan_document->document12 = $request->input('document12');
            $akihigan_document->kakui = $request->input('kakui');
            $akihigan_document->templename = $request->input('templename');
            $akihigan_document->address = $request->input('address');
            $akihigan_document->tel = $request->input('tel');

            if (Auth::guard('web')->check()) {
                $akihigan_document->jiin_id = Auth::guard()->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            // データベースに保存
            $akihigan_document->save();
            DB::commit();
            session()->flash('success', 'はがき文書を登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'はがき文書を登録できませんでした。');
        }

        // リダイレクト
        return redirect()->route('akihigandocument.edit', ['id' => $akihigan_document->id]);
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
        $akihigan_document = AkihiganDocument::findOrFail($id);
        // セッションからakihigan_idを取得
        $akihigan_id = session('akihigan_id');
        return view('akihigandocuments.edit', compact('akihigan_document', 'akihigan_id'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AkihiganDocumentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $akihigan_document = AkihiganDocument::findOrFail($id);

            // モデル->カラム名 = 値でデータを割り当てる
            $akihigan_document->title = $request->input('title');
            $akihigan_document->document1 = $request->input('document1');
            $akihigan_document->document2 = $request->input('document2');
            $akihigan_document->document3 = $request->input('document3');
            $akihigan_document->document4 = $request->input('document4');
            $akihigan_document->document5 = $request->input('document5');
            $akihigan_document->document6 = $request->input('document6');
            $akihigan_document->document7 = $request->input('document7');
            $akihigan_document->document8 = $request->input('document8');
            $akihigan_document->document9 = $request->input('document9');
            $akihigan_document->document10 = $request->input('document10');
            $akihigan_document->document11 = $request->input('document11');
            $akihigan_document->document12 = $request->input('document12');
            $akihigan_document->kakui = $request->input('kakui');
            $akihigan_document->templename = $request->input('templename');
            $akihigan_document->address = $request->input('address');
            $akihigan_document->tel = $request->input('tel');

            // データベースに保存
            $akihigan_document->save();
            DB::commit();
            session()->flash('success', 'はがき文書を変更しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'はがき文書を変更できませんでした。');
        }

        // リダイレクト
        return redirect()->route('akihigandocument.edit', ['id' => $id]);
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
        $akihigan_document = AkihiganDocument::first();

        if ($akihigan_document) {
            return redirect()->route('akihigandocument.edit', $akihigan_document->id);
        } else {
            return redirect()->route('akihigandocument.create');
        }
    }
    public function print(Request $request, $id)
    {
        $action = $request->query('action');
        list($pdf, $font, $templateId) = PostcardPrint::createDocumentInstance();
        $akihigandocument = AkihiganDocument::findOrFail($id);

        $pdf->addPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        // 表題
        PostcardPrint::DocumentTitle($pdf, $font, $akihigandocument->title, 85, 7, 22, 8);

        // 各位
        PostcardPrint::DocumentKakui($pdf, $font, $akihigandocument->kakui, 10, 10, 18, 7);

        // 住所
        PostcardPrint::DocumentAddress($pdf, $font, $akihigandocument->address, 17.7, 80, 12, 4.5);

        // 寺院名
        PostcardPrint::DocumentTempleName($pdf, $font, $akihigandocument->templename, 10, 90, 18, 7);

        // 電話番号
        PostcardPrint::DocumentTel($pdf, $font, $akihigandocument->tel, 4.8, 90, 11, 4);

        // 文書
        $documents = [$akihigandocument->document1,
                      $akihigandocument->document2,
                      $akihigandocument->document3,
                      $akihigandocument->document4,
                      $akihigandocument->document5,
                      $akihigandocument->document6,
                      $akihigandocument->document7,
                      $akihigandocument->document8,
                      $akihigandocument->document9,
                    ];

        PostcardPrint::DocumentDocument($pdf, $font, $documents, 77, 10, 12, 4.2);

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'AkihiganDocument_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
