<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
    <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">汎用マスタ</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full max-w-7xl">
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="w-full max-w-7xl">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg font-normal bg-custom-5 border border-b-2 bg-gray-100 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form aciton="{{ route('generalmaster.index') }}" method="GET" >
                        @csrf
                        <div class="grid grid-cols-12">
                            <input type="hidden" id="k_sel_master" name="k_sel_master" value="{{ $k_sel_master }}" />
                            <label for="k_sel_master" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">マスタ名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="sel_master" name="sel_master" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full h-full p-2">
                                    <option value="">(未選択)</option>
                                    @foreach ($master_names as $master_name)
                                        <option value="{{ $master_name->key3 }}" @if($k_sel_master === $master_name->key3) selected @endif>{{ $master_name->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="generalmaster_search">
                                <i class="fa-solid fa-magnifying-glass"></i><span class="mx-2">指定条件で検索する</span>
                            </button>
                            <button type="button" class="btn-default text-xs reset shadow-sm py-3 px-4">
                                <i class="fa-regular fa-circle-xmark"></i><span class="mx-2">指定条件をリセットする</span>
                            </button>
                            @if(!empty($k_sel_master))
                                @php
                                    if ($k_sel_master === \Config::get('literal.GeneralMaster.Area')) {
                                        $title = "地区名";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.DankaDivision')) {
                                        $title = "檀家区分";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Position')) {
                                        $title = "寺役職";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Relationship')) {
                                        $title = "家族続柄";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Occupation')) {
                                        $title = "職業";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Mortuarytablet')) {
                                        $title = "位牌区分";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Manager')) {
                                        $title = "担当者";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.TempleOffice')) {
                                        $title = "宗務所";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Title')) {
                                        $title = "敬称";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.SubTitle')) {
                                        $title = "脇敬称";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Teacher')) {
                                        $title = "師";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Jikaku')) {
                                        $title = "寺格";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Qualification')) {
                                        $title = "資格";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.NyuukaiName')) {
                                        $title = "入会名";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.DankaKaihi')) {
                                        $title = "檀家会費";
                                    } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.GozikaiKaihi')) {
                                        $title = "護持会会費";
                                    }
                                @endphp
                                <a class="btn-success proc-btn text-xs shadow-sm py-3 px-4 ml-3" href="{{ route('generalmaster.create', ['no' => $k_sel_master]) }}">
                                    <i class="fa-regular fa-square-plus"></i><span class="px-2">{{ $title }}を追加する</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <hr class="w-full mb-4 max-w-7xl">
                <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                    <div class="">
                        @if(count($general_masters) > 0)
                            <!-- 寺院汎用マスタ一覧 -->
                            @include('generalmasters.list.general_master')
                        @else
                            @php
                                if ($k_sel_master === \Config::get('literal.GeneralMaster.Area')) {
                                    $msg = "地区名情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.DankaDivision')) {
                                    $msg = "檀家区分情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Position')) {
                                    $msg = "寺役職情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Relationship')) {
                                    $msg = "家族続柄情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Occupation')) {
                                    $msg = "職業情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Mortuarytablet')) {
                                    $msg = "位牌区分情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Manager')) {
                                    $msg = "担当者情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.TempleOffice')) {
                                    $msg = "宗務所情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Title')) {
                                    $msg = "敬称情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.SubTitle')) {
                                    $msg = "脇敬称情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Teacher')) {
                                    $msg = "師情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Jikaku')) {
                                    $msg = "寺格情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.Qualification')) {
                                    $msg = "資格情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.NyuukaiName')) {
                                    $msg = "入会名情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.DankaKaihi')) {
                                    $msg = "檀家会費情報がありません";
                                } elseif ($k_sel_master === \Config::get('literal.GeneralMaster.GozikaiKaihi')) {
                                    $msg = "護持会会費情報がありません";
                                } else {
                                    $msg = "マスタを選択してください";
                                }
                            @endphp
                            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                                <p class="font-bold">{{ $msg }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    <br>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleColumns(); // ページロード時に金額列を適切に表示または非表示にする
        });

        function toggleColumns() {
            const kSelMaster = document.getElementById('k_sel_master'); // k_sel_masterの値
            const generalAmountHeaders = document.querySelectorAll('.general_amount'); // 金額列ヘッダー (class="general_amount")
            const column2Data = document.querySelectorAll('.column2'); // 金額列のデータ (class="column2")

            // 初期表示状態を設定
            if (kSelMaster.value === '15' || kSelMaster.value === '16') {
                // k_sel_masterが15または16の場合は金額列を表示
                generalAmountHeaders.forEach(header => {
                    header.style.display = ''; // 金額列ヘッダーを表示
                });
                column2Data.forEach(data => {
                    data.style.display = ''; // 金額列データを表示
                });
            } else {
                // それ以外の場合は金額列を非表示
                generalAmountHeaders.forEach(header => {
                    header.style.display = 'none'; // 金額列ヘッダーを非表示
                });
                column2Data.forEach(data => {
                    data.style.display = 'none'; // 金額列データを非表示
                });
            }

            // k_sel_masterの値が変更された時に再度確認
            kSelMaster.addEventListener('change', function() {
                if (kSelMaster.value === '15' || kSelMaster.value === '16') {
                    // k_sel_masterが15または16の場合は金額列を表示
                    generalAmountHeaders.forEach(header => {
                        header.style.display = ''; // 金額列ヘッダーを表示
                    });
                    column2Data.forEach(data => {
                        data.style.display = ''; // 金額列データを表示
                    });
                } else {
                    // それ以外の場合は金額列を非表示
                    generalAmountHeaders.forEach(header => {
                        header.style.display = 'none'; // 金額列ヘッダーを非表示
                    });
                    column2Data.forEach(data => {
                        data.style.display = 'none'; // 金額列データを非表示
                    });
                }
            });
        }

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
</x-app-layout>