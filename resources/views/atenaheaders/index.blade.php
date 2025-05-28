<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">宛名印刷</div>
                <div class="header-buttons">
                    <a class="btn-entry" href="{{ route('atenaheader.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規作成</span>
                    </a>
                    <a href="{{ route('postcard.index') }}">
                        <button class="btn-entry">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4">
            <div class="w-full md:mb-4 max-w-3xl mt-3 text-sm sm:text-base">
                <div class="table-scroll">
                    <!-- 宛名印刷一覧表示 -->
                    @if ($atena_headers->isNotEmpty())
                        <table class="table-title radius-table shadow">
                            <thead>
                                <tr>
                                    <th scope="col" class="atena_col sticky-head">
                                        <span></span>
                                    </th>
                                    <th class="atena_title sticky-head">題名</th>
                                    <th class="atena_createdate sticky-head">作成日</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($atena_headers as $atena_header)
                                    <tr>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('atenaheader.show', $atena_header->id)}}"  class="btn-other">選択</a>
                                            </div>
                                        </td>
                                        <td>{{ $atena_header->title }}</td>

                                        <td>{{ $atena_header->CreatedEraName. $atena_header->CreatedEraYear. '年'. $atena_header->CreatedMonth. '月'. $atena_header->CreatedDay. '日' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">宛名印刷情報の登録がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-app-layout>