<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">春彼岸一覧</div>
                <div class="inline-flex -mt-1">
                    <a href="{{ route('haruhiganheader.create') }}">
                        <button class="header-btn">新規作成</button>
                    </a>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="table-scroll">
                    <!-- 春彼岸一覧表示 -->
                    @if ($haruhigan_headers->isNotEmpty())
                        <table class="table-title radius-table shadow">
                            <thead>
                                <tr>
                                    <th scope="col" class="haruhigan_col sticky-head">
                                        <span></span>
                                    </th>
                                    <th class="haruhigan_title sticky-head">題名</th>
                                    <th class="haruhigan_createdate sticky-head">作成日</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($haruhigan_orderbys as $haruhigan_orderby)
                                    <tr>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('haruhiganheader.edit', $haruhigan_orderby->id)}}"  class="btn-other">選択</a>
                                            </div>
                                        </td>
                                        <input type="hidden" value="{{ $haruhigan_orderby->ad_year }}" />
                                        <td>{{ $haruhigan_orderby->era . $haruhigan_orderby->year . '年' . '　春彼岸' }}</td>
                                        
                                        <td>{{ $haruhigan_orderby->CreatedEraName. $haruhigan_orderby->CreatedEraYear. '年'. $haruhigan_orderby->CreatedMonth. '月'. $haruhigan_orderby->CreatedDay. '日' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">春彼岸情報の登録がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-app-layout>