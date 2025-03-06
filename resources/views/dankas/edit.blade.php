<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">檀家 / 編集</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('danka.update', $danka->id) }}" method="post">
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
                    <tbody>
                        <tr>
                            <input type="hidden" name="danka_id" value="{{ $follower->danka_id }}" />
                            <th class="registration-item">地区名</th>
                            <td class="registration-body">
                                <select id ="area" name="area" class="registration-select">
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                        @if(!is_null(old('area')))
                                            <!-- バリデーションエラー等による再表示時 -->
                                            <option value="{{ $area->value1 }}" {{ old('area') == $area->value1 ? 'selected' : '' }}>{{ $area->value1 }}</option>
                                        @else
                                            <!-- 初期表示時 -->
                                            @if($area->value1 === $danka->area)
                                                <option value="{{ $area->value1 }}" selected>{{ $area->value1 }}</option>
                                            @else
                                                <option value="{{ $area->value1 }}">{{ $area->value1 }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">氏名
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="name" class="registration-input" value="{{ $follower->name }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">氏名かな
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="namekana" class="registration-input" value="{{ $follower->namekana }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">性別</th>
                            <td class="registration-body">
                                <select id ="gender" name="gender" class="registration-select">
                                    <option value=""></option>
                                    @foreach(App\Consts\GenderConsts::GENDER_LIST as $name => $number)
                                        @if(!is_null(old('gender')))
                                            <!-- バリデーションエラー等による再表示時 -->
                                            <option value="{{ $name }}" {{ old('gender') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                        @else
                                            @if($name === $follower->gender)
                                                <option value="{{ $name }}" selected>{{ $name }}</option>
                                            @else
                                                <option value="{{ $name }}">{{ $name }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">生年月日</th>
                            <td class="registration-body">
                                <input type="date" name="birthdate" class="registration-input" value="{{ $follower->birthdate }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">郵便番号</th>
                            <td class="registration-body">
                                <input type="text" name="postcode" class="registration-input" value="{{ $follower->postcode }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所１</th>
                            <td class="registration-body">
                                <input type="text" name="address1" class="registration-input" placeholder="" value="{{ $follower->address1 }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所２</th>
                            <td class="registration-body">
                                <input type="text" name="address2" class="registration-input" placeholder="" value="{{ $follower->address2 }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">電話番号</th>
                            <td class="registration-body">
                                <input type="text" name="tel" class="registration-input" placeholder="09012345678" value="{{ $follower->tel }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">FAX</th>
                            <td class="registration-body">
                                <input type="text" name="fax" class="registration-input" placeholder="09012345678" value="{{ $follower->fax }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">檀家区分</th>
                            <td class="registration-body">
                                <select id ="dankadivision" name="dankadivision" class="registration-select">
                                    <option value=""></option>
                                    @foreach($dankadivisions as $dankadivision)
                                        @if(!is_null(old('dankadivision')))
                                             <!-- バリデーションエラー等による再表示時  -->
                                            <option value="{{ $dankadivision->value1 }}" {{ old('dankadivision') == $dankadivision->value1 ? 'selected' : '' }}>{{ $dankadivision->value1 }}</option>
                                        @else
                                            <!-- 初期表示時 -->
                                            @if($dankadivision->value1 === $danka->dankadivision)
                                                <option value="{{ $dankadivision->value1 }}" selected>{{ $dankadivision->value1 }}</option>
                                            @else
                                                <option value="{{ $dankadivision->value1 }}">{{ $dankadivision->value1 }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">位牌区分</th>
                            <td class="registration-body">
                                <select id ="mortuarytablet" name="mortuarytablet" class="registration-select">
                                    <option value=""></option>
                                    @foreach($mortuarytablets as $mortuarytablet)
                                        @if(!is_null(old('mortuarytablet')))
                                            <!-- バリデーションエラー等による再表示時 -->
                                            <option value="{{ $mortuarytablet->value1 }}" {{ old('mortuarytablet') == $mortuarytablet->value1 ? 'selected' : '' }}>{{ $mortuarytablet->value1 }}</option>
                                        @else
                                            <!-- 初期表示時 -->
                                            @if($mortuarytablet->value1 === $danka->mortuarytablet)
                                                <option value="{{ $mortuarytablet->value1 }}" selected>{{ $mortuarytablet->value1 }}</option>
                                            @else
                                                <option value="{{ $mortuarytablet->value1 }}">{{ $mortuarytablet->value1 }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">寺役職</th>
                            <td class="registration-body">
                                <select id ="position" name="position" class="registration-select">
                                    <option value=""></option>
                                    @foreach($positions as $position)
                                        @if(!is_null(old('position')))
                                            <!-- バリデーションエラー等による再表示時 -->
                                            <option value="{{ $position->value1 }}" {{ old('position') == $position->value1 ? 'selected' : '' }}>{{ $position->value1 }}</option>
                                        @else
                                            <!-- 初期表示時 -->
                                            @if($position->value1 === $follower->position)
                                                <option value="{{ $position->value1 }}" selected>{{ $position->value1 }}</option>
                                            @else
                                                <option value="{{ $position->value1 }}">{{ $position->value1 }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">生前戒名</th>
                            <td class="registration-body">
                                <input type="text" name="seizenkaimyou" class="registration-input" value="{{ $follower->seizenkaimyou }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">職業</th>
                            <td class="registration-body">
                                <select id ="occupation" name="occupation" class="registration-select">
                                    <option value=""></option>
                                    @foreach($occupations as $occupation)
                                        @if(!is_null(old('occupation')))
                                            <!-- バリデーションエラー等による再表示時 -->
                                            <option value="{{ $occupation->value1 }}" {{ old('occupation') == $occupation->value1 ? 'selected' : '' }}>{{ $occupation->value1 }}</option>
                                        @else
                                            <!-- 初期表示時 -->
                                            @if($occupation->value1 === $follower->occupation)
                                                <option value="{{ $occupation->value1 }}" selected>{{ $occupation->value1 }}</option>
                                            @else
                                                <option value="{{ $occupation->value1 }}">{{ $occupation->value1 }}</option>
                                            @endif
                                        <!-- @endif -->
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item"></th>
                            <td class="registration-body">
                                <input type="hidden" name="gozikai" value="0"> 
                                <input type="checkbox" name="gozikai" class="registration-input" value="1" @if(old('gozikai', $danka->gozikai)) checked @endif> 護持会
                                <input type="hidden" name="membershipfee" value="0"> 
                                <input type="checkbox" name="membershipfee" class="registration-input" value="1" @if(old('membershipfee', $danka->membershipfee)) checked @endif> 会費
                                <input type="hidden" name="report" value="0"> 
                                <input type="checkbox" name="report" class="registration-input" value="1" @if(old('report', $danka->report)) checked @endif> 届出
                                <input type="hidden" name="tanagyou" value="0"> 
                                <input type="checkbox" name="tanagyou" class="registration-input" value="1" @if(old('tanagyou', $danka->tanagyou)) checked @endif> 棚経
                                <input type="hidden" name="haruhigan" value="0"> 
                                <input type="checkbox" name="haruhigan" class="registration-input" value="1" @if(old('haruhigan', $danka->haruhigan)) checked @endif> 春彼岸
                                <input type="hidden" name="akihigan" value="0"> 
                                <input type="checkbox" name="akihigan" class="registration-input" value="1" @if(old('akihigan', $danka->akihigan)) checked @endif> 秋彼岸
                                <input type="hidden" name="hanamatsuri" value="0"> 
                                <input type="checkbox" name="hanamatsuri" class="registration-input" value="1" @if(old('hanamatsuri', $danka->hanamatsuri)) checked @endif> 花まつり
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">はがき区分</th>
                            <td class="registration-body">
                                <select id="postcard" name="postcard" class="registration-select">
                                    <option value=""></option>
                                    @foreach(App\Consts\PostcardConsts::POSTCARD_LIST as $name => $number)
                                        @if(!is_null(old('postcard')))
                                            <!-- バリデーションエラー等による再表示時 -->
                                            <option value="{{ $name }}" {{ old('postcard') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                        @else
                                            @if($name === $danka->postcard)
                                                <option value="{{ $name }}" selected>{{ $name }}</option>
                                            @else
                                                <option value="{{ $name }}">{{ $name }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">備考</th>
                            <td class="registration-body">
                                <textarea type="text" name="memo" class="registration-textarea" value="{{ $danka->memo }}">{{ $danka->memo }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('danka.index') }}">
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
          