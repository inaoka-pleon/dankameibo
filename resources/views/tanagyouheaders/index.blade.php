<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('errors.form_errors')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">棚経参一覧</div>
                <div class="header-buttons">
                    <a class="btn-entry" href="{{ route('tanagyouheader.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規作成</span>
                    </a>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="btn-entry">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4">
            <div class="w-full md:mb-4 max-w-3xl mt-2 text-sm sm:text-base">
                <div class="table-scroll">
                    @if ($tanagyou_headers->isNotEmpty())
                        <table class="table-title radius-table shadow">
                            <thead>
                                <tr>
                                    <th scope="col" class="tanagyou_col sticky-head">
                                        <span></span>
                                    </th>
                                    <th class="tanagyou_title sticky-head">題名</th>
                                    <th class="tanagyou_createdate sticky-head">作成日</th>
                                    <th class="border text-xl px-4 py-2 visible md:hidden">
                                        題名
                                        作成日
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tanagyou_orderbys as $tanagyou_orderby)
                                    <tr>
                                        <td data-th="">
                                            <div class="btn-center">
                                                <a href="{{ route('tanagyouheader.edit', $tanagyou_orderby->id)}}"   class="btn-other">選択</a>
                                            </div>
                                        </td>
                                        <input type="hidden" value="{{ $tanagyou_orderby->ad_year }}" />
                                        <td data-th="題名">{{ $tanagyou_orderby->era . $tanagyou_orderby->year . '年' . '　棚経' }}</td>
                                        <td data-th="作成日">{{ $tanagyou_orderby->CreatedEraName. $tanagyou_orderby->CreatedEraYear. '年'. $tanagyou_orderby->CreatedMonth. '月'. $tanagyou_orderby->CreatedDay. '日' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">棚経参情報の登録がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-app-layout>