<x-admin-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">ユーザー / 新規登録</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('admin.user.store') }}" method="post">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4 mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <table class="registration-table">
                    <tbody>
                        <input type="hidden" name="jiin_id" value="{{ $jiin_id }}" />
                        <tr>
                            <th class="registration-item">寺院名</th>
                            <td class="registration-body">{{ $jiin->jiin_name }}</td>
                        </tr>
                        <tr>
                            <th class="registration-item">ユーザー名
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="name" class="registration-input" placeholder="例：山田　太郎" value="{{ old('name') }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">ユーザー名かな</th>
                            <td class="registration-body">
                                <input type="text" name="name_kana" class="registration-input" placeholder="例：やまだ　たろう" value="{{ old('name_kana') }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">メールアドレス
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="email" class="registration-input" placeholder="例：sample@sample.com" value="{{ old('email') }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">備考</th>
                            <td class="registration-body">
                                <input type="text" name="memo" class="registration-input" value="{{ old('memo') }}" />
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('admin.jiin.show', $jiin_id) }}">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('登録します。よろしいですか？')">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">登録</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>