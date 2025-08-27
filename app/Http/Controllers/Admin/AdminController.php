<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Mail\AdminRegist;
use App\Models\Admin;
use App\Services\CommonUtility;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 一覧
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $put_flg    = false;
        if (strcmp($request->searchType, 'admin_search') === 0) {
            $put_flg    = true;
        }
        // 一覧検索パラメータ取得
        $cond_admin = CommonUtility::GetQueryParameter($request, 'cond_admin', $put_flg);

        $query      = Admin::query();
        if (!empty($cond_admin['name'])) {
            $query->where('name', 'like', '%'.$cond_admin['name'].'%');
        }
        if (!empty($cond_admin['email'])) {
            $query->where('email', 'like', '%'.$cond_admin['email'].'%');
        }
        $admins = $query->paginate(10);

        // 該当件数表示
        $adminCount = $admins->total();

        return view('admin.admin.index', compact('admins', 'adminCount'))
            ->with('cond_admin', $cond_admin);
    }

    public function show($id)
    {

    }

    /**
     * 新規作成
     * @return View
     */
    public function create(): View
    {
        return view('admin.admin.create');
    }
        /**
     * 保存
     * @param AdminRequest $request
     * @return RedirectResponse
     */
    public function store(AdminRequest $request): RedirectResponse
    {
        try {
            $pass       = str_random(10);

            $admin              = new Admin();
            $admin->name        = $request->input('name');
            $admin->email       = $request->input('email');
            $admin->password    = bcrypt($pass);
            $admin->save();

            Mail::to($request->input('email'))->send(new AdminRegist($admin->name, $admin->email, $pass));

            DB::commit();
            session()->flash('success', '管理者情報を登録しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '管理者情報を登録できませんでした。');
        }
        return redirect()->route('admin.admin.index');
    }

    /**
     * 編集
     * @param $id
     * @return View
     */
    public function edit($id): View
    {
        $admin  = Admin::query()->findOrFail($id);
        return view('admin.admin.edit', compact('admin'));
    }

    /**
     * 更新
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'name'  => 'required|string|max:20',
                'email' => 'required|string|email|max:255',
            ]);

            $admin              = Admin::query()->findOrFail($id);
            $admin->name        = $request->input('name');
            $admin->email       = $request->input('email');
            $admin->save();

            DB::commit();
            session()->flash('success', '管理者情報を更新しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '管理者情報を更新できませんでした。');
        }
        return redirect()->route('admin.admin.index');
    }

    /**
     * 削除
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        try {
            Admin::query()->findOrFail($id)->delete();
            DB::commit();
            session()->flash('success', '管理者情報を削除しました。');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', '管理者情報を削除できませんでした。');
        }
        return redirect()->route('admin.admin.index');
    }
}
