<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">回忌設定 / 編集</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4">
        </div>
        <div class="py-4">
            <div class="registration">
                <form method="POST" action="{{ route('kaiki.update', $kaiki->id) }}" onsubmit="return confirm('変更します。よろしいですか？')">
                    @csrf
                    @method('PATCH')
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
                            <tr>
                                <th class="registration-item">区分
                                    <span class="registration-item-required">必須</span>
                                </th>
                                <td class="registration-body">
                                    <select id="kaiki_kbn" name="kaiki_kbn" class="registration-select">
                                        @foreach($kaiki_kbns as $kaiki_kbn)
                                            @if($kaiki_kbn->key3 === $kaiki->kaiki_kbn)
                                                <option value="{{ $kaiki_kbn->key3 }}" selected>{{ $kaiki_kbn->value1 }}</option>
                                            @else
                                                <option value="{{ $kaiki_kbn->key3 }}">{{ $kaiki_kbn->value1 }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="kaiki_su" class="my-3">
                                <th class="registration-item">回忌
                                    <span class="registration-item-required">必須</span>
                                </th>
                                <td class="registration-body">
                                    <input type="number" id="kaiki" name="kaiki" class="registration-input" placeholder="" value="{{ old('kaiki', $kaiki->kaiki) }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">回忌名
                                    <span class="registration-item-required">必須</span>
                                </th>
                                <td class="registration-body">
                                    <input type="text" id="kaiki_name" name="kaiki_name" class="registration-input" placeholder="１０文字以内で入力してください" value="{{ old('kaiki_name', $kaiki->kaiki_name) }}" />
                                </td>
                            </tr>
                            <tr>
                                <th class="registration-item">表示・非表示
                                    <span class="registration-item-required">必須</span>
                                </th>
                                <td class="registration-body">
                                    <div class="flex">
                                        <div class="flex">
                                            <input type="radio" id="target_flg_1" name="target_flg" value="1" class="radio" {{ old('target_flg', $kaiki->target_flg) !== 0 ? 'checked' : '' }} />
                                            <label for="target_flg_1" class="ml-2">表示する</label>
                                        </div>
                                        <div class="flex ml-12">
                                            <input type="radio" id="target_flg_2" name="target_flg" value="0" class="radio" {{ old('target_flg', $kaiki->target_flg) === 0 ? 'checked' : '' }} />
                                            <label for="target_flg_2" class="ml-2">表示しない</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="kaiki_kikan" class="my-3" style="display: none;">
                                <th class="registration-item">対象期間
                                    <span class="registration-item-required">必須</span>
                                </th>
                                <td class="registration-body">
                                    <div class="flex">
                                        <div class="flex">
                                            <div class="w-8/9 max-w-3xl block">
                                                <div class="inline-block">
                                                    <select id="from_year_kbn" name="from_year_kbn" class="registration-select-mini">
                                                        <option value=""></option>
                                                        @foreach($from_kbns as $from_kbn)
                                                            @if($from_kbn->key3 === $kaiki->from_year_kbn)
                                                                <option value="{{ $from_kbn->key3 }}" selected>{{ $from_kbn->value1 }}</option>
                                                            @else
                                                                <option value="{{ $from_kbn->key3 }}">{{ $from_kbn->value1 }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <input type="number" name="from_month" class="registration-input-mini" placeholder="月" min="1" max="12" value="{{ old('from_month', $kaiki->from_month) }}" />
                                                    <label class="pt-2">月</label>
                                                    <input type="number" name="from_day" class="registration-input-mini" placeholder="日" min="1" max="31" value="{{ old('from_day', $kaiki->from_day) }}" />
                                                    <label class="pt-2">日</label>
                                                    <label class="pt-2">～</label>
                                                    <select id="to_year_kbn" name="to_year_kbn" class="registration-select-mini">
                                                        <option value=""></option>
                                                        @foreach($to_kbns as $to_kbn)
                                                            @if($to_kbn->key3 === $kaiki->to_year_kbn)
                                                                <option value="{{ $to_kbn->key3 }}" selected>{{ $to_kbn->value1 }}</option>
                                                            @else
                                                                <option value="{{ $to_kbn->key3 }}">{{ $to_kbn->value1 }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <input type="number" name="to_month" class="registration-input-mini" placeholder="月" min="1" max="12" value="{{ old('to_month', $kaiki->to_month) }}" />
                                                    <label class="pt-2">月</label>
                                                    <input type="number" name="to_day" class="registration-input-mini" placeholder="日" min="1" max="31" value="{{ old('to_day', $kaiki->to_day) }}" />
                                                    <label class="pt-2">日</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="houyou_date" class="my-3" style="display: none;">
                                <th class="registration-item">法要日
                                    <span class="registration-item-required">必須</span>
                                </th>
                                <td class="registration-body">
                                    <div class="w-5/6 max-w-2xl block">
                                        <div class="inline-block">
                                            <input type="number" name="houyou_month" class="registration-input-mini" placeholder="月" min="1" max="12" value="{{ old('houyou_month', $kaiki->houyou_month) }}" />
                                            <label class="pt-2">月</label>
                                            <input type="number" name="houyou_day" class="registration-input-mini" placeholder="日" min="1" max="31" value="{{ old('houyou_day', $kaiki->houyou_day) }}" />
                                            <label class="pt-2">日</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-btn">
                        <a class="form-btn2 form-inline-block" href="{{ route('kaiki.index') }}">
                            <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                        </a>
                        <button type="submit" class="form-btn1 form-inline-block">
                            <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">変更</span>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </main>
    <script>
        window.onload = function () {
            dispKaikiKikan();
        };

        document.getElementById("kaiki_kbn").addEventListener("change", function() {
            dispKaikiKikan();
        });

        function dispKaikiKikan() {
            const kaikiKbn = document.getElementById("kaiki_kbn").value;
            const kaikiSu = document.getElementById("kaiki_su");
            const kaikiKikan = document.getElementById("kaiki_kikan");
            const houyouDate = document.getElementById("houyou_date");

            if (kaikiKbn === '2' || kaikiKbn === '3') {
                // 初花または初盆の場合
                kaikiSu.style.display = 'none';
                kaikiKikan.style.display = 'table-row';
                houyouDate.style.display = 'table-row';
            } else if (kaikiKbn === '1') {
                // 百箇日の場合
                kaikiSu.style.display = 'none';
                kaikiKikan.style.display = 'none';
                houyouDate.style.display = 'none';
            } else {
                // 年忌の場合
                kaikiSu.style.display = 'table-row';
                kaikiKikan.style.display = 'none';
                houyouDate.style.display = 'none';
            }
        }
    </script>
</x-app-layout>