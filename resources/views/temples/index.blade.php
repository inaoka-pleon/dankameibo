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
                <div class="text-gray-800 text-xl font-semibold">寺院一覧</div>
                <div class="inline-flex -mt-1">
                    <a class="header-btn" href="{{ route('temple.create') }}">新規登録</a>
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
                    <form aciton="{{ route('temple.index') }}" method="GET" >
                        <div class="grid grid-cols-12">
                            <label for="k_tmepleoffice" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">宗務所</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_temple[templeoffice]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach($templeoffices as $templeoffice)
                                        <option value="{{ $templeoffice->value1 }}" @if(!empty($cond_temple['templeoffice']) && $cond_temple['templeoffice'] === $templeoffice->value1) selected @endif>{{ $templeoffice->value1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="k_parish" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">教区</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <select type="text" name="cond_temple[parish]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full">
                                    <option value=""></option>
                                    @foreach(App\Consts\ParishConsts::PARISH_LIST as $name => $number)
                                        <option value="{{ $name }}" @if(!empty($cond_temple['parish']) && $cond_temple['parish'] === $name) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="k_templename" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">寺院名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_temple[templename]" value="{{ $cond_temple['templename'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_templename">
                            </div>
                            <label for="k_templenamekana" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">寺院名かな</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_temple[templenamekana]" value="{{ $cond_temple['templenamekana'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_templenamekana">
                            </div>
                            <label for="k_name" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">氏名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_temple[name]" value="{{ $cond_temple['name'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_name">
                            </div>
                            <label for="k_tel" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">電話番号</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_temple[tel]" value="{{ $cond_temple['tel'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_tel">
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="temple_search">
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
                    <!-- 寺院一覧表示 -->
                    @if ($temples->isNotEmpty()) 
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="templeoffice">宗務所</th>
                                    <th class="parish">教区</th>
                                    <th class="templenamekana">寺院名</th>
                                    <th class="templename">寺院名かな</th>
                                    <th class="name1">氏名</th>
                                    <th class="tel1">電話番号</th>
                                    <th scope="col">
                                        <span></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($temples as $temple)
                                    <tr>
                                        <td>{{ $temple->templeoffice }}</td>
                                        <td>{{ $temple->parish }}</td>
                                        <td>
                                            <a href="{{ route('temple.show', $temple->id) }}">{{ $temple->templename }}</a>
                                        </td>
                                        <td>{{ $temple->templenamekana }}</td>
                                        <td>{{ $temple->name }}</td>
                                        <td>{{ $temple->tel }}</td>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('temple.edit', $temple->id )}}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <form onsubmit="return deleteTemple();"
                                                    class="btn-other"
                                                    action="{{ route('temple.destroy', $temple->id) }}" method="post"
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
                            {{ $temples->appends([
                                'cond_temple' => @(Request::get('cond_temple')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">寺院情報がありません</p>
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
    function deleteTemple(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>
</x-app-layout>