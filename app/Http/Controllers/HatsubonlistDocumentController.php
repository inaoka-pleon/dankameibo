<?php

namespace App\Http\Controllers;

use App\Http\Requests\HatsubonlistDocumentRequest;
use App\Models\HatsubonlistDocument;
use App\Services\CommonUtility;
use App\Services\PostcardPrint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HatsubonlistDocumentController extends Controller
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
        return view('hatsubonlistdocuments.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HatsubonlistDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            // モデルのインスタンス化
            $hatsubonlist_document = new HatsubonlistDocument();

            // モデル->カラム名 = 値でデータを割り当てる
            $hatsubonlist_document->title = $request->input('title');
            $hatsubonlist_document->document1 = $request->input('document1');
            $hatsubonlist_document->document2 = $request->input('document2');
            $hatsubonlist_document->document3 = $request->input('document3');
            $hatsubonlist_document->document4 = $request->input('document4');
            $hatsubonlist_document->document5 = $request->input('document5');
            $hatsubonlist_document->document6 = $request->input('document6');
            $hatsubonlist_document->document7 = $request->input('document7');
            $hatsubonlist_document->document8 = $request->input('document8');
            $hatsubonlist_document->document9 = $request->input('document9');
            $hatsubonlist_document->document10 = $request->input('document10');
            $hatsubonlist_document->document11 = $request->input('document11');
            $hatsubonlist_document->document12 = $request->input('document12');
            $hatsubonlist_document->kakui = $request->input('kakui');
            $hatsubonlist_document->templename = $request->input('templename');
            $hatsubonlist_document->address = $request->input('address');
            $hatsubonlist_document->tel = $request->input('tel');

            if (Auth::guard('web')->check()) {
                $hatsubonlist_document->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
 
            // データベースに保存
            $hatsubonlist_document->save();
            DB::commit();
            session()->flash('success', 'はがき文書を登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'はがき文書を登録できませんでした。');
        }

        // リダイレクト
        return redirect()->route('hatsubonlistdocument.edit', ['id' => $hatsubonlist_document->id]);
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
        $hatsubonlist_document = HatsubonlistDocument::findOrFail($id);
        return view('hatsubonlistdocuments.edit', compact('hatsubonlist_document'))->with('id', $id);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HatsubonlistDocumentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            //モデルのインスタンス化
            $hatsubonlist_document = HatsubonlistDocument::findOrFail($id);

            // モデル->カラム名 = 値でデータを割り当てる
            $hatsubonlist_document->title = $request->input('title');
            $hatsubonlist_document->document1 = $request->input('document1');
            $hatsubonlist_document->document2 = $request->input('document2');
            $hatsubonlist_document->document3 = $request->input('document3');
            $hatsubonlist_document->document4 = $request->input('document4');
            $hatsubonlist_document->document5 = $request->input('document5');
            $hatsubonlist_document->document6 = $request->input('document6');
            $hatsubonlist_document->document7 = $request->input('document7');
            $hatsubonlist_document->document8 = $request->input('document8');
            $hatsubonlist_document->document9 = $request->input('document9');
            $hatsubonlist_document->document10 = $request->input('document10');
            $hatsubonlist_document->document11 = $request->input('document11');
            $hatsubonlist_document->document12 = $request->input('document12');
            $hatsubonlist_document->kakui = $request->input('kakui');
            $hatsubonlist_document->templename = $request->input('templename');
            $hatsubonlist_document->address = $request->input('address');
            $hatsubonlist_document->tel = $request->input('tel');

            // データベースに保存
            $hatsubonlist_document->save();
            DB::commit();
            session()->flash('success', 'はがき文書を更新しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'はがき文書を更新できませんでした。');
        }

        // リダイレクト
        return redirect()->route('hatsubonlistdocument.edit', ['id' => $id]);
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
        $hatsubonlist_document = HatsubonlistDocument::first();

        if ($hatsubonlist_document) {
            return redirect()->route('hatsubonlistdocument.edit', $hatsubonlist_document->id);
        } else {
            return redirect()->route('hatsubonlistdocument.create');
        }
    }
    public function print(Request $request, $id)
    {
        $action = $request->query('action');
        list($pdf, $font, $templateId) = PostcardPrint::createDocumentInstance();
        $hatsubonlist_document = HatsubonlistDocument::findOrFail($id);

        $pdf->addPage();
        $pdf->useTemplate($templateId, null, null, null, null, true);

        // 表題
        PostcardPrint::DocumentTitle($pdf, $font, $hatsubonlist_document->title, 85, 7, 22, 8);

        // 各位
        PostcardPrint::DocumentKakui($pdf, $font, $hatsubonlist_document->kakui, 10, 10, 18, 7);

        // 住所
        PostcardPrint::DocumentAddress($pdf, $font, $hatsubonlist_document->address, 17.7, 80, 12, 4.5);

        // 寺院名
        PostcardPrint::DocumentTempleName($pdf, $font, $hatsubonlist_document->templename, 10, 90, 18, 7);

        // 電話番号
        PostcardPrint::DocumentTel($pdf, $font, $hatsubonlist_document->tel, 4.8, 90, 11, 4);

        // 文書
        $documents = [$hatsubonlist_document->document1,
                      $hatsubonlist_document->document2,
                      $hatsubonlist_document->document3,
                      $hatsubonlist_document->document4,
                      $hatsubonlist_document->document5,
                      $hatsubonlist_document->document6,
                      $hatsubonlist_document->document7,
                      $hatsubonlist_document->document8,
                      $hatsubonlist_document->document9,
                    ];

        PostcardPrint::DocumentDocument($pdf, $font, $documents, 77, 10, 12, 4.2);

        // PDFをブラウザに出力
        if ($action === 'download') {
            $pdf_path = 'HatsubonlistDocument_'.CommonUtility::SysDateTime()->format('YmdHis').'.pdf';
            $pdf->Output($pdf_path, 'D');
        } else {
            $pdf->Output('output.pdf', 'I');
        }
    }
}
