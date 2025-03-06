<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">過去帳 / 新規登録</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('kakocho.store') }}" method="post">
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
                
                <table class="registration-table">
                    <tbody>
                        <input type="hidden" name="danka_id" value="{{ $danka_id }}" />
                        <tr>
                            <th class="registration-item">俗名
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="zokumyou" class="registration-input" value="{{ old('zokumyou') }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">俗名かな
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="zokumyoukana" class="registration-input" value="{{ old('zokumyoukana') }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">戒名</th>
                            <td class="registration-body">
                                <input type="text" name="kaimyou" class="registration-input" value="{{ old('kaimyou') }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">命日
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <select id="death_era" name="death_era" class="w-1/4">
                                    <option value="">{{ '(未選択)' }}</option>
                                    @foreach($eras as $era)
                                        <option value="{{ $era->id }}" {{ old('death_era') == $era->id ? 'selected' : '' }}>{{ $era->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="death_year" class="w-1/6" min="1" max="99" placeholder="年" value="{{ old('death_year') }}">
                                <label class="pt-2">年</label>
                                <input type="number" name="death_month" class="w-1/6" min="1" max="12" placeholder="月" value="{{ old('death_month') }}">
                                <label class="pt-2">月</label>
                                <input type="number" name="death_day" class="w-1/6" min="1" max="31" placeholder="日" value="{{ old('death_day') }}">
                                <label class="pt-2">日</label>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">行年</th>
                            <td class="registration-body">
                                <input type="text" name="ageatdeath" class="registration-input" value="{{ old('ageatdeath') }}" />
                        </tr>
                        <tr>
                        <th class="registration-item">関係</th>
                            <td class="registration-body">
                                <select id ="relationship" name="relationship" class="registration-select">
                                    <option selected></option>
                                    @foreach ($relationships as $relationship)
                                        <option value="{{ $relationship->value1 }}" {{ old('relationship') == $relationship->value1 ? 'selected' : '' }}>{{ $relationship->value1 }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">備考</th>
                            <td class="registration-body">
                                <input type="text" name="kakocho_memo" class="registration-input" value="{{ old('kakocho_memo') }}" />
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('danka.show', $danka_id) }}">
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