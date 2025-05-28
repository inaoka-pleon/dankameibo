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
                <div class="header-title">地区別名簿一覧表</div>
                <div class="header-buttons">
                    <button id="postcardPrintButtonNoBackPrint" class="header-btn">はがき印刷</button>
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
        <div class="flex flex-col justify-center items-center mb-4">
            <div class="mb-3 w-full">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg font-normal bg-custom-5 border border-b-2 bg-gray-100 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form action="{{ route('arealist.index') }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_area" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">地区名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_arealist[area]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->value1 }}" @if(!empty($cond_arealist['area']) && $cond_arealist['area'] === $area->value1) selected @endif>{{ $area->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="arealist_search">
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
                    @if ($arealists->isNotEmpty())
                        <div class="mb-3">
                            <p class="ml-2">該当：{{ $arealistCount }} 件</p>
                        </div>
                        <table class="table-danka radius-table shadow">
                            <thead>
                                <tr>
                                    <th class="area_name">氏名</th>
                                    <th class="area_dankadivision">檀家区分</th>
                                    <th class="area_area">地区名</th>
                                    <th class="area_postcode">郵便番号</th>
                                    <th class="area_address">住所</th>
                                    <th class="area_tel">電話番号</th>
                                    <th class="area_postcard">はがき区分</th>
                                    <th class="border text-xl px-4 py-2 visible md:hidden">
                                        氏名<br>
                                        檀家区分<br>
                                        地区名<br>
                                        郵便番号<br>
                                        住所<br>
                                        電話番号<br>
                                        はがき区分
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($arealists as $arealist)
                                    <tr>
                                        <td class="area_name" data-th="氏名">{{ $arealist->name }}</td>
                                        <td class="area_dankadivision" data-th="檀家区分">{{ $arealist->dankadivision }}</td>
                                        <td class="area_area" data-th="地区名">{{ $arealist->area }}</td>
                                        <td class="area_postcode" data-th="郵便番号">{{ $arealist->postcode }}</td>
                                        <td class="area_address" data-th="住所">{{ $arealist->address1 . $arealist->address2 }}</td>
                                        <td class="area_tel" data-th="電話番号">{{ $arealist->tel }}</td>
                                        <td class="area_postcard" data-th="はがき区分">{{ $arealist->postcard }}</td>
                                        <td class="border text-xl px-4 py-2 visible md:hidden">
                                            {{ $arealist->name }}<br>
                                            {{ $arealist->dankadivision }}<br>
                                            {{ $arealist->area }}<br>
                                            {{ $arealist->postcode }}<br>
                                            {{ $arealist->address1 . $arealist->address2 }}<br>
                                            {{ $arealist->tel }}<br>
                                            {{ $arealist->postcard }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-cntent-center">
                            {{ $arealists->appends(['cond_arealist' => @(Request::get('cond_arealist'))])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">地区別名簿情報がありません</p>
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

        function deleteDanka(){
            return confirm('削除します。よろしいですか？');
        }
    </script>

    
    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function() {
                showListDialog("{{ route('arealist.print', ['action' => 'download']) }}", "{{ route('arealist.print', ['action' => 'display']) }}", "一覧表印刷");
            });

            document.getElementById('postcardPrintButtonNoBackPrint').addEventListener('click', function() {
                showPostcardDialogNoBackPrint("はがき印刷", {
                    postcard: {
                        download: "{{ route('arealist.postcard_print', ['action' => 'download']) }}",
                        display: "{{ route('arealist.postcard_print', ['action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('arealist.envelope4_print', ['action' => 'download']) }}",
                        display: "{{ route('arealist.envelope4_print', ['action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('arealist.envelope3_print', ['action' => 'download']) }}",
                        display: "{{ route('arealist.envelope3_print', ['action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('arealist.square3_print', ['action' => 'download']) }}",
                        display: "{{ route('arealist.square3_print', ['action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('arealist.square2_print', ['action' => 'download']) }}",
                        display: "{{ route('arealist.square2_print', ['action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('arealist.label_print', ['action' => 'download']) }}",
                        display: "{{ route('arealist.label_print', ['action' => 'display']) }}"
                    }
                });
            });
        });
    </script>
</x-app-layout>
        