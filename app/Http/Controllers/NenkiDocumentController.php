<?php

namespace App\Http\Controllers;

use App\Http\Requests\NenkilistDocumentRequest;
use App\Models\NenkiDocument;
use Egulias\EmailValidator\Result\Reason\CharNotAllowed;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NenkiDocumentController extends Controller
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
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $kakocho_id = session('kakocho_id');
        return view('nenkidocuments.create', compact('kakocho_id'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NenkilistDocumentRequest $request)
    {
        DB::beginTransaction();

        try {
            //モデルのインスタンス化
            $nenkidocument = new NenkiDocument();

            //モデル->カラム名 = 値で、データを割り当てる
            $nenkidocument->document1 = $request->input('document1');
            $nenkidocument->document2 = $request->input('document2');
            $nenkidocument->document3 = $request->input('document3');
            $nenkidocument->document4 = $request->input('document4');
            $nenkidocument->document5 = $request->input('document5');
            $nenkidocument->document6 = $request->input('document6');

            if (Auth::guard('web')->check()) {
                $nenkidocument->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院Idが取得できませんでした。');
            }
            //データベースに保存
            $nenkidocument->save();

            DB::commit();
            session()->flash('success', '年忌表文書を登録しました。');

        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '年忌表文書を登録できませんでした。');
        };
        
        //リダイレクト
        return redirect()->route('nenkidocument.edit', ['id' => $nenkidocument->id]);
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
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $nenkidocument = NenkiDocument::findOrFail($id);
        // セッションからkakocho_idを取得
        $kakocho_id = session('kakocho_id');
        return view('nenkidocuments.edit', compact('nenkidocument', 'kakocho_id'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NenkilistDocumentRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            //モデルのインスタンス化
            $nenkidocument = NenkiDocument::findOrFail($id);

            //モデル->カラム名 = 値で、データを割り当てる
            $nenkidocument->document1 = $request->input('document1');
            $nenkidocument->document2 = $request->input('document2');
            $nenkidocument->document3 = $request->input('document3');
            $nenkidocument->document4 = $request->input('document4');
            $nenkidocument->document5 = $request->input('document5');
            $nenkidocument->document6 = $request->input('document6');

            if (Auth::guard('web')->check()) {
                $nenkidocument->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院Idが取得できませんでした。');
            }
            //データベースに保存
            $nenkidocument->save();

            DB::commit();
            session()->flash('success', '年忌表文書を変更しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '年忌表文書を変更できませんでした。');
        };

        //リダイレクト
        return redirect()->route('nenkidocument.edit', ['id' => $id]);

        // return redirect()->route('nenkidocument.createOrEdit');
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
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        $nenkidocument = NenkiDocument::first();

        if ($nenkidocument) {
            return redirect()->route('nenkidocument.edit', $nenkidocument->id);
        } else {
            return redirect()->route('nenkidocument.create');
        }
    }
}
