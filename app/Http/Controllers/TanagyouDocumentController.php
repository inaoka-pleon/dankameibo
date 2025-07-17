<?php

namespace App\Http\Controllers;

use App\Http\Requests\TanagyouDocumentRequest;
use App\Models\TanagyouDocument;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TanagyouDocumentController extends Controller
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
        $tanagyou_id = session(('tanagyou_id'));
        return view('tanagyoudocuments.create', compact('tanagyou_id'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TanagyouDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $tanagyou_document = new TanagyouDocument();

            // モデル->カラム名 = 値でデータを割り当てる
            $tanagyou_document->title = $request->input('title');
            $tanagyou_document->document1 = $request->input('document1');
            $tanagyou_document->document2 = $request->input('document2');
            $tanagyou_document->document3 = $request->input('document3');
            $tanagyou_document->document4 = $request->input('document4');
            $tanagyou_document->document5 = $request->input('document5');
            $tanagyou_document->document6 = $request->input('document6');
            $tanagyou_document->document7 = $request->input('document7');
            $tanagyou_document->document8 = $request->input('document8');
            $tanagyou_document->document9 = $request->input('document9');
            $tanagyou_document->document10 = $request->input('document10');
            $tanagyou_document->document11 = $request->input('document11');
            $tanagyou_document->document12 = $request->input('document12');
            $tanagyou_document->kakui = $request->input('kakui');
            $tanagyou_document->templename = $request->input('templename');
            $tanagyou_document->address = $request->input('address');
            $tanagyou_document->tel = $request->input('tel');

            if (Auth::guard('web')->check()) {
                $tanagyou_document->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }

            // データベースに保存
            $tanagyou_document->save();
            DB::commit();
            session()->flash('success', '棚経文書を登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '棚経文書を登録できませんでした。');
        }

        // リダイレクト
        return redirect()->route('tanagyoudocument.edit', ['id' => $tanagyou_document->id]);
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
        $tanagyou_document = TanagyouDocument::findOrFail($id);
        // セッションからtanagyou_idを取得
        $tanagyou_id = session(('tanagyou_id'));
        return view('tanagyoudocuments.edit', compact('tanagyou_document', 'tanagyou_id'))->with('id', $id);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TanagyouDocumentRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            //モデルのインスタンス化
            $tanagyou_document = TanagyouDocument::findOrFail($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $tanagyou_document->title = $request->input('title');
            $tanagyou_document->document1 = $request->input('document1');
            $tanagyou_document->document2 = $request->input('document2');
            $tanagyou_document->document3 = $request->input('document3');
            $tanagyou_document->document4 = $request->input('document4');
            $tanagyou_document->document5 = $request->input('document5');
            $tanagyou_document->document6 = $request->input('document6');
            $tanagyou_document->document7 = $request->input('document7');
            $tanagyou_document->document8 = $request->input('document8');
            $tanagyou_document->document9 = $request->input('document9');
            $tanagyou_document->document10 = $request->input('document10');
            $tanagyou_document->document11 = $request->input('document11');
            $tanagyou_document->document12 = $request->input('document12');
            $tanagyou_document->kakui = $request->input('kakui');
            $tanagyou_document->templename = $request->input('templename');
            $tanagyou_document->address = $request->input('address');
            $tanagyou_document->tel = $request->input('tel');

            //データベースに保存
            $tanagyou_document->save();

            DB::commit();
            session()->flash('success', '棚経文書を変更しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '棚経文書を変更できませんでした。');
        }
        
        //リダイレクト
        return redirect()->route('tanagyoudocument.edit', ['id' => $id]);
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
        $tanagyou_document = TanagyouDocument::first();

        if ($tanagyou_document) {
            return redirect()->route('tanagyoudocument.edit', $tanagyou_document->id);
        } else {
            return redirect()->route('tanagyoudocument.create');
        }
    }
    public function print(Request $request, $id)
    {
        $action = $request->query('action');
        list($pdf, $font, $templateId) = PostcardPrint::createDocumentInstance();
        $tanagyoudocument = TanagyouDocument::findOrFail($id);

        $pdf->addPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        // 表題
        PostcardPrint::DocumentTitle($pdf, $font, $tanagyoudocument->title, 85, 7, 22, 8);

        // 各位
        PostcardPrint::DocumentKakui($pdf, $font, $tanagyoudocument->kakui, 10, 10, 18, 7);

        // 住所
        PostcardPrint::DocumentAddress($pdf, $font, $tanagyoudocument->address, 17.7, 80, 12, 4.5);

        // 寺院名
        PostcardPrint::DocumentTempleName($pdf, $font, $tanagyoudocument->templename, 10, 90, 18, 7);

        // 電話番号
        PostcardPrint::DocumentTel($pdf, $font, $tanagyoudocument->tel, 4.8, 90, 11, 4);

        // 文書
        $documents = [$tanagyoudocument->document1,
                      $tanagyoudocument->document2,
                      $tanagyoudocument->document3,
                      $tanagyoudocument->document4,
                      $tanagyoudocument->document5,
                      $tanagyoudocument->document6,
                      $tanagyoudocument->document7,
                      $tanagyoudocument->document8,
                      $tanagyoudocument->document9,
                    ];

        PostcardPrint::DocumentDocument($pdf, $font, $documents, 77, 10, 12, 4.2);

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'TanagyouDocument_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
