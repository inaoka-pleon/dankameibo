<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">檀信徒各家霊簿一覧表</h2>
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('kakuiereibolist.print') }}">一覧表印刷</a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">



     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="hs-accordion-group" data-hs-accordion-always-open="">
                        <div class="hs-accordion active" id="hs-basic-always-open-heading-one">
                            <div id="hs-basic-always-open-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-always-open-heading-one">
                                <form aciton="{{ route('kakuiereibolist.index') }}" method="GET" >
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        <div class="my-1">
                                            <label for="k_area" class="text-xs">地区名</label>
                                            <div>
                                                <select type="" name="cond_kakuiereibo[area]" class="">
                                                    <option value=""></option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->value1 }}" @if(!empty($cond_kakuiereibo['area']) && $cond_kakuiereibo['area'] === $area->value1) selected @endif>{{ $area->value1 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="my-4">
                                            <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="kakuiereibo_search">指定条件で検索する</button>
                                            <button type="button" class="btn-default text-xs reset">指定条件をリセットする</button>
                                        </div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 地区別名簿一覧表示 -->
                     @if ($kakuiereibolists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="name2">氏名</th>
                                    <th class="dankadivision1">檀家区分</th>
                                    <th class="area1">地区名</th>
                                    <th class="postcode1">郵便番号</th>
                                    <th class="address1">住所</th>
                                    <th class="tel2">電話番号</th>
                                    <th class="postcard1">はがき区分</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kakuiereibolists as $kakuiereibolist)
                                    <tr>
                                        <td>{{ $kakuiereibolist->name }}</td>
                                        <td>{{ $kakuiereibolist->dankadivision }}</td>
                                        <td>{{ $kakuiereibolist->area }}</td>
                                        <td>{{ $kakuiereibolist->postcode }}</td>
                                        <td>{{ $kakuiereibolist->address1 . $kakuiereibolist->address2 }}</td>
                                        <td>{{ $kakuiereibolist->tel }}</td>
                                        <td>{{ $kakuiereibolist->postcard }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-cntent-center">
                            {{ $kakuiereibolists->appends([
                                'kakuiereibolist' => @(Request::get('cond_kakuiereibo')),
                                ])->links() }}
                        </div>
                    @else
                        @if (request()->has('searchType'))
                            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                                <p class="font-bold">各家霊簿情報がありません</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
     </div>
</x-app-layout>