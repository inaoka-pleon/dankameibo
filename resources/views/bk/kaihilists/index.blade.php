<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">年度別護持会会費受付一覧</h2>
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('kaihilist.print') }}">一覧表印刷</a>
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
                                <form action="{{ route('kaihilist.index') }}" method="GET">
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        <div class="my-1">
                                            <br>
                                            <label for="k_era" class="text-xs"></label>
                                            <div>
                                                <select name="target_year[era]" class="">
                                                    @foreach($eras as $era)
                                                        <option value="{{ $era->id }}" @if((old('target_year.era', $target_year['era'] ?? '') == $era->id) || (empty($target_year['era']) && $currentEraName == $era->name)) selected @endif>{{ $era->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="k_year" class="text-xs"></label>
                                                <input type="search" name="target_year[year]" min="1" max="100" value="{{ old('target_year.year', $target_year['year'] ?? $currentEraYear) }}" id="k_year" class="search-input" />年
                                            </div>
                                        </div>
                                        <div class="my-4">
                                            <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="kaihilist_search">指定条件で検索</button>
                                            <button type="button" class="btn-default text-xs reset">指定条件をリセット</button>
                                        </div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 護持会会費受付一覧表示 -->
                    @if ($kaihilists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="kaihi_name">氏名</th>
                                    <th class="kaihi_namekana">氏名かな</th>
                                    @foreach ($columns as $column)
                                        <th class="kaihi_year">{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $danka_id => $details)
                                    <tr>
                                        <input type="hidden" name="kaihilist_id_{{$loop->index + 1}}" value="{{ $danka_id }}" />
                                        <td>{{ $details['name'] }}</td>
                                        <td>{{ $details['namekana'] }}</td>
                                        @foreach ($columns as $column)
                                            @php
                                                $ad_year = $year_map[$column];
                                            @endphp
                                            <td>{{ $details['years'][$ad_year] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-content-center">
                            {{ $kaihilists->appends([
                                'target_year' => @(Request::get('target_year')),
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