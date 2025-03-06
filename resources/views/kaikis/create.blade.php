<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            回忌設定 / 新規作成
        </h2>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('kaiki.store') }}" method="POST" onsubmit="return confirm('保存します。よろしいですか？');">
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
                <div class="my-3">
                    <label for="kaiki_kbn" class="">区分 <span class="required-mark">必須</span></label>
                    <div>
                        <select id="kaiki_kbn" name="kaiki_kbn" class="w-64">
                            @foreach($kaiki_kbns as $kaiki_kbn)
                                <option value="{{ $kaiki_kbn['key3'] }}" {{ old('kaiki_kbn') == $kaiki_kbn['key3'] ? 'selected' : '' }}>
                                    {{ $kaiki_kbn['value1'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="kaiki_su" class="my-3">
                    <label for="kaiki" class="">回忌 <span class="required-mark">必須</span></label>
                    <div>
                        <input type="number" id="kaiki" name="kaiki" class="w-32" placeholder="" value="{{ old('kaiki') }}" />
                    </div>
                </div>
                <div class="my-3">
                    <label for="kaiki_name" class="">回忌名 <span class="required-mark">必須</span></label>
                    <div>
                        <input type="text" id="kaiki_name" name="kaiki_name" class="w-96" placeholder="１０文字以内で入力してください" value="{{ old('kaiki_name') }}" />
                    </div>
                </div> 
                <div class="my-3">
                    <label for="target_flg" class="">表示・非表示 <span class="required-mark">必須</span></label>
                    <div class="flex mt-3">
                        <div class="flex">
                            <input type="radio" id="target_flg_1" name="target_flg" value="1" class="radio" {{ old('target_flg') !== 0 ? 'checked' : '' }} />
                            <label for="target_flg_1" class="text-gray-500 ml-2 dark:text-gray-400">表示する</label>
                        </div>
                        <div class="flex ml-12">
                            <input type="radio" id="target_flg_2" name="target_flg" value="0" class="radio" {{ old('target_flg') === 0 ? 'checked' : '' }} />
                            <label for="target_flg_2" class="text-gray-500 ml-2 dark:text-gray-400">表示しない</label>
                        </div>
                    </div>
                </div>
                <div id="kaiki_kikan" class="my-3">
                    <label for="death_era" class="">対象期間<span class="mx-2 required-mark">必須</span></label>
                    <div class="w-8/9 max-w-3xl block">
                        <div class="inline-block w-4/5">
                            <select id="from_year_kbn" name="from_year_kbn" class="w-1/9">
                                <option value=""></option>
                                @foreach($from_kbns as $from_kbn)
                                    <option value="{{ $from_kbn['key3'] }}" {{ old('from_year_kbn') == $from_kbn['key3'] ? 'selected' : '' }}>
                                        {{ $from_kbn['value1'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="from_month" class="w-1/9" placeholder="月" min="1" max="12" value="{{ old('from_month') }}" />
                            <label class="pt-2">月</label>
                            <input type="number" name="from_day" class="w-1/9" placeholder="日" min="1" max="31" value="{{ old('from_day') }}" />
                            <label class="pt-2">日</label>
                            <label class="pt-2">～</label>
                            <select id="to_year_kbn" name="to_year_kbn" class="w-1/9">
                                <option value=""></option>
                                @foreach($from_kbns as $from_kbn)
                                    <option value="{{ $from_kbn['key3'] }}" {{ old('to_year_kbn') == $from_kbn['key3'] ? 'selected' : '' }}>
                                        {{ $from_kbn['value1'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="to_month" class="w-1/9" placeholder="月" min="1" max="12" value="{{ old('to_month') }}" />
                            <label class="pt-2">月</label>
                            <input type="number" name="to_day" class="w-1/9" placeholder="日" min="1" max="31" value="{{ old('to_day') }}" />
                            <label class="pt-2">日</label>
                        </div>
                    </div>
                </div>
                <div id="houyou_date" class="my-3">
                    <label for="death_era" class="">法要日<span class="mx-2 required-mark">必須</span></label>
                    <div class="w-5/6 max-w-2xl block">
                        <div class="inline-block">
                            <input type="number" name="houyou_month" class="w-1/3" placeholder="月" min="1" max="12" value="" />
                            <label class="pt-2">月</label>
                            <input type="number" name="houyou_day" class="w-1/3" placeholder="日" min="1" max="31" value="" />
                            <label class="pt-2">日</label>
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn-success proc-btn">
                    <i class="fa-solid fa-floppy-disk"></i><span class="mx-2">保存</span>
                </button>
                <a class="btn-default proc-btn" href="{{ route('kaiki.index') }}"><span class="mr-2">戻　る</span><i class="fa-solid fa-backward"></i></a>
            </form>
        </div>
    </div>
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
                kaikiKikan.style.display = 'block';
                houyouDate.style.display = 'block';
            } else if (kaikiKbn === '1') {
                // 百箇日の場合
                kaikiSu.style.display = 'none';
                kaikiKikan.style.display = 'none';
                houyouDate.style.display = 'none';
            } else {
                // 年忌の場合
                kaikiSu.style.display = 'block';
                kaikiKikan.style.display = 'none';
                houyouDate.style.display = 'none';
            }
        }
    </script>
</x-app-layout>