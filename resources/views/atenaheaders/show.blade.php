<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')
    @include('errors.form_errors')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">宛名印刷</div>
                <div class="header-buttons">
                    <a href="{{ route('atenadetail.danka_search', $atena_header_id) }}">
                        <button id="" class="header-btn">檀信徒検索</button>
                    </a>
                    <a href="{{ route('atenadetail.create', $atena_header_id) }}">
                        <button class="header-btn">新規登録</button>
                    </a>
                    <button id="postcardPrintButtonNoBackPrint" class="header-btn">印刷</button>
                    <a href="{{ route('atenaheader.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4">

            <div class="w-full md:mb-4 text-sm sm:text-base">
                <div class="">
                    <div class="mb-2">
                        <p class="ml-2">該当：{{ $atenaCount }} 件</p>
                    </div>
                    @if ($atena_details->isNotEmpty())
                        <table class="table-danka radius-table shadow">
                            <thead>
                                <tr>
                                    <th class="atena_name">氏名</th>
                                    <th class="atena_keishou">敬称</th>
                                    <th class="atena_postcode">郵便番号</th>
                                    <th class="atena_address1">住所１</th>
                                    <th class="atena_address2">住所２</th>
                                    <th class="atena_postcard">はがき</th>
                                    <th class="atena_btn"></th>
                                    <th class="border text-xl px-4 py-2 visible md:hidden">
                                        氏名<br>
                                        敬称<br>
                                        郵便番号<br>
                                        住所１<br>
                                        住所２<br>
                                        はがき
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($atena_details as $atena_detail)
                                    <tr>
                                        <input type="hidden" name="atena_detail_id" value="{{ $atena_detail->atena_detail_id }}" />
                                        <input type="hidden" name="atena_header_id" value="{{ $atena_detail->atena_header_id }}" />
                                        <td class="atena_name" data-th="氏名">{{ $atena_detail->name }}</td>
                                        <td class="atena_keishou" data-th="敬称">{{ $atena_detail->keishou }}</td>
                                        <td class="atena_postcode" data-th="郵便番号">{{ $atena_detail->postcode }}</td>
                                        <td class="atena_address1" data-th="住所１">{{ $atena_detail->address1 }}</td>
                                        <td class="atena_address2" data-th="住所２">{{ $atena_detail->address2 }}</td>
                                        <td class="atena_postcard" data-th="はがき">{{ $atena_detail->postcard }}</td>
                                        <td class="atena_btn">
                                            <div class="btn-center">
                                                <a href="{{ route('atenadetail.edit', ['id' => $atena_detail->atena_detail_id ] )}}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <form onsubmit="return deleteAtena();"
                                                    class="btn-delete"
                                                    action="{{ route('atenadetail.destroy', ['atena_header_id' => $atena_detail->atena_header_id, 'id' => $atena_detail->atena_detail_id]) }}" method="post"
                                                    role="menuitem" tabindex="-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-trash"></i><span class="mx-2">削除</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="border text-xl px-4 py-2 visible md:hidden">
                                            {{ $atena_detail->name }}<br>
                                            {{ $atena_detail->keishou }}<br>
                                            {{ $atena_detail->postcode }}<br>
                                            {{ $atena_detail->address1 }}<br>
                                            {{ $atena_detail->address2 }}<br>
                                            {{ $atena_detail->postcard }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-content-center">
                            {{ $atena_details->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">宛名印刷情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <script>
        function deleteAtena(){
            if(confirm('削除します。よろしいですか？')){
                return true;
            } else {
                return false;
            }
        }
    </script>

    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('postcardPrintButtonNoBackPrint').addEventListener('click', function() {
                showPostcardDialogNoBackPrint("はがき印刷", {
                    postcard: {
                        download: "{{ route('atenaheader.postcard_print', ['id' => $atena_header_id, 'action' => 'download']) }}",
                        display: "{{ route('atenaheader.postcard_print', ['id' => $atena_header_id, 'action' => 'display']) }}"
                    },
                    envelope4: {
                        download: "{{ route('atenaheader.envelope4_print', ['id' => $atena_header_id, 'action' => 'download']) }}",
                        display: "{{ route('atenaheader.envelope4_print', ['id' => $atena_header_id, 'action' => 'display']) }}"
                    },
                    envelope3: {
                        download: "{{ route('atenaheader.envelope3_print', ['id' => $atena_header_id, 'action' => 'download']) }}",
                        display: "{{ route('atenaheader.envelope3_print', ['id' => $atena_header_id, 'action' => 'display']) }}"
                    },
                    square3: {
                        download: "{{ route('atenaheader.square3_print', ['id' => $atena_header_id, 'action' => 'download']) }}",
                        display: "{{ route('atenaheader.square3_print', ['id' => $atena_header_id, 'action' => 'display']) }}"
                    },
                    square2: {
                        download: "{{ route('atenaheader.square2_print', ['id' => $atena_header_id, 'action' => 'download']) }}",
                        display: "{{ route('atenaheader.square2_print', ['id' => $atena_header_id, 'action' => 'display']) }}"
                    },
                    label: {
                        download: "{{ route('atenaheader.label_print', ['id' => $atena_header_id, 'action' => 'download']) }}",
                        display: "{{ route('atenaheader.label_print', ['id' => $atena_header_id, 'action' => 'display']) }}"
                    }
                });
            });
        });
    </script>
</x-app-layout>