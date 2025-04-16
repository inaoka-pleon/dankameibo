<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">はがき汎用</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('generalpostcard.store') }}" method="post">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounred-md p-4 my-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <table class="registration-table">
                        <tbody>
                            <tr>
                                <th class="registration-item">表題</th>
                                <td class="registration-body">
                                    <input type="text" name="title" class="registration-input" placeholder="表題" value="{{ old('title') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文１</th>
                                <td class="registration-body">
                                    <input type="text" name="document1" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document1') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文２</th>
                                <td class="registration-body">
                                    <input type="text" name="document2" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document2') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文３</th>
                                <td class="registration-body">
                                    <input type="text" name="document3" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document3') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文４</th>
                                <td class="registration-body">
                                    <input type="text" name="document4" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document4') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文５</th>
                                <td class="registration-body">
                                    <input type="text" name="document5" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document5') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文６</th>
                                <td class="registration-body">
                                    <input type="text" name="document6" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document6') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文７</th>
                                <td class="registration-body">
                                    <input type="text" name="document7" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document7') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文８</th>
                                <td class="registration-body">
                                    <input type="text" name="document8" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document8') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">文９</th>
                                <td class="registration-body">
                                    <input type="text" name="document9" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document9') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">各位</th>
                                <td class="registration-body">
                                    <input type="text" name="kakui" class="registration-input" placeholder="各位" value="{{ old('kakui') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">住所</th>
                                <td class="registration-body">
                                    <input type="text" name="address" class="registration-input" placeholder="住所" value="{{ old('address') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">寺院名</th>
                                <td class="registration-body">
                                    <input type="text" name="templename" class="registration-input" placeholder="寺院名" value="{{ old('templename') }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">TEL</th>
                                <td class="registration-body">
                                    <input type="text" name="tel" class="registration-input" placeholder="電話番号" value="{{ old('tel') }}" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-btn">
                        <a class="form-btn2 form-inline-block" href="{{ route('generalpostcard.index') }}">
                            <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                        </a>
                        <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('登録します。よろしいですか？')">
                            <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">登録</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>