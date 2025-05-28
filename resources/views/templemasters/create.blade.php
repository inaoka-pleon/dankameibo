<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <form action="{{ route('templemaster.store') }}" method="POST">
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
            <div class="mb-3 flex flex-col justify-center items-center">
                <div class="header-container">
                    <div class="header-title">自寺院マスタ</div>
                    <div class="header-buttons">
                        <button type="submit" class="header-btn"
                        onclick="return confirm('登録します。よろしいですか？')">登録</button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-center items-center">
                <hr class="mb-4 w-full">
            </div>
                <div class="max-w-7xl mx-auto sm:px-6">
                    <div class="pt-4 text-gray-900">
                        <div>
                            <table class="table-jiin">
                                <tr>
                                    <th class="registration-item">山号</th>
                                    <td class="registration-body">
                                        <input type="text" name="mountainname" class="registration-input" value="{{ old('mountainname') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">寺院名</th>
                                    <td class="registration-body">
                                        <input type="text" name="templename" class="registration-input" value="{{ old('templename') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">住職氏名</th>
                                    <td class="registration-body">
                                        <input type="text" name="jyushokuname" class="registration-input" value="{{ old('jyushokuname') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">郵便番号</th>
                                    <td class="registration-body">
                                        <input type="text" name="postcode" class="registration-input" value="{{ old('postcode') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">住所１</th>
                                    <td class="registration-body">
                                        <input type="text" name="address1" class="registration-input" value="{{ old('address1') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">住所２</th>
                                    <td class="registration-body">
                                        <input type="text" name="address2" class="registration-input" value="{{ old('address2') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">電話番号</th>
                                    <td class="registration-body">
                                        <input type="text" name="tel" class="registration-input" value="{{ old('tel') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">FAX</th>
                                    <td class="registration-body">
                                        <input type="text" name="fax" class="registration-input" value="{{ old('fax') }}" />
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</x-app-layout>