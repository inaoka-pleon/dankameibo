<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">回忌別霊簿一覧表</h2>
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('kaikireibolist.print') }}">一覧表印刷</a>
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
                                <form aciton="{{ route('kaikireibolist.index') }}" method="GET" >
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        <div>
                                            <select id="eraSelect" name="target_year[era]" class="">
                                                @foreach($eras as $era)
                                                    <option value="{{ $era->id }}" @if((old('target_year.era', $target_year['era'] ?? '') == $era->id) || (empty($target_year['era']) && $currentEraId == $era->name)) selected @endif>{{ $era->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="k_year" class="text-xs"></label>
                                            <input type="search" id="yearInput" name="target_year[year]" min="1" max="100" value="{{ old('target_year.year', $target_year['year'] ?? $currentEraYear) }}" id="k_year" class="search-input" />年度
                                        </div>
                                        <div>
                                            <select id="kaikiSelect" name="target_kaiki[kaiki]" class="">
                                                @foreach($kaikis as $kaiki)
                                                    <option value="{{ $kaiki->id }}" @if(old('target_kaiki.kaiki', $target_kaiki['kaiki'] ?? '') == $kaiki->id) || (empty($target_kaiki['kaiki'])) @endif>{{ $kaiki->kaiki }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="my-4">
                                            <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="kaikireibo_search">指定条件で検索する</button>
                                            <button type="button" class="btn-default text-xs reset">指定条件をリセットする</button>
                                        </div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 回忌別霊簿一覧表示 -->
                     @if ($kaikireibolists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="">代表者名</th>
                                    <th class="">住所</th>
                                    <th class="">電話番号</th>
                                    <th class="">回忌</th>
                                    <th class="">戒名</th>
                                    <th class="">俗名</th>
                                    <th class="">命日</th>
                                    <th class="">行年</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($kaikireibolists as $kaikireibo)
                                    <tr>
                                        <td>{{ $kaikireibo->chief_name }}</td>
                                        <td>{{ $kaikireibo->address1 . $kaikireibo->address2 }}</td>
                                        <td>{{ $kaikireibo->tel }}</td>
                                        <td></td>
                                        <td>{{ $kaikireibo->kaimyou }}</td>
                                        <td>{{ $kaikireibo->zokumyou }}</td>
                                        <td>{{ $kaikireibo->name . $kaikireibo->death_year. '年'. $kaikireibo->death_month. '月'. $kaikireibo->death_day. '日' }}</td>
                                        <td>{{ $kaikireibo->ageatdeath }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-cntent-center">
                            {{ $kaikireibolists->appends([
                                'cond_kaikireibo' => @(Request::get('cond_kaikireibo')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">回忌霊簿情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
     </div>
</x-app-layout>