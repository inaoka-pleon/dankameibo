<x-app-layout>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">年回表</div>
                <div class="inline-flex -mt-1">
                    <a href="{{ route('nenkaidocument.createOrEdit') }}">
                        <button class="header-btn">文書</button>
                    </a>
                    <button id="printButton" class="header-btn">印刷</button>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="mb-4 w-full max-w-7xl">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4 my-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg font-normal bg-custom-5 border border-b-2 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form action="{{ route('nenkailist.index') }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_era" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">年度</label>
                            <div class="col-span-12 sm:col-span-10 py-1 sm:py-2 sm:pl-2 flex items-center">
                                <select type="text" name="nenkai_target_year[era]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-64 sm:w-auto mr-2">
                                    <option value=""></option>
                                    @foreach($eras as $era)
                                        <option value="{{ $era->id }}" @if((old('nenkai_target_year.era', $nenkai_target_year['era'] ?? '') == $era->id) || (empty($nenkai_target_year['era']) && $currentEraId == $era->name)) selected @endif>{{ $era->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" id="yearInput" name="nenkai_target_year[year]" min="1" max="100" value="{{ old('nenkai_target_year.year', $nenkai_target_year['year'] ?? $currentEraYear + 1) }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 sm:w-auto">年度
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="nenkailist_search">
                                <i class="fa-solid fa-magnifying-glass"></i><span class="mx-2">指定条件で検索する</span>
                            </button>
                            <button type="button" class="btn-default text-xs reset shadow-sm py-3 px-4">
                                <i class="fa-regular fa-circle-xmark"></i><span class="mx-2">指定条件をリセットする</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="">
                    <!-- 年回表表示 -->
                    @if ($nenkailists->isNotEmpty())
                        <table class="table-nenkai radius-table">
                            <thead>
                                <tr>
                                    <th class="nenkai_kaiki">回忌</th>
                                    <th class="nenkai_nendo">年度</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nenkailists as $nenkailist)
                                    <tr>
                                        <td>{{ $nenkailist->kaiki_name }}</td>
                                        <td>{{ $nenkailist->death_era_name . $nenkailist->death_year . '年' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">年回表情報がありません</p>
                        </div>
                    @endif
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
                showListDialog("{{ route('nenkailist.print', ['action' => 'download']) }}", "{{ route('nenkailist.print', ['action' => 'display']) }}", "一覧表印刷");
            });
        });
    </script>
</x-app-layout>