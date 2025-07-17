<?php

namespace App\Http\Controllers;

use App\Http\Requests\NenkaiDOcumentRequest;
use App\Models\NenkaiDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NenkaiDocumentController extends Controller
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
        return view('nenkaidocuments.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NenkaiDocumentRequest $request)
    {
        DB::beginTransaction();
        try{
            //モデルのインスタンス化
            $nenkaidocument = new NenkaiDocument();

            //モデル->カラム名 = 値で、データを割り当てる
            $nenkaidocument->document1 = $request->input('document1');
            $nenkaidocument->document2 = $request->input('document2');
            $nenkaidocument->document3 = $request->input('document3');
            $nenkaidocument->document4 = $request->input('document4');
            $nenkaidocument->document5 = $request->input('document5');
            $nenkaidocument->document6 = $request->input('document6');
            $nenkaidocument->document7 = $request->input('document7');
            $nenkaidocument->document8 = $request->input('document8');
            $nenkaidocument->document9 = $request->input('document9');

            if (Auth::guard('web')->check()) {
                $nenkaidocument->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            //データベースに保存
            $nenkaidocument->save();

            DB::commit();
            session()->flash('success', '年回表文書を登録しました。');
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('error', '年回表文書を登録できませんでした。');
        }
        
        //リダイレクト
        return redirect()->route('nenkaidocument.edit', ['id' => $nenkaidocument->id]);
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
        $nenkaidocument = NenkaiDocument::findOrFail($id);
        return view('nenkaidocuments.edit', compact('nenkaidocument'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NenkaiDocumentRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            //モデルのインスタンス化
            $nenkaidocument = NenkaiDocument::findOrFail($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $nenkaidocument->document1 = $request->input('document1');
            $nenkaidocument->document2 = $request->input('document2');
            $nenkaidocument->document3 = $request->input('document3');
            $nenkaidocument->document4 = $request->input('document4');
            $nenkaidocument->document5 = $request->input('document5');
            $nenkaidocument->document6 = $request->input('document6');
            $nenkaidocument->document7 = $request->input('document7');
            $nenkaidocument->document8 = $request->input('document8');
            $nenkaidocument->document9 = $request->input('document9');

            //データベースに保存
            $nenkaidocument->save();

            // 正常に登録出来たらコミット
            DB::commit();
            session()->flash('success', '年回表文書を変更しました。');
        } catch(Exception $ex) {
            // 正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '年回表文書を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('nenkaidocument.edit', ['id' => $id]);
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
        $nenkaidocument = NenkaiDocument::first();

        if ($nenkaidocument) {
            return redirect()->route('nenkaidocument.edit', $nenkaidocument->id);
        } else {
            return redirect()->route('nenkaidocument.create');
        }
    }
}
