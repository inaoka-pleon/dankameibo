<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">

    @include('dialog')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">逮夜表</div>
                <div class="header-buttons">
                    <button id="printButton" class="header-btn">印刷</button>
                    <a class="header-btn" onclick="history.back()">終了</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
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

                    <!-- 逮夜表表示 -->
                     @foreach ($taiyalists as $taiyalist)
                        <table class="tableSample">
                            <tr>
                                <th>戒名</th>
                                <td>{{ $taiyalist->kaimyou }}</td>
                            </tr>
                            <tr>
                                <th>俗名</th>
                                <td>{{ $taiyalist->zokumyou }}</td>
                            </tr>
                            <tr>
                                <th>命日</th>
                                <td>{{ AD_to_JA_conv_calender($taiyalist->deathanniversary) }}</td>
                            </tr>
                        </table>
                    @endforeach
                    <br>
                    <table class="table-danka radius-table">
                        <thead>
                            <tr>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taiyaTables as $taiyaTable)
                                @foreach ($taiyaTable as $taiya)
                                    <tr>
                                        <td>{{ $taiya['title'] }}</td>
                                        <td>{{ AD_to_JA_conv_calender($taiya['date']).$taiya['dayOfWeek'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="/js/dialog.js"></script>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                showListDialog("{{ route('taiyalist.print', ['id' => $kakocho->id, 'action' => 'download']) }}", "{{ route('taiyalist.print', ['id' => $kakocho->id, 'action' => 'display']) }}", "印刷");
            });
        });
    </script>
</x-app-layout>