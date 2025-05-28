<x-app-layout>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">

    @include('dialog')
    @include('errors.form_errors')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">花まつり一覧表</div>
                <div class="header-buttons">
                    <button id="postcardPrintButtonNoBackPrint" class="header-btn">はがき印刷</button>
                    <button id="printButton" class="header-btn">一覧表印刷</button>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-3">
            <div class="w-full md:mb-4 text-sm sm:text-base">
                <div class="">
                    <div class="mb-3">
                        <p class="ml-2">該当：{{ $hanamatsuriCount }} 件</p>
                    </div>
                    @if ($hanamatsurilists->isNotEmpty())
                        <table class="table-danka radius-table shadow">
                            <thead>
                                <tr class>
                                    <th class="hanamatsuri_name">氏名</th>
                                    <th class="hanamatsuri_area">地区名</th>
                                    <th class="hanamatsuri_postcode">郵便番号</th>
                                    <th class="hanamatsuri_address">住所</th>
                                    <th class="hanamatsuri_tel">電話番号</th>
                                    <th class="hanamatsuri_gozikai">護持会</th>
                                    <th class="hanamatsuri_postcard">はがき区分</th>
                                    <th class="border text-xl px-4 py-2 visible md:hidden">
                                        氏名<br>
                                        地区名<br>
                                        郵便番号<br>
                                        住所<br>
                                        電話番号<br>
                                        護持会<br>
                                        はがき区分
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hanamatsurilists as $hanamatsurilist)
                                    <tr>
                                        <td class="hanamatsuri_name" data-th="氏名">{{ $hanamatsurilist->name }}</td>
                                        <td class="hanamatsuri_area" data-th="地区名">{{ $hanamatsurilist->area }}</td>
                                        <td class="hanamatsuri_postcode" data-th="郵便番号">{{ $hanamatsurilist->postcode }}</td>
                                        <td class="hanamatsuri_address" data-th="住所">{{ $hanamatsurilist->address1 . $hanamatsurilist->address2 }}</td>
                                        <td class="hanamatsuri_tel" data-th="電話番号">{{ $hanamatsurilist->tel }}</td>
                                        <td class="hanamatsuri_gozikai" data-th="護持会">
                                            <input type="hidden" name="gozikai" value="0">
                                            <input type="checkbox" name="gozikai" class="registration-input" value="1" @if(old('gozikai', $hanamatsurilist->gozikai)) checked @endif disabled>
                                        </td>
                                        <td class="hanamatsuri_postcard" data-th="はがき区分">{{ $hanamatsurilist->postcard }}</td>
                                        <td class="border text-xl px-4 py-2 visible md:hidden">
                                            {{ $hanamatsurilist->name }}<br>
                                            {{ $hanamatsurilist->area }}<br>
                                            {{ $hanamatsurilist->postcode }}<br>
                                            {{ $hanamatsurilist->address1 . $hanamatsurilist->address2 }}<br>
                                            {{ $hanamatsurilist->tel }}<br>
                                            @if($hanamatsurilist->gozikai) 〇 @else - @endif<br>
                                            {{ $hanamatsurilist->postcard }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-cntent-center">
                            {{ $hanamatsurilists->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">花まつり一覧情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function() {
                showListDialog("{{ route('hanamatsurilist.print', ['action' => 'download']) }}", "{{ route('hanamatsurilist.print', ['action' => 'display']) }}", "一覧表印刷");
            });
            document.getElementById('postcardPrintButtonNoBackPrint').addEventListener('click', function() {
                showPostcardDialogNoBackPrint("はがき印刷", {
                    postcard: {
                        download: "{{ route('hanamatsurilist.postcard_print', ['action' => 'download']) }}",
                        display: "{{ route('hanamatsurilist.postcard_print', ['action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('hanamatsurilist.envelope4_print', ['action' => 'download']) }}",
                        display: "{{ route('hanamatsurilist.envelope4_print', ['action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('hanamatsurilist.envelope3_print', ['action' => 'download']) }}",
                        display: "{{ route('hanamatsurilist.envelope3_print', ['action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('hanamatsurilist.square3_print', ['action' => 'download']) }}",
                        display: "{{ route('hanamatsurilist.square3_print', ['action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('hanamatsurilist.square2_print', ['action' => 'download']) }}",
                        display: "{{ route('hanamatsurilist.square2_print', ['action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('hanamatsurilist.label_print', ['action' => 'download']) }}",
                        display: "{{ route('hanamatsurilist.label_print', ['action' => 'display']) }}"
                    }
                });
            });
        });
    </script>
</x-app-layout>