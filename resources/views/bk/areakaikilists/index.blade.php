<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">地区別回忌一覧表</h2>
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('areakaikilist.print') }}">一覧表印刷</a>
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
                                <form aciton="{{ route('arealist.index') }}" method="GET" >
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        <label for="k_era" class="text-xs"></label>
                                        <div>
                                            <select id="eraSelect" name="target_year[era]" class="">
                                                @foreach($eras as $era)
                                                    <option value="{{ $era->id }}" @if((old('target_year.era', $target_year['era'] ?? '') == $era->id) || (empty($target_year['era']) && $currentEraId == $era->name)) selected @endif>{{ $era->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="k_year" class="text-xs"></label>
                                            <input type="search" id="yearInput" name="target_year[year]" min="1" max="100" value="{{ old('target_year.year', $target_year['year'] ?? $currentEraYear) }}" id="k_year" class="search-input" />年度
                                        </div>
                                        <button type="submit" style="display: none;"></button>
                                        <div class="my-1">
                                            <label for="k_area" class="text-xs">地区名</label>
                                            <div>
                                                <select type="" name="cond_areakaiki[area]" class="">
                                                    <option value=""></option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->value1 }}" @if(!empty($cond_areakaiki['area']) && $cond_areakaiki['area'] === $area->value1) selected @endif>{{ $area->value1 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="my-4">
                                            <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="areakaiki_search">指定条件で検索する</button>
                                            <button type="button" class="btn-default text-xs reset">指定条件をリセットする</button>
                                        </div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 地区別名簿一覧表示 -->
                     @if ($areakaikilists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="">氏名</th>
                                    <th class="">地区名</th>
                                    <th class="">回忌</th>
                                    <th class="">戒名</th>
                                    <th class="">俗名</th>
                                    <th class="">命日</th>
                                    <th class="">供養</th>
                                    <th class="">住所</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($areakaikilists as $areakaiki)
                                    <tr>
                                        <td>{{ $areakaiki->chief_name }}</td>
                                        <td>{{ $areakaiki->area }}</td>
                                        <td>{{ $areakaiki->kaiki }}</td>
                                        <td>{{ $areakaiki->kaimyou }}</td>
                                        <td>{{ $areakaiki->zokumyou }}</td>
                                        <td>{{ $areakaiki->death_era_name . $areakaiki->death_year. '年'. $areakaiki->death_month. '月'. $areakaiki->death_day. '日' }}</td>
                                        <td>
                                            <input type="hidden" name="kuyou_{{$i}}" value="0">
                                            <input type="checkbox" name="kuyou_{{$i}}" id="kuyou_{{$i}}" value="1" @if(old('kuyou', $areakaiki->kuyou)) checked @endif>
                                        </td>
                                        <td>{{ $areakaiki->address1 . $areakaiki->address2 }}</td>
                                    </tr>
                                    @php($i++)
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                      
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">地区別名簿情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
     </div>
</x-app-layout>