<x-app-layout>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">

    @include('dialog')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">初盆忌一覧表</div>
                <div class="header-buttons">
                    <button id="yomikomichoButton" class="header-btn">読込帳印刷</button>
                    <a href="{{ route('hatsubonlistdocument.createOrEdit') }}">
                        <button class="header-btn">はがき文書</button>
                    </a>
                    <button id="postcardPrintButton" class="header-btn">はがき印刷</button>
                    <button id="printButton" class="header-btn">一覧表印刷</button>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="">
                    <div class="mb-4">
                        <p class="ml-2">該当：{{ $hatsubonCount }} 件</p>
                    </div>
                    <!-- 初盆忌一覧表示 -->
                    @if ($hatsubonlists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="hatsubon_name">氏名</th>
                                    <th class="hatsubon_address">住所</th>
                                    <th class="hatsubon_kaimyou">戒名</th>
                                    <th class="hatsubon_zokumyou">俗名</th>
                                    <th class="hatsubon_deathdate">命日</th>
                                    <th class="hatsubon_postcard">はがき区分</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($hatsubonlists as $hatsubonlist)
                                    <tr>
                                        <td>{{ $hatsubonlist->chief_name }}</td>
                                        <td>{{ $hatsubonlist->address1. $hatsubonlist->address2 }}</td>
                                        <td>{{ $hatsubonlist->kaimyou }}</td>
                                        <td>{{ $hatsubonlist->zokumyou }}</td>
                                        <td>{{ $hatsubonlist->death_era_name . $hatsubonlist->death_year. '年'. $hatsubonlist->death_month. '月'. $hatsubonlist->death_day. '日' }}</td>
                                        <td>{{ $hatsubonlist->postcard }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">初盆忌情報がありません</p>
                        </div>
                    @endif
                    <br>
                    <div>
                        @foreach ($kaikis as $kaiki)
                            <label>期間：{{ $FromEraName. $FromEraYear. '年'. $FromMonth. '月'. $FromDay. '日'}}　～　{{ $ToEraName. $ToEraYear. '年'. $ToMonth. '月'. $ToDay. '日' }}</label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function() {
                showListDialog("{{ route('hatsubonlist.print', ['action' => 'download']) }}", "{{ route('hatsubonlist.print', ['action' => 'display']) }}", "一覧表印刷");
            });
            document.getElementById('yomikomichoButton').addEventListener('click', function() {
                showListDialog("{{ route('hatsubonlist.yomikomicho.print', ['action' => 'download']) }}", "{{ route('hatsubonlist.yomikomicho.print', ['action' => 'display']) }}", "読込帳印刷");
            });
            document.getElementById('postcardPrintButton').addEventListener('click', function() {
                showPostcardDialog("はがき印刷", {
                    postcard: {
                        download: "{{ route('hatsubonlist.postcard_print', ['action' => 'download']) }}",
                        display: "{{ route('hatsubonlist.postcard_print', ['action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('hatsubonlist.envelope4_print', ['action' => 'download']) }}",
                        display: "{{ route('hatsubonlist.envelope4_print', ['action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('hatsubonlist.envelope3_print', ['action' => 'download']) }}",
                        display: "{{ route('hatsubonlist.envelope3_print', ['action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('hatsubonlist.square3_print', ['action' => 'download']) }}",
                        display: "{{ route('hatsubonlist.square3_print', ['action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('hatsubonlist.square2_print', ['action' => 'download']) }}",
                        display: "{{ route('hatsubonlist.square2_print', ['action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('hatsubonlist.label_print', ['action' => 'download']) }}",
                        display: "{{ route('hatsubonlist.label_print', ['action' => 'display']) }}"
                    }
                });
                document.getElementById('postcardDisplayOption').addEventListener('click', function() {
                    showListDialog("{{ route('hatsubonlist.back_print', ['action' => 'download']) }}", "{{ route('hatsubonlist.back_print', ['action' => 'display']) }}", "裏面印刷");
                });
            });
        });
    </script>
</x-app-layout>