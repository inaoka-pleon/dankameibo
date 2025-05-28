<x-app-layout>
    <x-slot name="header">
        <h2 class="header header-inner">位牌区分マスタ</h2>

        <!-- 位牌区分新規登録画面に遷移 -->
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('mortuarytablet.create') }}">
                新規作成
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
                                <form aciton="{{ route('mortuarytablet.index') }}" method="GET" >

                                    <div class="my-1">
                                        <label for="k_mortuarytablet" class="text-xs">位牌区分</label>
                                        <div>
                                            <input type="search" name="cond_mortuarytablet[mortuarytablet]" value="{{ $cond_mortuarytablet['mortuarytablet'] }}" class="text-xs w-full lg:w-1/2" id="k_mortuarytablet">
                                        </div>
                                    </div>
                                    <div class="my-4">
                                        <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="mortuarytablet_search">指定条件で検索</button>
                                        <button type="button" class="btn-default text-xs reset">指定条件をリセット</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <!-- 位牌区分一覧表示 -->
                @if($mortuarytablets->isNotEmpty())
                    <table class="table_design01 radius-table">
                        <thead>
                            <tr>
                                <th class="areaid">地域コード</th>
                                <th class="mortuarytablet">位牌区分</th>
                                <th>
                                    <span></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mortuarytablets as $mortuarytablet)
                                <tr>
                                    <td>
                                        <div>
                                            {{ $mortuarytablet->id }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $mortuarytablet->mortuarytablet }}
                                        </div>
                                    </td>
                                    <td class="btn-center">
                                        <a class="btn-edit" href="{{ route('mortuarytablet.edit', $mortuarytablet->id) }}">編集</a>
                                        <form onsubmit="return deleteMortuarytablet();"
                                            class="btn-delete"
                                            action="{{ route('mortuarytablet.destroy', $mortuarytablet->id) }}" method="post"
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
                        {{ $mortuarytablets->appends ([
                            'cond_mortuarytablet' => @(Request::get('cond_mortuarytablet')),
                            ])->links() }}
                    </div>
                @else
                    <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                        <p class="font-bold">位牌区分の登録がありません</p>
                    </div>
                @endif
            </div> 
        </div>
    </div>

<script>
    function deleteMortuarytablet(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>
</x-app-layout>