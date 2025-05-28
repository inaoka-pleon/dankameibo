<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-2 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">年忌表文書</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4">
        </div>
        <div class="registration">
            <form action="{{ route('nenkidocument.store') }}" method="POST">

                @csrf

                <table class="registration-table">
                    <tr>
                        <th class="registration-item">文１</th>
                        <td class="registration-body">
                            <input type="text" name="document1" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document1') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文２</th>
                        <td class="registration-body">
                            <input type="text" name="document2" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document2') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文３</th>
                        <td class="registration-body">
                            <input type="text" name="document3" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document3') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文４</th>
                        <td class="registration-body">
                            <input type="text" name="document4" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document4') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文５</th>
                        <td class="registration-body">
                            <input type="text" name="document5" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document5') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文６</th>
                        <td class="registration-body">
                            <input type="text" name="document6" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document6') }}" />
                        </td>
                    </tr>
                </table>
                <div class="form-btn">
                    <div class="inline-flex -mt-1">
                        <a class="form-btn2 form-inline-block" href="{{ route('nenkilist.index', ['id' => $kakocho_id]) }}">
                            <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                        </a>
                    </div>
                    <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('登録します。よろしいですか？')">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">登録</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>