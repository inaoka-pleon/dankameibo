<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">寺院名簿</h2>
        <div class="flex flex-row-reverse">
            <a class="header-btn" href="{{ route('templelist.print') }}">一覧表印刷</a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="hs-accordion-group" data-hs-accordion-always-open="">
                        <div class="hs-accordion active" id="hs-basic-always-open-heading-one">
                            <div id="hs-basic-always-open-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-always-open-heading-one">
                                <form aciton="{{ route('templelist.index') }}" method="GET" >
                                    <p class="text-gray-800 dark:text-neutral-200">
                                        <div class="my-1">
                                            <label for="k_templeoffice" class="text-xs">宗務所</label>
                                            <div>
                                                <select type="" name="cond_templelist[templeoffice]" class="">
                                                    <option value=""></option>
                                                    @foreach($templeoffices as $templeoffice)
                                                        <option value="{{ $templeoffice->value1 }}" @if(!empty($cond_templelist['templeoffice']) && $cond_templelist['templeoffice'] === $templeoffice->value1) selected @endif>{{ $templeoffice->value1 }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="my-1">
                                            <label for="k_parish" class="text-xs">教区</label>
                                            <div>
                                                <select type="" name="cond_templelist[parish]" class="">
                                                    <option value=""></option>
                                                    @foreach(App\Consts\ParishConsts::PARISH_LIST as $name => $number)
                                                        <option value="{{ $name }}" @if(!empty($cond_templelist['parish']) && $cond_templelist['parish'] === $name) selected @endif>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="my-4">
                                            <button type="submit" class="btn-primary proc-btn text-xs" name="searchType" value="templelist_search">指定条件で検索する</button>
                                            <button type="button" class="btn-default text-xs reset">指定条件をリセットする</button>
                                        </div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 寺院名簿一覧表示 -->
                     @if ($templelists->isNotEmpty())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="templeoffice5">宗務所</th>
                                    <th class="parish5">教区</th>
                                    <th class="no5">寺籍番号</th>
                                    <th class="postcode5">郵便番号</th>
                                    <th class="templename5">寺院名</th>
                                    <th class="qualification5">資格</th>
                                    <th class="name5">氏名</th>
                                    <th class="tel5">電話番号</th>
                                    <th class="address5">住所</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templelists as $templelist)
                                    <tr>
                                        <td>{{ $templelist->templeoffice }}</td>
                                        <td>{{ $templelist->parish }}</td>
                                        <td>{{ $templelist->no }}</td>
                                        <td>{{ $templelist->postcode }}</td>
                                        <td>{{ $templelist->templename }}</td>
                                        <td>{{ $templelist->qualification }}</td>
                                        <td>{{ $templelist->name }}</td>
                                        <td>{{ $templelist->tel }}</td>
                                        <td>{{ $templelist->address1 . $templelist->address2 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-cntent-center">
                            {{ $templelists->appends([
                                'cond_templelist' => @(Request::get('cond_templelist')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">寺院名簿情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
     </div>
</x-app-layout>