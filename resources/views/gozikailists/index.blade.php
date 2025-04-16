<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">護持会名簿一覧表</div>
                <div class="header-buttons">
                    <button id="postcardPrintButtonNoBackPrint" class="header-btn">はがき印刷</button>
                    <button id="printButton" class="header-btn">一覧表印刷</button>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="mb-4 w-full max-w-7xl">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg font-normal bg-custom-5 border border-b-2 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form action="{{ route('gozikailist.index') }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_area" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">地区名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_gozikailist[area]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->value1 }}" @if(!empty($cond_gozikailist['area']) && $cond_gozikailist['area'] === $area->value1) selected @endif>{{ $area->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="gozikailist_search">
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
                    <div class="mb-4">
                        <p class="ml-2">該当：{{ $gozikaiCount }} 件</p>
                    </div>
                    @if ($gozikailists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="gozikai_name">氏名</th>
                                    <th class="gozikai_dankadivision">檀家区分</th>
                                    <th class="gozikai_area">地区名</th>
                                    <th class="gozikai_postcode">郵便番号</th>
                                    <th class="gozikai_address">住所</th>
                                    <th class="gozikai_tel">電話番号</th>
                                    <th class="gozikai_postcard">はがき区分</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gozikailists as $gozikailist)
                                    <tr>
                                        <td>{{ $gozikailist->name }}</td>
                                        <td>{{ $gozikailist->dankadivision }}</td>
                                        <td>{{ $gozikailist->area }}</td>
                                        <td>{{ $gozikailist->postcode }}</td>
                                        <td>{{ $gozikailist->address1 . $gozikailist->address2 }}</td>
                                        <td>{{ $gozikailist->tel }}</td>
                                        <td>{{ $gozikailist->postcard }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-cntent-center">
                            {{ $gozikailists->appends([
                                'cond_gozikailist' => @(Request::get('cond_gozikailist')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">護持会名簿情報がありません</p>
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
            document.getElementById('printButton').addEventListener('click', function() {
                showListDialog("{{ route('gozikailist.print', ['action' => 'download']) }}", "{{ route('gozikailist.print', ['action' => 'display']) }}", "一覧表印刷");
            });

            document.getElementById('postcardPrintButtonNoBackPrint').addEventListener('click', function() {
                showPostcardDialogNoBackPrint("はがき印刷", {
                    postcard: {
                        download: "{{ route('gozikailist.postcard_print', ['action' => 'download']) }}",
                        display: "{{ route('gozikailist.postcard_print', ['action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('gozikailist.envelope4_print', ['action' => 'download']) }}",
                        display: "{{ route('gozikailist.envelope4_print', ['action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('gozikailist.envelope3_print', ['action' => 'download']) }}",
                        display: "{{ route('gozikailist.envelope3_print', ['action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('gozikailist.square3_print', ['action' => 'download']) }}",
                        display: "{{ route('gozikailist.square3_print', ['action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('gozikailist.square2_print', ['action' => 'download']) }}",
                        display: "{{ route('gozikailist.square2_print', ['action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('gozikailist.label_print', ['action' => 'download']) }}",
                        display: "{{ route('gozikailist.label_print', ['action' => 'display']) }}"
                    }
                });
            });
        });
    </script>
</x-app-layout>