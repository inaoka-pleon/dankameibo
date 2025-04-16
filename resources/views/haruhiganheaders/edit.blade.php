<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">春彼岸一覧 / {{ $haruhigan_headers->era . ' '. $haruhigan_headers->year. '年'}}</div>
                <div class="header-buttons">
                    <a href="{{ route('haruhiganheader.danka_search', $haruhigan_header_id) }}">
                        <button id="" class="header-btn">檀信徒検索</button>
                    </a>
                    <a href="{{ route('haruhigandocument.createOrEdit') }}">
                        <button class="header-btn">はがき文書</button>
                    </a>
                    <button id="postcardPrintButton" class="header-btn">はがき印刷</button>
                    <button id="printButton" class="header-btn">一覧表印刷</button>
                    <a href="{{ route('haruhiganheader.index') }}">
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
                    <form action="{{ route('haruhiganheader.edit', ['id' => $haruhigan_header_id]) }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_manager" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">担当者</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_haruhigan_header[manager]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->value1 }}" @if(!empty($cond_haruhigan_header['manager']) && $cond_haruhigan_header['manager'] === $manager->value1) selected @endif>{{ $manager->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="haruhigan_header_search">
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
            <form action="{{ route('haruhiganheader.update', $haruhigan_header_id) }}" class="mb-4 w-full max-w-7xl" method="POST">
                @csrf
                @method('PATCH')

                <div class="flex flex-row-reverse">
                    <button type="submit" class="header-btn"
                    onclick="return confirm('更新します。よろしいですか？')">更新</button>
                </div>
                <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                    <div class="">
                        <div class="mb-4">
                            <p class="ml-2">該当：{{ $haruhiganCount }} 件</p>
                        </div>
                        @if ($haruhigan_details->isNotEmpty())
                            <table class="table-tanagyou radius-table shadow">
                                <thead>
                                    <tr>
                                        <th class="haruhigan_name">氏名</th>
                                        <th class="haruhigan_address">住所</th>
                                        <th class="haruhigan_tel">電話番号</th>
                                        <th class="haruhigan_month">月</th>
                                        <th class="haruhigan_day">日</th>
                                        <th class="haruhigan_ampm">午前午後</th>
                                        <th class="haruhigan_hour">時</th>
                                        <th class="haruhigan_minute">分</th>
                                        <th class="haruhigan_manager">担当者</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($haruhigan_details as $haruhigan_detail)
                                        <tr>
                                            <input type="hidden" name="haruhigan_detail_id_{{$i}}" value="{{ $haruhigan_detail->haruhigan_detail_id }}" /></td>
                                            <td>{{ $haruhigan_detail->name }}</td>
                                            <td>{{ $haruhigan_detail->address1 . $haruhigan_detail->address2 }}</td>
                                            <td>{{ $haruhigan_detail->tel }}</td>
                                            <td><input type="text" name="month_{{$i}}" id="month_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ old('month_' . $i, $haruhigan_detail->month ?? 3) }}"></td>
                                            <td><input type="text" name="day_{{$i}}" id="day_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $haruhigan_detail->day }}"></td>
                                            <td>
                                                <select name="ampm_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                                    <option selected></option>
                                                    @foreach (App\Consts\AmpmConsts::AMPM_LIST as $name => $number)
                                                        @if($name === $haruhigan_detail->ampm)
                                                            <option value="{{ $name }}" selected>{{ $name }}</option>
                                                        @else
                                                            <option value="{{ $name }}">{{ $name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="hour_{{$i}}" id="hour_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $haruhigan_detail->hour }}"></td>
                                            <td><input type="text" name="minute_{{$i}}" id="minute_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" value="{{ $haruhigan_detail->minute }}"></td>
                                            <td>
                                                <select name="manager_{{$i}}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                                    <option selected></option>
                                                    @foreach($managers as $manager)
                                                        @if($manager->value1 === $haruhigan_detail->manager)
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
                                    {{ $haruhigan_details->appends([
                                        'cond_haruhigan_header' => @(Request::get('cond_haruhigan_header')),
                                        ])->links() }}
                            </div>
                        @else
                            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                                <p class="font-bold">春彼岸一覧情報がありません</p>
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
                showListDialog("{{ route('haruhiganheader.print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}", "{{ route('haruhiganheader.print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}", "一覧表印刷");
            });
            document.getElementById('postcardPrintButton').addEventListener('click', function() {
                showPostcardDialog("はがき印刷", {
                    postcard: {
                        download: "{{ route('haruhiganheader.postcard_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}",
                        display: "{{ route('haruhiganheader.postcard_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('haruhiganheader.envelope4_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}",
                        display: "{{ route('haruhiganheader.envelope4_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('haruhiganheader.envelope3_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}",
                        display: "{{ route('haruhiganheader.envelope3_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('haruhiganheader.square3_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}",
                        display: "{{ route('haruhiganheader.square3_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('haruhiganheader.square2_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}",
                        display: "{{ route('haruhiganheader.square2_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('haruhiganheader.label_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}",
                        display: "{{ route('haruhiganheader.label_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}"
                    }
                });
                document.getElementById('postcardDisplayOption').addEventListener('click', function() {
                    showListDialog("{{ route('haruhiganheader.back_print', ['id' => $haruhigan_header_id, 'action' => 'download']) }}", "{{ route('haruhiganheader.back_print', ['id' => $haruhigan_header_id, 'action' => 'display']) }}", "裏面印刷");
                });
            });
        });
    </script>
</x-app-layout>