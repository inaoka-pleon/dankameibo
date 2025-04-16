<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">大施食会御案内</div>
                <div class="header-buttons">
                    <a id="printButton" class="header-btn">印刷</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('hatsubonlistdocument.update', $hatsubonlist_document->id) }}" method="POST">
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
                        <th class="registration-item">表題</th>
                        <td class="registration-body">
                            <input type="text" name="title" class="registration-input" placeholder="表題" value="{{ old('title', $hatsubonlist_document->title) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文１</th>
                        <td class="registration-body">
                            <input type="text" name="document1" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document1', $hatsubonlist_document->document1) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文２</th>
                        <td class="registration-body">
                            <input type="text" name="document2" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document2', $hatsubonlist_document->document2) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文３</th>
                        <td class="registration-body">
                            <input type="text" name="document3" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document3', $hatsubonlist_document->document3) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文４</th>
                        <td class="registration-body">
                            <input type="text" name="document4" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document4', $hatsubonlist_document->document4) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文５</th>
                        <td class="registration-body">
                            <input type="text" name="document5" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document5', $hatsubonlist_document->document5) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文６</th>
                        <td class="registration-body">
                            <input type="text" name="document6" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document6', $hatsubonlist_document->document6) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文７</th>
                        <td class="registration-body">
                            <input type="text" name="document7" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document7', $hatsubonlist_document->document7) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文８</th>
                        <td class="registration-body">
                            <input type="text" name="document8" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document8', $hatsubonlist_document->document8) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">文９</th>
                        <td class="registration-body">
                            <input type="text" name="document9" class="registration-input" placeholder="３２文字以内で入力してください" value="{{ old('document9', $hatsubonlist_document->document9) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">各位</th>
                        <td class="registration-body">
                            <input type="text" name="kakui" class="registration-input" placeholder="各位" value="{{ old('kakui', $hatsubonlist_document->kakui) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">住所</th>
                        <td class="registration-body">
                            <input type="text" name="address" class="registration-input" placeholder="住所" value="{{ old('address', $hatsubonlist_document->address) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">寺院名</th>
                        <td class="registration-body">
                            <input type="text" name="templename" class="registration-input" placeholder="寺院名" value="{{ old('templename', $hatsubonlist_document->templename) }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">TEL</th>
                        <td class="registration-body">
                            <input type="text" name="tel" class="registration-input" placeholder="TEL" value="{{ old('tel', $hatsubonlist_document->tel) }}" />
                        </td>
                    </tr>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('hatsubonlist.index') }}">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('更新します。よろしいですか？')">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">更新</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
    <script src="/js/dialog.js"></script>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                showListDialog("{{ route('hatsubonlistdocument.print', ['id'=> $hatsubonlist_document->id, 'action' => 'download']) }}", "{{ route('hatsubonlistdocument.print', ['id'=> $hatsubonlist_document->id, 'action' => 'display']) }}", "印刷");
            });
        });
    </script>
</x-app-layout>