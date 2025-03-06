<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">自寺院マスタ</div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form action="{{ route('templemaster.store') }}" method="POST">
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

                                <div class="flex flex-row-reverse">
                                    <button type="submit" class="header-btn"
                                    onclick="return confirm('登録します。よろしいですか？')">登録</button>
                                </div>
                                <br>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>