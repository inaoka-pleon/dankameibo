<x-app-layout>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/dialog.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">花まつり一覧表</div>
                <div class="inline-flex -mt-1">
                    <button id="postcardPrintButtonNoBackPrint" class="header-btn">はがき印刷</button>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hanamatsurilists as $hanamatsurilist)
                                    <tr>
                                        <td>{{ $hanamatsurilist->name }}</td>
                                        <td>{{ $hanamatsurilist->area }}</td>
                                        <td>{{ $hanamatsurilist->postcode }}</td>
                                        <td>{{ $hanamatsurilist->address1 . $hanamatsurilist->address2 }}</td>
                                        <td>{{ $hanamatsurilist->tel }}</td>
                                        <!-- <td>{{ $hanamatsurilist->gozikai }}</td> -->
                                        <td class="table-checkbox">
                                            <input type="hidden" name="gozikai" value="0"> 
                                            <input type="checkbox" name="gozikai" class="registration-input" value="1" @if(old('gozikai', $hanamatsurilist->gozikai)) checked @endif disabled>
                                        </td>
                                        <td>{{ $hanamatsurilist->postcard }}</td>
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