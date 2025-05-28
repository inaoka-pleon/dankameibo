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
                <div class="header-title">檀信徒検索</div>
                <div class="header-buttons">
                    <a href="{{ route('akihiganheader.edit', $akihigan_header_id) }}">
                        <ibutton class="header-btn">終了</ibutton>
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
                    <form action="{{ route('akihiganheader.danka_search', $akihigan_header_id) }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_namekana" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">氏名かな</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_akihigan[namekana]" value="{{ $cond_akihigan['namekana'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_namekana">
                            </div>
                            <label for="k_name" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">氏名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_akihigan[name]" value="{{ $cond_akihigan['name'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_name">
                            </div>
                            <label for="k_area" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">地区名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_akihigan[area]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->value1 }}" @if(!empty($cond_akihigan['area']) && $cond_akihigan['area'] === $area->value1) selected @endif>{{ $area->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="akihigan_search">
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
                    <form action="{{ route('akihiganheader.add_danka_data', $akihigan_header_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="flex flex-row-reverse">
                                <button type="submit" class="header-btn"
                                onclick="return confirm('代入します。よろしいですか？')">代入</button>
                            </div>
                            <p class="ml-2">該当：{{ $dankaSearchCount }} 件</p>
                        </div>
                        @if ($danka_searchs->isNotEmpty())
                            @csrf
                            <div class="overflow-auto shadow" style="max-height: 65vh;">
                                <table class="table-danka radius-table shadow">
                                    <thead>
                                        <tr>
                                            <th class="search_checkbox sticky-head"></th>
                                            <th class="search_namekana sticky-head">かな</th>
                                            <th class="search_name sticky-head">氏名</th>
                                            <th class="search_kubun sticky-head">区分</th>
                                            <th class="search_gender sticky-head">性別</th>
                                            <th class="search_area sticky-head">地区名</th>
                                            <th class="search_tel sticky-head">電話番号</th>
                                            <th class="search_address sticky-head">住所</th>
                                            <th class="search_postcode sticky-head">郵便番号</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($danka_searchs as $danka_search)
                                            <tr>
                                                <td class="table-checkbox"><input type="checkbox" name="selected_dankas[]" value="{{ $danka_search->id }}"></td>
                                                <td data-th="かな">{{ $danka_search->namekana }}</td>
                                                <td data-th="氏名">{{ $danka_search->name }}</td>
                                                <td data-th="区分">{{ $danka_search->chiefmourner_flg == 1? '施主' : '家族' }}</td>
                                                <td data-th="性別">{{ $danka_search->gender }}</td>
                                                <td data-th="地区名">{{ $danka_search->area }}</td>
                                                <td data-th="電話番号">{{ $danka_search->tel }}</td>
                                                <td data-th="住所">{{ $danka_search->address1 . $danka_search->address2 }}</td>
                                                <td data-th="郵便番号">{{ $danka_search->postcode }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                        @else
                            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                                <p class="font-bold">檀信徒情報がありません</p>
                            </div>
                        @endif
                    </form>
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

        function deleteDanka(){
            return confirm('削除します。よろしいですか？');
        }
    </script>
</x-app-layout>
        