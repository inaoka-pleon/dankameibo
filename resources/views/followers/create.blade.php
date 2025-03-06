<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">家族情報 / 新規登録</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('follower.store') }}" method="post">
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
                        <th class="registration-item">氏名
                            <span class="registration-item-required">必須</span>
                        </th>
                        <td class="registration-body">
                            <input type="text" name="name" class="registration-input" value="{{ old('name') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">氏名かな
                            <span class="registration-item-required">必須</span>
                        </th>
                        <td class="registration-body">
                            <input type="text" name="namekana" class="registration-input" value="{{ old('namekana') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">続柄</th>
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
                        <th class="registration-item">性別</th>
                        <td class="registration-body">
                            <select id ="gender" name="gender" class="registration-select">
                                <option selected></option>
                                @foreach (App\Consts\GenderConsts::GENDER_LIST as $name => $number)
                                    <option value="{{ $name }}" {{ old('gender') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">生年月日</th>
                        <td class="registration-body">
                            <input type="date" name="birthdate" class="registration-input" value="{{ old('birthdate') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">郵便番号</th>
                        <td class="registration-body">
                            <input type="text" name="postcode" class="registration-input" value="{{ old('postcode') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">住所１
                            <span class="registration-item-required">必須</span>
                        </th>
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
                        <th class="registration-item">電話番号
                            <span class="registration-item-required">必須</span>
                        </th>
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
                    <tr>
                        <th class="registration-item">寺役職</th>
                        <td class="registration-body">
                            <select id ="position" name="position" class="registration-select">
                                <option selected></option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->value1 }}" {{ old('position') == $position->value1 ? 'selected' : '' }}>{{ $position->value1 }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">生前戒名</th>
                        <td class="registration-body">
                            <input type="text" name="seizenkaimyou" class="registration-input" value="{{ old('seizenkaimyou') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">職業</th>
                        <td class="registration-body">
                            <select id ="occupation" name="occupation" class="registration-select">
                                <option selected></option>
                                @foreach ($occupations as $occupation)
                                    <option value="{{ $occupation->value1 }}" {{ old('occupation') == $occupation->value1 ? 'selected' : '' }}>{{ $occupation->value1 }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">備考</th>
                        <td class="registration-body">
                            <textarea type="text" name="memo" class="registration-textarea" value="{{ old('memo') }}"></textarea>
                        </td>
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
</x-app-layout>