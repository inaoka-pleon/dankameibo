<?php

namespace App\Http\Controllers\Admin;

use App\Consts\UserRoleConsts;
use App\Http\Controllers\Controller;
use App\Mail\AdminRegist;
use App\Mail\UserRegist;
use App\Models\Jiin;
use App\Models\User;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $put_flg = false;
        if (strcmp($request->searchType, 'user_search') === 0) {
            $put_flg = true;
        }
        // 一覧検索パラメータ取得
        $cond_user = CommonUtility::GetQueryParameter($request, 'cond_user', $put_flg);

        $query = User::query()
                ->leftjoin('jiins', 'users.jiin_id', '=', 'jiins.id')  
                ->select('jiins.id',
                         'users.id',
                         'users.name',
                         'users.name_kana',
                         'users.email');

        if (!empty($cond_user['name'])) {
            $query->where('name', 'like', '%'.$cond_user['name'].'%');
        }
        if (!empty($cond_user['name_kana'])) {
            $query->where('name_kana', 'like', '%'.$cond_user['name_kana'].'%');
        }
        if (!empty($cond_user['email'])) {
            $query->where('email', 'like', '%'.$cond_user['email'].'%');
        }
        $users = $query->paginate(10);

        // 該当件数表示
        $userCount = $users->total();

        return view('admin.user.index', compact('users', 'userCount'))
            ->with('cond_user', $cond_user);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $jiin = Jiin::findOrFail($id);

        return view('admin.user.create', compact('jiin'))->with('jiin_id', $id);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        DB::beginTransaction();

                $jiin_id = $request->input('jiin_id');
                $pass = str_random(10);
    
                $user = new User;
                $user->name = $request->input('name');
                $user->name_kana = $request->input('name_kana');
                $user->email = $request->input('email');
                $user->password = bcrypt($pass);
                $user->memo = $request->input('memo');
                $user->jiin_id = $jiin_id;
    
                $user->save();
                
                Mail::to($request->input('email'))->send(new UserRegist($user->name, $user->email, $pass));
                DB::commit();
                session()->flash('success', 'ユーザー情報を登録しました。');


        return redirect()->route('admin.jiin.show', $jiin_id);
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
        $user = User::find($id);

        return view('admin.user.show', compact('user'));
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
        $user = User::query()->findOrFail($id);
        $this->authorize('update', $user);

        return view('admin.user.edit', compact('user'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        DB::beginTransaction();

            $user = User::find($id);
            $user->name = $request->input('name');
            $user->name_kana = $request->input('name_kana');
            $user->email = $request->input('email');
            $user->memo = $request->input('memo');
            $user->save();

            DB::commit();
            session()->flash('success', 'ユーザー情報を更新しました。');

        return redirect()->route('admin.user.index');
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
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        DB::beginTransaction();
        try {
            User::query()->findOrFail($id)->delete();

            DB::commit();
            session()->flash('success', 'ユーザー情報を削除しました。');
        } catch(Exception $ex) {
            DB::rollBack();
            session()->flash('error', 'ユーザー情報を削除できませんでした。');
        }  

        return redirect()->route('admin.user.index');
        //
    }
}
