<?php

namespace App\Policies;

use App\Models\User; // Userモデルをuse
use App\Models\Admin; // Adminモデルもuse (もしAdminがUser操作を許可されるなら)
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * システム管理者が全ての操作を許可される場合
     * @param  \App\Models\Admin  $admin // Adminモデルを指定 (adminsテーブルのユーザー)
     * @return bool|null
     */
    public function before(Admin $admin)
    {
        // Adminユーザーなら、すべてのPolicyチェックをパスさせる
        // Adminはjiin_idを持たないUserモデルとは別の管理者です
        // $admin->isAdmin() のようなヘルパーメソッドがあれば利用
        return true; // もしisAdmin()というメソッドでAdminかどうか判断するなら $admin->isAdmin()
    }


    /**
     * ユーザーが他のユーザーを作成できるかチェックします。
     *
     * @param  \App\Models\User  $user // 操作を実行しようとしているログインユーザー（usersテーブルのユーザー）
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // ログインしているUserが canRegisterUsers() メソッドを持つかチェック
        // 寺院責任者（Manager）以上の役割を持つユーザーがユーザー登録可能
        return $user->canRegisterUsers();
    }

    /**
     * ユーザーが他のユーザーを表示できるかチェックします。
     * @param  \App\Models\User  $loggedInUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $loggedInUser, User $targetUser)
    {
        // ログインユーザーが、ターゲットユーザーと同じ寺院に所属しているか、
        // または全ユーザーを見れる権限（例: システム管理者）を持つかをチェック
        return $loggedInUser->jiin_id === $targetUser->jiin_id;
        // 必要に応じてシステム管理者も考慮: return $loggedInUser->isAdmin() || $loggedInUser->jiin_id === $targetUser->jiin_id;
    }

    /**
     * ユーザーが他のユーザーを更新できるかチェックします。
     * @param  \App\Models\User  $loggedInUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $loggedInUser, User $targetUser)
    {
        // ログインユーザーが、ターゲットユーザーと同じ寺院に所属し、かつ、
        // 自身が他のユーザーを更新できる権限（例: 寺院責任者）を持つかをチェック
        return $loggedInUser->jiin_id === $targetUser->jiin_id && $loggedInUser->isManager();
        // 例外的に、ユーザーが自分自身のプロフィールを編集できる場合は追加ロジックが必要
        // return ($loggedInUser->id === $targetUser->id) || ($loggedInUser->jiin_id === $targetUser->jiin_id && $loggedInUser->isManager());
    }

    /**
     * ユーザーが他のユーザーを削除できるかチェックします。
     * @param  \App\Models\User  $loggedInUser
     * @param  \App\Models\User  $targetUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $loggedInUser, User $targetUser)
    {
        // ログインユーザーが、ターゲットユーザーと同じ寺院に所属し、かつ、
        // 自身が他のユーザーを削除できる権限（例: 寺院責任者）を持つかをチェック
        // また、自分自身を削除できないようにするロジックも考慮
        return $loggedInUser->jiin_id === $targetUser->jiin_id && $loggedInUser->isManager() && $loggedInUser->id !== $targetUser->id;
    }

}