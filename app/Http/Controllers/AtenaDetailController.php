<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtenaHeaderRequest;
use App\Models\AtenaDetail;
use App\Models\AtenaHeader;
use App\Models\Code;
use App\Models\Danka;
use App\Models\Follower;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AtenaDetailController extends Controller
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
     * 新規登録
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        
        return view('atenadetails.create')->with('atena_header_id', $id);
        //
    }

    /**
     * 保存
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AtenaHeaderRequest $request)
    {
        $atena_header_id = $request->input('atena_header_id');
        DB::beginTransaction();
        
        try {            
            // モデルをインスタンス化
            $atena_detail = new AtenaDetail;

            // モデル->カラム名=値で、データを割り当てる
            $atena_detail->name = $request->input('name');
            $atena_detail->namekana = $request->input('namekana');
            $atena_detail->keishou = $request->input('keishou');
            $atena_detail->postcode = $request->input('postcode');
            $atena_detail->address1 = $request->input('address1');
            $atena_detail->address2 = $request->input('address2');
            $atena_detail->postcard = $request->input('postcard');
            $atena_detail->atena_header_id = $atena_header_id;

            if (Auth::guard('web')->check()) {
                $atena_detail->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $atena_detail->save();

            // データベースに保存
            DB::commit();
            session()->flash('success', '宛名情報を登録しました。');
        
        } catch(Exception $ex) {
            // 正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '宛名情報を登録できませんでした。');
        };

        // リダイレクト
        return redirect()->route('atenaheader.show', ['id' => $atena_header_id]);    
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
        
        $atena_detail = AtenaDetail::query()->find($id);

        return view('atenadetails.edit', compact('atena_detail'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AtenaHeaderRequest $request, $id)
    {
        $atena_header_id = $request->input('atena_header_id');

        DB::beginTransaction();

        try{
            $atena_detail = AtenaDetail::findOrFail($id);
            $atena_detail->name = $request->input('name');
            $atena_detail->namekana = $request->input('namekana');
            $atena_detail->keishou = $request->input('keishou');
            $atena_detail->postcode = $request->input('postcode');
            $atena_detail->address1 = $request->input('address1');
            $atena_detail->address2 = $request->input('address2');
            $atena_detail->postcard = $request->input('postcard');
            $atena_detail->atena_header_id = $atena_header_id;

            if (Auth::guard('web')->check()) {
                $atena_detail->jiin_id = Auth::guard('web')->user()->jiin_id;
            } else {
                throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
            }
            $atena_detail->save();

            // 正常に登録できたらコミット
            DB::commit();
            session()->flash('success', '宛名情報を変更しました。');
        } catch(Exception $ex) {
            // 正常に終了しなかったらロールバック
            DB::rollBack();
            session()->flash('error', '宛名情報を変更できませんでした。');
        };

        // リダイレクト
        return redirect()->route('atenaheader.show', ['id' => $atena_header_id]);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($atena_header_id, $id)
    {
        DB::beginTransaction();

        $userJiinId = Auth::guard('web')->user()->jiin_id;
        if (empty($userJiinId)) {
            throw new Exception('ログインユーザーの寺院IDが取得できませんでした。');
        }
        try{
            $atena_detail = AtenaDetail::findOrFail($id);
            $atena_detail->delete();

            DB::commit();
            session()->flash('success', '宛名情報を削除しました。');
        
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', '宛名情報を削除できませんでした。');
        }

        return redirect()->route('atenaheader.show', $atena_header_id);
        //
    }
    // 檀信徒検索
    public function danka_search(Request $request, $id)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'atena_search') === 0) {
            $put_flg = true;
        }

        //地区名のデータ取得
        $areas = Code::query()
                ->where('key1', '=', 'AREA')
                ->get();

        //一覧検索パラメータ取得
        $cond_atena = CommonUtility::GetQueryParameter($request, 'cond_atena', $put_flg);

        $atena_headers = AtenaHeader::query()
                ->select('atena_headers.id',
                        'atena_headers.title')
                ->where('atena_headers.id', '=', $id)
                ->first();

        $query = Danka::query()
                ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                ->select('dankas.id',
                         'followers.name',
                         'followers.namekana',
                         'followers.postcode',
                         'followers.address1',
                         'followers.address2',
                         'followers.tel',
                         'followers.chiefmourner_flg',
                         'followers.deceased_flg',
                         'followers.gender',
                         'dankas.area')
                ->where('followers.deceased_flg', '=', 0)
                ->orderBy('followers.namekana', 'asc');

        if(!empty($cond_atena['area'])) {
            $query->where('area', '=', $cond_atena['area']);
            } 
            if(!empty($cond_atena['name'])) {
            $query->where('name', 'like', '%'.$cond_atena['name'].'%');
            }
            if (!empty($cond_atena['namekana'])) {
            $query->where('namekana', 'like', '%'.$cond_atena['namekana'].'%');
            }

        $danka_searchs = $query->get();

        // 該当件数表示
        $dankaSearchCount = $danka_searchs->count();

        return view('atenadetails.danka_search',  ['id' => $id], compact('atena_headers', 'areas', 'danka_searchs', 'dankaSearchCount'))
            ->with('atena_header_id', $id)
            ->with('cond_atena', $cond_atena);
    }
    // 檀信徒追加
    public function add_danka_data(Request $request, $id) {
        $selectedDankas = $request->input('selected_dankas');
    
        foreach ($selectedDankas as $selectedDanka) {
            $danka = Danka::with('followers')->find($selectedDanka);
            if ($danka && $danka->followers->isNotEmpty()) {
                $follower = $danka->followers->first();
                $atena_detail = new AtenaDetail();
                $atena_detail->atena_header_id = $id;
                $atena_detail->name = $follower->name;
                $atena_detail->namekana = $follower->namekana;
                $atena_detail->keishou = '様'; // 初期値を設定
                $atena_detail->postcode = $follower->postcode;
                $atena_detail->address1 = $follower->address1;
                $atena_detail->address2 = $follower->address2;
                $atena_detail->postcard = '出す'; // 初期値を設定

                $atena_detail->save();
            }
        }
    
        return redirect()->route('atenaheader.show', $id);
    }
}
