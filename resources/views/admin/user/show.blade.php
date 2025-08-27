<x-admin-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')
    
    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">ユーザー詳細</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6" >
                <div class="">
                    <div class="">
                            <table class="tableSample">
                                <tr>
                                    <th>ユーザー名</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>ユーザー名かな</th>
                                    <td>{{ $user->name_kana }}</td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>備考</th>
                                    <td>{{ $user->memo }}</td>
                                </tr>
                            </table>
                            <div class="form-btn">
                                <a class="form-btn2 form-inline-block" href="{{ route('admin.user.index') }}">
                                    <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-admin-layout>