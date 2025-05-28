<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">本堂掲示用一覧表</div>
                <div class="header-buttons">
                    <button id="amuletButton" class="header-btn">お札印刷</button>
                    <button id="printButton" class="header-btn">一覧表印刷</button>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-3">
            <hr class="w-full">
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="mb-3 w-full">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg font-normal bg-custom-5 border border-b-2 bg-gray-100 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form action="{{ route('hondoulist.index') }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="eraSelect" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">年度</label>
                            <div class="col-span-12 sm:col-span-10 py-1 sm:py-2 sm:pl-2 flex items-center">
                                <select type="text" name="hondou_target_year[era]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-64 sm:w-auto mr-2">
                                    <option value=""></option>
                                    @foreach($eras as $era)
                                        <option value="{{ $era->id }}" @if((old('hondou_target_year.era', $hondou_target_year['era'] ?? '') == $era->id) || (empty($hondou_target_year['era']) && $currentEraId == $era->name)) selected @endif>{{ $era->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" id="yearInput" name="hondou_target_year[year]" min="1" max="100" value="{{ old('hondou_target_year.year', $hondou_target_year['year'] ?? $currentEraYear + 1) }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 sm:w-auto">年度
                            </div>
                            <label for="kaikiSelect" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">回忌</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" id="kaikiSelect" name="cond_hondoulist[kaiki]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-64">
                                    <option value=""></option>
                                    @foreach($kaikis as $kaiki)
                                        <option value="{{ $kaiki->kaiki }}" @if(old('cond_hondoulist.kaiki', $cond_hondoulist['kaiki'] ?? '') == $kaiki->kaiki) selected @endif>{{ $kaiki->kaiki }}</option>
                                    @endforeach
                                </select>
                                <span>回忌</span>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="hondoulist_search">
                                <i class="fa-solid fa-magnifying-glass"></i><span class="mx-2">指定条件で検索</span>
                            </button>
                            <button type="button" class="btn-default text-xs reset shadow-sm py-3 px-4">
                                <i class="fa-regular fa-circle-xmark"></i><span class="mx-2">指定条件をリセット</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="w-full mb-3">
            <div class="w-full md:mb-4 text-sm sm:text-base">
                <div class="">
                    <div class="mb-3">
                        <p class="ml-2">該当：{{ $hondouCount }} 件</p>
                    </div>
                    @if ($hondoulists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="hondou_nendo">年度</th>
                                    <th class="hondou_kaiki">回忌</th>
                                    <th class="hondou_name">代表者</th>
                                    <th class="hondou_kaimyou">戒名</th>
                                    <th class="hondou_zokumyou">俗名</th>
                                    <th class="hondou_deathdate">命日</th>
                                    <th class="hondou_gyounen">行年</th>
                                    <th class="border text-xl px-4 py-2 visible md:hidden">
                                        年度<br>
                                        回忌<br>
                                        代表者<br>
                                        戒名<br>
                                        俗名<br>
                                        命日<br>
                                        行年
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hondoulists as $hondoulist)
                                    <tr>
                                        <td class="hondou_nendo" data-th="年度">{{ $hondoulist->death_era_name . $hondoulist->death_year . '年' }}</td>
                                        <td class="hondou_kaiki" data-th="回忌">{{ $hondoulist->kaiki }}</td>
                                        <td class="hondou_name" data-th="代表者">{{ $hondoulist->chief_name }}</td>
                                        <td class="hondou_kaimyou" data-th="戒名">{{ $hondoulist->kaimyou }}</td>
                                        <td class="hondou_zokumyou" data-th="俗名">{{ $hondoulist->zokumyou }}</td>
                                        <td class="hondou_deathdate" data-th="命日">{{ $hondoulist->death_era_name . $hondoulist->death_year. '年'. $hondoulist->death_month. '月'. $hondoulist->death_day. '日' }}</td>
                                        <td class="hondou_gyounen" data-th="行年">{{ $hondoulist->ageatdeath }}</td>
                                        <td class="border text-xl px-4 py-2 visible md:hidden">
                                            {{ $hondoulist->death_era_name . $hondoulist->death_year . '年' }}<br>
                                            {{ $hondoulist->kaiki }}<br>
                                            {{ $hondoulist->chief_name }}<br>
                                            {{ $hondoulist->kaimyou }}<br>
                                            {{ $hondoulist->zokumyou }}<br>
                                            {{ $hondoulist->death_era_name . $hondoulist->death_year. '年'. $hondoulist->death_month. '月'. $hondoulist->death_day. '日' }}<br>
                                            {{ $hondoulist->ageatdeath }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">本堂掲示用情報がありません</p>
                        </div>
                    @endif
                </div>
                <div class="pagination justify-content-center">
                    {{ $hondoulists->links() }}
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchHead = document.getElementById('search_head');
            const searchBody = document.getElementById('search_body');

            searchHead.addEventListener('click', function() {
                if (searchBody.style.display === 'none' || searchBody.style.display === '') {
                    searchBody.style.display = 'block';
                } else {
                    searchBody.style.display = 'none';
                }
            });

            document.querySelector('.reset').addEventListener('click', function() {
                document.querySelectorAll('#search_body input[type="text"]').forEach(input => input.value = '');
                document.querySelectorAll('#search_body select').forEach(select => select.selectedIndex = 0);
            });
        });
    </script>
    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                showListDialog("{{ route('hondoulist.print', ['action' => 'download']) }}", "{{ route('hondoulist.print', ['action' => 'display']) }}", "一覧表印刷");
            });
            document.getElementById('amuletButton').addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                showListDialog("{{ route('hondoulist.amulet.print', ['action' => 'download']) }}", "{{ route('hondoulist.amulet.print', ['action' => 'display']) }}", "お札印刷");
            });
        });
    </script>
</x-app-layout>