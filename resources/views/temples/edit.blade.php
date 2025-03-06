<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">寺院 / 編集</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('temple.update', $temple->id) }}" method="post">
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
                            <th class="registration-item">宗務所</th>
                            <td class="registration-body">
                                <select id ="templeoffice" name="templeoffice" class="registration-select">
                                    <option value=""></option>
                                    @foreach ($templeoffices as $templeoffice)
                                            @if($templeoffice->value1 === $temple->templeoffice)
                                                <option value="{{ $templeoffice->value1 }}" selected>{{ $templeoffice->value1 }}</option>
                                            @else
                                                <option value="{{ $templeoffice->value1 }}">{{ $templeoffice->value1 }}</option>
                                            @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th class="registration-item">教区</th>
                            <td class="registration-body">
                                <select id = "parish" name="parish" class="registration-select">
                                    <option value=""></option>
                                    @foreach(App\Consts\ParishConsts::PARISH_LIST as $name => $number)
                                        @if($name === $temple->parish)
                                            <option value="{{ $name }}" selected>{{ $name }}</option>
                                        @else
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">寺籍番号</th>
                            <td class="registration-body">
                                <input type="text" name="no" class="registration-input" value="{{ $temple->no }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">寺院名
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="templename" class="registration-input" value="{{ $temple->templename }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">寺院名かな
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="templenamekana" class="registration-input" value="{{ $temple->templenamekana }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">資格</th>
                            <td class="registration-body">
                                <select id ="qualification" name="qualification" class="registration-select">
                                    <option value=""></option>
                                    @foreach ($qualifications as $qualification)
                                        @if($qualification->value1 === $member->qualification)
                                            <option value="{{ $qualification->value1 }}" selected>{{ $qualification->value1 }}</option>
                                        @else
                                            <option value="{{ $qualification->value1 }}">{{ $qualification->value1 }}</option>
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
                                <input type="text" name="name" class="registration-input" value="{{ $member->name }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">氏名かな
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="namekana" class="registration-input" value="{{ $member->namekana }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">敬称</th>
                            <td class="registration-body">
                                <select id ="title" name="title" class="registration-select">
                                    <option value=""></option>
                                    @foreach ($titles as $title)
                                        @if($title->value1 === $member->title)
                                            <option value="{{ $title->value1 }}" selected>{{ $title->value1 }}</option>
                                        @else
                                            <option value="{{ $title->value1 }}">{{ $title->value1 }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">脇敬称</th>
                            <td class="registration-body">
                                <select id ="subtitle" name="subtitle" class="registration-select">
                                    <option value=""></option>
                                    @foreach ($subtitles as $subtitle)
                                        @if($subtitle->value1 === $member->subtitle)
                                            <option value="{{ $subtitle->value1 }}" selected>{{ $subtitle->value1 }}</option>
                                        @else
                                            <option value="{{ $subtitle->value1 }}">{{ $subtitle->value1 }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">郵便番号</th>
                            <td class="registration-body">
                                <input type="text" name="postcode" class="registration-input" value="{{ $member->postcode }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所１
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="address1" class="registration-input" value="{{ $member->address1 }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所２</th>
                            <td class="registration-body">
                                <input type="text" name="address2" class="registration-input" value="{{ $member->address2 }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">電話番号</th>
                            <td class="registration-body">
                                <input type="text" name="tel" class="registration-input" value="{{ $member->tel }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">FAX</th>
                            <td class="registration-body">
                                <input type="text" name="fax" class="registration-input" value="{{ $member->fax }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">手紙区分</th>
                            <td class="registration-body">
                                <select id="letterdivision" name="letterdivision" class="registration-select">
                                    <option value=""></option>
                                    @foreach(App\Consts\LetterdivisionConsts::LETTERDIVISION_LIST as $name => $number)
                                        @if($name === $member->letterdivision)
                                            <option value="{{ $name }}" selected>{{ $name }}</option>
                                        @else
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">年賀状区分</th>
                            <td class="registration-body">
                                <select id="newyearscarddivision" name="newyearscarddivision" class="registration-select">
                                    <option value=""></option>
                                    @foreach(App\Consts\NewyearscarddivisionConsts::NEWYEARSCARDDIVISION_LIST as $name => $number)
                                        @if($name === $member->newyearscarddivision)
                                            <option value="{{ $name }}" selected>{{ $name }}</option>
                                        @else
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">暑中見舞区分</th>
                            <td class="registration-body">
                                <select id="summergreetingdivision" name="summergreetingdivision" class="registration-select">
                                    <option selected></option>
                                    @foreach(App\Consts\SummergreetingdivisionConsts::SUMMERGREETINGDIVISION_LIST as $name => $number)
                                        @if($name === $member->summergreetingdivision)
                                            <option value="{{ $name }}" selected>{{ $name }}</option>
                                        @else
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">師</th>
                            <td class="registration-body">
                                <select id ="teacher" name="teacher" class="registration-select">
                                    <option value=""></option>
                                    @foreach ($teachers as $teacher)
                                        @if($teacher->value1 === $member->teacher)
                                            <option value="{{ $teacher->value1 }}" selected>{{ $teacher->value1 }}</option>
                                        @else
                                            <option value="{{ $teacher->value1 }}">{{ $teacher->value1 }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">山号</th>
                            <td class="registration-body">
                                <input type="text" name="mountainname" class="registration-input" value="{{ $temple->mountainname }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">寺格</th>
                            <td class="registration-body">
                                <select id ="jikaku" name="jikaku" class="registration-select">
                                    <option value=""></option>
                                    @foreach ($jikakus as $jikaku)
                                        @if($jikaku->value1 === $temple->jikaku)
                                            <option value="{{ $jikaku->value1 }}" selected>{{ $jikaku->value1 }}</option>
                                        @else
                                            <option value="{{ $jikaku->value1 }}">{{ $jikaku->value1 }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th class="registration-item">仏教会</th>
                            <td class="registration-body">
                                <select id ="buddhistfederation" name="buddhistfederation" class="registration-select">
                                    <option selected></option>
                                        @foreach(App\Consts\BuddhistfederationConsts::BUDDHISTFEDERATION_LIST as $name => $number)
                                            @if($name === $temple->buddhistfederation)
                                                <option value="{{ $name }}" selected>{{ $name }}</option>
                                            @else
                                                <option value="{{ $name }}">{{ $name }}</option>
                                            @endif
                                        @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">入会名</th>
                            <td class="registration-body">
                                @foreach($nyuukai_names as $nyuukai_name)
                                    <div class="registratoin-checkbox">
                                        <input type="checkbox" id="nyuukai_name_{{ $nyuukai_name->value1 }}" name="nyuukai_name[]" value="{{ $nyuukai_name->value1 }}"
                                            @if(in_array($nyuukai_name->value1, $selected_nyuukai_names)) checked @endif>
                                        <label for="nyuukai_name_{{ $nyuukai_name->value1 }}">{{ $nyuukai_name->value1 }}</label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">備考</th>
                            <td class="registration-body">
                                <textarea type="text" name="memo" class="registration-textarea" value="{{ $temple->memo }}">{{ $temple->memo }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('temple.index') }}">
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