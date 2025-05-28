<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-2 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">過去帳 / 編集</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4">
        </div>
        <div class="registration">
            <form action="{{ route('kakocho.update', $kakocho->id) }}" method="post">
                @csrf
                @method('patch')

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
                        <input type="hidden" name="danka_id" value="{{ $kakocho->danka_id }}" />
                        <tr>
                            <th class="registration-item">俗名
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="zokumyou" class="registration-input" placeholder="例：山田　太郎" value="{{ $kakocho->zokumyou ?? $kakocho->name }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">俗名かな
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="zokumyoukana" class="registration-input" placeholder="例：やまだ　たろう" value="{{ $kakocho->zokumyoukana ?? $kakocho->namekana }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">戒名</th>
                            <td class="registration-body">
                                <input type="text" name="kaimyou" class="registration-input" value="{{ $kakocho->kaimyou ?? $kakocho->seizenkaimyou}}" />
                        </tr>
                        <tr>
                            <th class="registration-item">命日
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <select id="death_era" name="death_era" class="registration-select">
                                    @foreach($eras as $era)
                                        @if(!is_null(old('era')))
                                            <!-- //バリデーションエラー等による再表示時 -->
                                            <option value="{{ $era->id }}" {{ old('death_era') == $era->id ? 'selected' : '' }}>{{ $era->name }}</option>
                                        @else
                                            @if($era->id === $kakocho->death_era)
                                                <option value="{{ $era->id }}" selected>{{ $era->name }}</option>
                                            @else
                                                <option value="{{ $era->id }}">{{ $era->name }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                                <input type="number" name="death_year" class="registration-input-mini" min="1" max="99" placeholder="年" value="{{ old('death_year', $kakocho->death_year) }}">
                                <label class="pt-2">年</label>
                                <input type="number" name="death_month" class="registration-input-mini" min="1" max="12" placeholder="月" value="{{ old('death_month', $kakocho->death_month) }}">
                                <label class="pt-2">月</label>
                                <input type="number" name="death_day" class="registration-input-mini" min="1" max="31" placeholder="日" value="{{ old('death_day', $kakocho->death_day) }}">
                                <label class="pt-2">日</label>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">行年</th>
                            <td class="registration-body">
                                <input type="text" name="ageatdeath" class="registration-input" value="{{ $kakocho->ageatdeath }}" />
                        </tr>
                        <tr>
                            <th class="registration-item">関係</th>
                            <td class="registration-body">
                                <select id ="relationship" name="relationship" class="registration-select">
                                    <option value=""></option>
                                    @foreach($relationships as $relationship)
                                        @if(!is_null(old('relationship')))
                                            <!-- //バリデーションエラー等による再表示時 -->
                                            <option value="{{ $relationship->value1 }}" {{ old('relationship') == $relationship->value1 ? 'selected' : '' }}>{{ $relationship->value1 }}</option>
                                        @else
                                            <!-- 初期表示時 -->
                                            @if($relationship->value1 === $kakocho->relationship)
                                                <option value="{{ $relationship->value1 }}" selected>{{ $relationship->value1 }}</option>
                                            @else
                                                <option value="{{ $relationship->value1 }}">{{ $relationship->value1 }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        <tr>
                            <th class="registration-item">備考</th>
                            <td class="registration-body">
                                <input type="text" name="kakocho_memo" class="registration-input" value="{{ $kakocho->kakocho_memo }}" />
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('danka.show', $kakocho->danka_id )}}">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('変更します。よろしいですか？')">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">変更</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>