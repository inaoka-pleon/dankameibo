<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">年忌表</div>
                <div class="inline-flex -mt-1">
                    <a class="header-btn" href="{{ route('nenkidocument.createOrEdit', ['id' => session('kakocho_id')]) }}">文書</a>
                    <a id="printButton" class="header-btn">印刷</a>
                    <a class="header-btn" href="{{ route('danka.show', ['id' => $kakocho->danka_id]) }}">終了</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <form action="{{ route('nenkilist.update', $kakocho->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex flex-col justify-center items-center mb-8">
                <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                    <div class="">
                        <div class="hs-accordion-group" data-hs-accordion-always-open="">
                            <div class="hs-accordion active" id="hs-basic-always-open-heading-one">
                                <div id="hs-basic-always-open-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-always-open-heading-one">
                                    <form action="{{ route('nenkilist.index', $kakocho->id) }}" method="GET">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- 年忌表表示 -->
                        <table class="tableSample">
                            <input type="hidden" id="id" name="id" value="{{ $kakocho->id }}">
                            <tr>
                                <th>戒名</th>
                                <td>{{ $kakocho->kaimyou }}</td>
                            </tr>
                            <tr>
                                <th>俗名</th>
                                <td>{{ $kakocho->zokumyou }}</td>
                            </tr>
                            <tr>
                                <th>行年</th>
                                <td>
                                    @if(!empty($kakocho->ageatdeath))
                                        {{ $kakocho->ageatdeath }} 才
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>命日</th>
                                <td>{{ AD_to_JA_calender_conv($kakocho->deathanniversary) }}</td>
                            </tr>
                        </table>
                        <br>
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="nenki_kaiki">回忌</th>
                                    <th class="nenki_houyou">法要日</th>
                                    <th class="nenki_kuyou">供養</th>
                                    <th class="nenki_memo">備考</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kaikis as $kaiki)
                                    @php
                                        $nenkilist = $nenkilists->firstWhere('kaiki_id', $kaiki->id);
                                    @endphp
                                    <tr>
                                        <input type="hidden" name="kaiki_id" value="{{ $kaiki->id }}" />
                                        <td>{{ $kaiki->kaiki_name }}</td>
                                        <td>
                                            @if (isset($houyouDates[$kaiki->id]))
                                                {{ $houyouDates[$kaiki->id]['era_name'] }}{{ $houyouDates[$kaiki->id]['era_year'] }}年{{ $houyouDates[$kaiki->id]['month'] }}月{{ $houyouDates[$kaiki->id]['day'] }}日
                                            @endif
                                        </td>
                                        <td class="table-checkbox">
                                            <input type="hidden" name="kuyou[{{ $kaiki->id }}]" value="0">
                                            <input type="checkbox" name="kuyou[{{ $kaiki->id }}]" id="kuyou[{{ $kaiki->id }}]" value="1" {{ $nenkilist && $nenkilist->kuyou ? 'checked' : '' }}>
                                        </td>
                                        <td><input type="text" name="memo[{{ $kaiki->id }}]" id="memo[{{ $kaiki->id }}]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $nenkilist ? $nenkilist->memo : '' }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="flex flex-row-reverse">
                            <button type="submit" class="header-btn" onclick="return confirm('更新します。よろしいですか？')">更新</button>
                        </div>  
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="/js/dialog.js"></script>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function() {
                showListDialog("{{ route('nenkilist.print', ['id' => $kakocho->id, 'action' => 'download']) }}", "{{ route('nenkilist.print', ['id' => $kakocho->id, 'action' => 'display']) }}", "一覧表印刷");
            });
        });
    </script>
</x-app-layout>