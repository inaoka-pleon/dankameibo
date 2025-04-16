<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">檀家 / 新規登録</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-1">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('danka.store') }}" method="post">
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
                        <tr>
                            <th class="registration-item">地区名</th>
                            <td class="registration-body">
                                <select id ="area" name="area" class="registration-select">
                                    <option selected></option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->value1 }}" {{ old('area') == $area->value1 ? 'selected' : '' }}>{{ $area->value1 }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">氏名
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="name" class="registration-input" placeholder="例：姓　名" value="{{ old('name') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">氏名かな
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="namekana" class="registration-input" placeholder="例：せい　めい" value="{{ old('namekana') }}" />
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
                                <input type="text" name="postcode" class="registration-input" placeholder="例：123-4567" value="{{ old('postcode') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所１</th>
                            <td class="registration-body">
                                <input type="text" name="address1" class="registration-input" placeholder="例：〇〇県〇〇市〇〇町" value="{{ old('address1') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所２</th>
                            <td class="registration-body">
                                <input type="text" name="address2" class="registration-input" placeholder="例：〇〇丁目〇〇番地〇〇号" value="{{ old('address2') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">電話番号</th>
                            <td class="registration-body">
                                <input type="text" name="tel" class="registration-input" placeholder="例：090-1234-5678" value="{{ old('tel') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">FAX</th>
                            <td class="registration-body">
                                <input type="text" name="fax" class="registration-input" placeholder="例：090-1234-5678" value="{{ old('fax') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">檀家区分</th>
                            <td class="registration-body">
                                <select id ="dankadivision" name="dankadivision" class="registration-select">
                                    <option selected></option>
                                    @foreach ($dankadivisions as $dankadivision)
                                        <option value="{{ $dankadivision->value1 }}" {{ old('dankadivision') == $dankadivision->value1 ? 'selected' : '' }}>{{ $dankadivision->value1 }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">位牌区分</th>
                            <td class="registration-body">
                                <select id ="mortuarytablet" name="mortuarytablet" class="registration-select">
                                    <option selected></option>
                                    @foreach ($mortuarytablets as $mortuarytablet)
                                        <option value="{{ $mortuarytablet->value1 }}" {{ old('mortuarytablet') == $mortuarytablet->value1 ? 'selected' : '' }}>{{ $mortuarytablet->value1 }}</option>
                                    @endforeach
                                </select>
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
                            <th class="registration-item"></th>
                            <td class="registration-body">
                                <input type="hidden" name="gozikai" value="0">
                                <input type="checkbox" name="gozikai" class="registration-input" value="1" {{ old('gozikai') ? 'checked' : '' }}> 護持会
                                <input type="hidden" name="membershipfee" value="0">
                                <input type="checkbox" name="membershipfee" class="registration-input" value="1" {{ old('membershipfee') ? 'checked' : '' }}> 会費
                                <input type="hidden" name="report" value="0">
                                <input type="checkbox" name="report" class="" value="1" {{ old('report') ? 'checked' : '' }}> 届出
                                <input type="hidden" name="tanagyou" value="0">
                                <input type="checkbox" name="tanagyou" class="" value="1" {{ old('tanagyou') ? 'checked' : '' }}> 棚経
                                <input type="hidden" name="haruhigan" value="0">
                                <input type="checkbox" name="haruhigan" class="" value="1" {{ old('haruhigan') ? 'checked' : '' }}> 春彼岸
                                <input type="hidden" name="akihigan" value="0">
                                <input type="checkbox" name="akihigan" class="" value="1" {{ old('akihigan') ? 'checked' : '' }}> 秋彼岸
                                <input type="hidden" name="hanamatsuri" value="0">
                                <input type="checkbox" name="hanamatsuri" class="" value="1" {{ old('hanamatsuri') ? 'checked' : '' }}> 花まつり
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">はがき区分</th>
                            <td class="registration-body">
                                <select id="postcard" name="postcard" class="registration-select">
                                    <option selected></option>
                                    @foreach(App\Consts\PostcardConsts::POSTCARD_LIST as $name => $number)
                                        <option value="{{ $name }}" {{ old('postcard') == $name ? 'selected' : '' }}>{{ $name }}</option>
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
                    <a class="form-btn2 form-inline-block" href="{{ route('danka.index') }}">
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