<x-app-layout>
    <x-slot name="header">
        <h2 class="header header-inner">寺役職マスタ</h2>

        <!-- 寺役職新規登録画面に遷移 -->
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('position.create') }}">
                新規登録
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <!-- <link rel="stylesheet" href="/css/bootstrap.css"> -->

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <!-- 検索条件 -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white orverflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="hs-accordion-group">
                        <div class="hs-accordion active" id="hs-basic-heading-one">
                            <button class="hs-accordion-toggle hs-accordion-active:text-blue-600 group py-3 inline-flex items-center gap-x-3 w-full font-semibold text-left text-gray-800 transition hover:text-gray-500 dark:hs-accordion-active:text-blue-500 dark:text-gray-200 dark:hover:text-gray-400" aria-controls="hs-basic-collapse-one">
                                <svg class="hs-accordion-active:hidden hs-accordion-active:text-blue-600 hs-accordion-active:group-hover:text-blue-600 block w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.62421 7.86L13.6242 7.85999" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M8.12421 13.36V2.35999" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <svg class="hs-accordion-active:block hs-accordion-active:text-blue-600 hs-accordion-active:group-hover:text-blue-600 hidden w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.62421 7.86L13.6242 7.85999" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                条件指定
                            </button>
                            <div id="hs-basic-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-heading-one">
                                <form aciton="{{ route('position.index') }}" method="GET" >

                                    <div class="my-1">
                                        <label for="k_position" class="text-xs">寺役職</label>
                                        <div>
                                            <input type="search" name="cond_position[position]" value="{{ $cond_position['position'] }}" class="text-xs w-full lg:w-1/2" id="k_position">
                                        </div>
                                    </div>
                                    <div class="my-4">
                                        <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="position_search">指定条件で検索する</button>
                                        <button type="button" class="btn-default text-xs reset">指定条件をリセットする</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <!-- 寺役職一覧表示 -->
                @if($positions->isNotEmpty())
                    <table class="table_design01 radius-table">
                        <thead>
                            <tr>
                                <th class="positioncode">コード</th>
                                <th class="position">寺役職</th>
                                <th>
                                    <span></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                                <tr>
                                    <td>
                                        <div>
                                            {{ $position->id }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $position->position }}
                                        </div>
                                    </td>
                                    <td class="btn-center">
                                        <a class="btn-edit" href="{{ route('position.edit', $position->id) }}">編集</a>
                                        <form onsubmit="return deletePosition();"
                                            class="btn-delete"
                                            action="{{ route('position.destroy', $position->id) }}" method="post"
                                            role="menuitem" tabindex="-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="pagination justify-content-center">
                        {{ $positions->appends ([
                            'cond_position' => @(Request::get('cond_position')),
                            ])->links() }}
                    </div>
                @else
                    <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                        <p class="font-bold">寺役職の登録がありません</p>
                    </div>
                @endif
            </div> 
        </div> 
    </div>



<script>
    function deletePosition(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>
</x-app-layout>