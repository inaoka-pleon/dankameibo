<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">護持会会費受付一覧</h2>
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('gozikaikaihilist.print') }}">一覧表印刷</a>
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
                                <form id="searchForm" action="{{ route('gozikaikaihilist.index') }}" method="GET" >
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        <div class="my-1">
                                            <br>
                                            <label for="k_era" class="text-xs"></label>
                                            <div>
                                                <select id="eraSelect" name="target_year[era]" class="">
                                                    @foreach($eras as $era)
                                                        <option value="{{ $era->id }}" @if((old('target_year.era', $target_year['era'] ?? '') == $era->id) || (empty($target_year['era']) && $currentEraName == $era->name)) selected @endif>{{ $era->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="k_year" class="text-xs"></label>
                                                <input type="search" id="yearInput" name="target_year[year]" min="1" max="100" value="{{ old('target_year.year', $target_year['year'] ?? $currentEraYear) }}" id="k_year" class="search-input" />年度
                                            </div>
                                            <button type="submit" style="display: none;"></button>
                                            <div class="my-1">
                                            <label for="k_name" class="text-xs">氏名</label>
                                            <div>
                                                <input type="search" name="cond_gozikaikaihi_list[name]" value="{{ $cond_gozikaikaihi_list['name'] ?? null }}" class="text-xs w-full lg:w-1/2" id="k_name">
                                            </div>
                                        </div>
                                        <div class="my-1">
                                            <label for="k_namekana" class="text-xs">氏名かな</label>
                                            <div>
                                                <input type="search" name="cond_gozikaikaihi_list[namekana]" value="{{ $cond_gozikaikaihi_list['namekana'] ?? null }}" class="text-xs w-full lg:w-1/2" id="k_namekana">
                                            </div>
                                        </div>
                                        <div class="my-1">
                                            <label for="k_tel" class="text-xs">電話番号</label>
                                            <div>
                                                <input type="search" name="cond_gozikaikaihi_list[tel]" value="{{ $cond_gozikaikaihi_list['tel'] ?? null }}" class="text-xs w-full lg:w-1/2" id="k_tel">
                                            </div>
                                        </div>
                                        </div>
                                        <div class="my-4">
                                            <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="gozikaikaihi_search">指定条件で検索</button>
                                            <button type="button" class="btn-default text-xs reset">指定条件をリセット</button>
                                        </div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 護持会会費受付一覧表示 -->
                     @if ($gozikaikaihilists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="kaihi_name">氏名</th>
                                    <th class="kaihi_namekana">氏名かな</th>
                                    <th class="kaihi_area">地区名</th>
                                    <th class="kaihi_tel">電話番号</th>
                                    <th class="kaihi_payment_date">入金日</th>
                                    <th class="kaihi_payment_class">入金区分</th>
                                    <th class="kaihi_deposit_amount">入金額</th>
                                    <th class="kaihi_memo">備考</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($gozikaikaihilists as $gozikaikaihilist)
                                    <tr>
                                        <input type="hidden" name="gozikaikaihilist_id_{{$i}}" value="{{$gozikaikaihilist->id }}" /> 
                                        <input type="hidden" name="gozikaikaihilist_danka_id_{{$i}}" value="{{ $gozikaikaihilist->danka_id }}" />
                                        <td>{{ $gozikaikaihilist->name }}</td>
                                        <td>{{ $gozikaikaihilist->namekana }}</td>
                                        <td>{{ $gozikaikaihilist->area }}</td>
                                        <td>{{ $gozikaikaihilist->tel }}</td>
                                        <td>
                                            @if (!empty($gozikaikaihilist->era_name) && !empty($gozikaikaihilist->era_year) && !empty($gozikaikaihilist->month) && !empty($gozikaikaihilist->day))    
                                                {{ $gozikaikaihilist->era_name }}{{ $gozikaikaihilist->era_year }}年{{ $gozikaikaihilist->month }}月{{ $gozikaikaihilist->day }}日
                                            @else
                                            @endif
                                        </td>
                                        <td>{{ $gozikaikaihilist->payment_class }}</td>
                                        <td>{{ $gozikaikaihilist->deposit_amount }}</td>
                                        <td>{{ $gozikaikaihilist->memo }}</td>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('gozikaikaihilist.edit', ['danka_id' => $gozikaikaihilist->danka_id, 'id' => $gozikaikaihilist->id, 'target_year' => JA_to_AD_conv('era_id', 'year')] )}}" class="btn-other">編集</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-content-center">
                            {{ $gozikaikaihilists->appends([
                                'cond_gozikaikaihi_list' => @(Request::get('cond_gozikaikaihi_list')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">護持会会費受付一覧情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>