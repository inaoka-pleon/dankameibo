<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">棚経参一覧 / {{ $tanagyou_headers->era . ' '. $tanagyou_headers->year. '年' }}</div>
                <div class="header-buttons">
                    <a href="{{ route('tanagyouheader.danka_search', $tanagyou_header_id) }}">
                        <button id="" class="header-btn">檀信徒検索</button>
                    </a>
                    <a href="{{ route('hatsubondocument.createOrEdit') }}">
                        <button class="header-btn">初盆文書</button>
                    </a>
                    <a href="{{ route('tanagyoudocument.createOrEdit') }}">
                        <button class="header-btn">棚経文書</button>
                    </a>
                    <button id="postcardPrintButton" class="header-btn">はがき印刷</button>
                    <button id="printButton" class="header-btn">一覧表印刷</button>
                    <a href="{{ route('tanagyouheader.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="mb-4 w-full max-w-7xl">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg font-normal bg-custom-5 border border-b-2 bg-gray-100 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form action="{{ route('tanagyouheader.edit', ['id' => $tanagyou_header_id]) }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_manager" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">担当者</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_tanagyou_header[manager]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->value1 }}" @if(!empty($cond_tanagyou_header['manager']) && $cond_tanagyou_header['manager'] === $manager->value1) selected @endif>{{ $manager->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="tanagyou_header_search">
                                <i class="fa-solid fa-magnifying-glass"></i><span class="mx-2">指定条件で検索する</span>
                            </button>
                            <button type="button" class="btn-default text-xs shadow-sm py-3 px-4" id="resetButton">
                                <i class="fa-regular fa-circle-xmark"></i><span class="mx-2">指定条件をリセットする</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="w-full mb-4 max-w-7xl">
            <form action="{{ route('tanagyouheader.update', $tanagyou_header_id) }}" class="mb-4 w-full max-w-7xl" method="POST">
                @csrf
                @method('PATCH')

                <div class="flex flex-row-reverse">
                    <button type="submit" class="header-btn"
                    onclick="return confirm('更新します。よろしいですか？')">更新</button>
                </div>
                <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                    <div class="">
                        <div class="mb-4">
                            <p class="ml-2">該当：{{ $tanagyouCount }} 件</p>
                        </div>
                        @if ($tanagyou_details->isNotEmpty())
                            <table class="table-tanagyou radius-table shadow">
                                <thead>
                                    <tr>
                                        <th class="tanagyou_name">氏名</th>
                                        <th class="tanagyou_address">住所</th>
                                        <th class="tanagyou_tel">電話番号</th>
                                        <th class="tanagyou_month">月</th>
                                        <th class="tanagyou_day">日</th>
                                        <th class="tanagyou_ampm">午前午後</th>
                                        <th class="tanagyou_hour">時</th>
                                        <th class="tanagyou_minute">分</th>
                                        <th class="tanagyou_hatsubon">初盆</th>
                                        <th class="tanagyou_time">時間</th>
                                        <th class="tanagyou_manager">担当者</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($tanagyou_details as $tanagyou_detail)
                                        <tr>
                                            <input type="hidden" name="tanagyou_detail_id_{{$i}}" value="{{ $tanagyou_detail->tanagyou_detail_id }}" />
                                            <td>{{ $tanagyou_detail->name }}</td>
                                            <td>{{ $tanagyou_detail->address1 . $tanagyou_detail->address2 }}</td>
                                            <td>{{ $tanagyou_detail->tel }}</td>
                                            <td><input type="text" name="month_{{$i}}" id="month_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ old('month_' . $i, $tanagyou_detail->month ?? 8) }}"></td>
                                            <td><input type="text" name="day_{{$i}}" id="day_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $tanagyou_detail->day }}"></td>
                                            <td>
                                                <select name="ampm_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                                    <option selected></option>
                                                    @foreach (App\Consts\AmpmConsts::AMPM_LIST as $name => $number)
                                                        @if($name === $tanagyou_detail->ampm)
                                                            <option value="{{ $name }}" selected>{{ $name }}</option>
                                                        @else
                                                            <option value="{{ $name }}">{{ $name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="hour_{{$i}}" id="hour_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $tanagyou_detail->hour }}"></td>
                                            <td><input type="text" name="minute_{{$i}}" id="minute_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $tanagyou_detail->minute }}"></td>
                                            <!-- チェックボックス入力不可 -->
                                            <td class="table-checkbox">
                                                <input type="hidden" name="hatsubon_{{$i}}" value="0"> 
                                                <input type="checkbox" name="hatsubon_{{$i}}" id="hatsubon_{{$i}}" value="1" @if(old('hatsubon', $tanagyou_detail->hatsubon)) checked @endif disabled>
                                            </td>
                                            <!-- チェックボックス入力可 -->
                                            <!-- <td class="table-checkbox">
                                                <input type="hidden" name="hatsubon_{{$i}}" value="0"> 
                                                <input type="checkbox" name="hatsubon_{{$i}}" id="hatsubon_{{$i}}" value="1" @if(old('hatsubon', $tanagyou_detail->hatsubon)) checked @endif>
                                            </td> -->
                                            <td><input type="text" name="time_{{$i}}" id="time_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $tanagyou_detail->time }}"></td>
                                            <td>
                                                <select name="manager_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                                    <option selected></option>
                                                    @foreach($managers as $manager)
                                                        @if($manager->value1 === $tanagyou_detail->manager)
                                                            <option value="{{ $manager->value1 }}" selected>{{ $manager->value1 }}</option>
                                                        @else
                                                            <option value="{{ $manager->value1 }}">{{ $manager->value1 }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @php($i += 1)
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <div class="pagination justify-content-center">
                                    {{ $tanagyou_details->appends([
                                        'cond_tanagyou_header' => @(Request::get('cond_tanagyou_header')),
                                        ])->links() }}
                            </div>
                        @else
                            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                                <p class="font-bold">棚経参情報がありません</p>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchHead = document.getElementById('search_head');
            const searchBody = document.getElementById('search_body');
            const resetButton = document.getElementById('resetButton');

            searchHead.addEventListener('click', function() {
                if (searchBody.style.display === 'none' || searchBody.style.display === '') {
                    searchBody.style.display = 'block';
                } else {
                    searchBody.style.display = 'none';
                }
            });

            resetButton.addEventListener('click', function() {
                searchBody.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                searchBody.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            });
        });
    </script>
    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function() {
                showListDialog("{{ route('tanagyouheader.print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}", "{{ route('tanagyouheader.print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}", "一覧表印刷");
            });
            document.getElementById('postcardPrintButton').addEventListener('click', function() {
                showPostcardDialog("はがき印刷", {
                    postcard: {
                        download: "{{ route('tanagyouheader.postcard_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}",
                        display: "{{ route('tanagyouheader.postcard_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('tanagyouheader.envelope4_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}",
                        display: "{{ route('tanagyouheader.envelope4_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('tanagyouheader.envelope3_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}",
                        display: "{{ route('tanagyouheader.envelope3_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('tanagyouheader.square3_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}",
                        display: "{{ route('tanagyouheader.square3_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('tanagyouheader.square2_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}",
                        display: "{{ route('tanagyouheader.square2_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('tanagyouheader.label_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}",
                        display: "{{ route('tanagyouheader.label_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}"
                    },
                });
                document.getElementById('postcardDisplayOption').addEventListener('click', function() {
                    showListDialog("{{ route('tanagyouheader.back_print', ['id' => $tanagyou_header_id, 'action' => 'download']) }}", "{{ route('tanagyouheader.back_print', ['id' => $tanagyou_header_id, 'action' => 'display']) }}", "裏面印刷");
                });
            });
        });
    </script>
</x-app-layout>