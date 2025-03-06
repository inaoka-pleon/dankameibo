<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">檀家一覧</div>
                <div class="inline-flex -mt-1">
                    <a class="btn-entry" href="{{ route('danka.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規登録</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="mb-4 w-full max-w-7xl">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg bg-gray-100 font-normal bg-custom-5 border border-b-2 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form aciton="{{ route('danka.index') }}" method="GET" >
                        <div class="grid grid-cols-12">
                            <label for="k_area" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">地区名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_danka[area]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->value1 }}" @if(!empty($cond_danka['area']) && $cond_danka['area'] === $area->value1) selected @endif>{{ $area->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="k_name" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">氏名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_danka[name]" value="{{ $cond_danka['name'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_name">
                            </div>
                            <label for="k_namekana" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">氏名かな</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_danka[namekana]" value="{{ $cond_danka['namekana'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_namekana">
                            </div>
                            <label for="k_tel" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">電話番号</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_danka[tel]" value="{{ $cond_danka['tel'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_tel">
                            </div>
                            <label for="k_area" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">寺役職</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_danka[position]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->value1 }}" @if(!empty($cond_danka['position']) && $cond_danka['position'] === $position->value1) selected @endif>{{ $position->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="danka_search">
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
                    @if ($dankas->isNotEmpty()) 
                        <div class="mb-4">
                            <p class="ml-2">該当：{{ $dankaCount }} 件</p>
                        </div>
                        <table class="table-danka radius-table shadow">
                            <thead>
                                <tr class>
                                    <th class="danka_area">地区名</th>
                                    <th class="danka_name">氏名</th>
                                    <th class="danka_namekana">氏名かな</th>
                                    <th class="danka_postcode">郵便番号</th>
                                    <th class="danka_address">住所</th>
                                    <th class="danka_tel">電話番号</th>
                                    <th class="danka_position">寺役職</th>
                                    <th scope="col" class="danka_col">
                                        <span></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dankas as $danka)
                                    <tr>
                                        <td>{{ $danka->area }}</td>
                                        <td>
                                            <a href="{{ route('danka.show', $danka->id )}}" class="blue_line">{{ $danka->name }}</a>
                                        </td>
                                        <td>{{ $danka->namekana }}</td>
                                        <td>{{ $danka->postcode }}</td>
                                        <td>{{ $danka->address1. $danka->address2 }}</td>
                                        <td>{{ $danka->tel }}</td>
                                        <td>{{ $danka->position }}</td>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('danka.edit', $danka->id )}}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <form onsubmit="return deleteDanka();"
                                                    class="btn-delete"
                                                    action="{{ route('danka.destroy', $danka->id) }}" method="post"
                                                    role="menuitem" tabindex="-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-trash"></i><span class="mx-2">削除</span>
                                                    </button> 
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-content-center">
                            {{ $dankas->appends([
                                'cond_danka' => @(Request::get('cond_danka')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">檀家情報がありません</p>
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
</x-app-layout>