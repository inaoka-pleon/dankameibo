<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')
    
    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">年忌表文書</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('nenkidocument.update', $nenkidocument->id) }}" method="POST">
            @csrf
                @method('PATCH')

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounred-md p-4 my-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="registration-table">
                    <tr>
                        <th class="registration-item">文１</th>
                        <td class="registration-body">
                            <input type="text" name="document1" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document1', $nenkidocument->document1) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文２</th>
                        <td class="registration-body">
                            <input type="text" name="document2" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document2', $nenkidocument->document2) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文３</th>
                        <td class="registration-body">
                            <input type="text" name="document3" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document3', $nenkidocument->document3) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文４</th>
                        <td class="registration-body">
                            <input type="text" name="document4" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document4', $nenkidocument->document4) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文５</th>
                        <td class="registration-body">
                            <input type="text" name="document5" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document5', $nenkidocument->document5) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文６</th>
                        <td class="registration-body">
                            <input type="text" name="document6" class="registration-input" placeholder="３６文字以内で入力してください" value="{{ old('document6', $nenkidocument->document6) }}" />
                        </td>
                    </tr>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('nenkilist.index', ['id' => $kakocho_id]) }}">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('更新します。よろしいですか？')">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">更新</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>