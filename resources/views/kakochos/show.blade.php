<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">過去帳詳細</div>
                <div class="header-buttons">
                    <a href="{{ route('danka.show', $kakocho->danka_id) }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
                <div class="">
                    <div class="">
                        <table class="tableSample">
                            <tr>
                                <th>戒名</th>
                                <td>{{ $kakocho->kaimyou }}</td>
                            </tr>
                            <tr>
                                <th>俗名</th>
                                <td>{{ $kakocho->zokumyou }}</td>
                            </tr>
                            <tr>
                                <th>俗名ふりがな</th>
                                <td>{{ $kakocho->zokumyoukana }}</td>
                            </tr>
                            <tr>
                                <th>命日</th>
                                <td>{{ $death_era->name . $kakocho->death_year. '年'. $kakocho->death_month. '月'. $kakocho->death_day. '日' }}</td>
                            </tr>

                            <tr>
                                <th>行年</th>
                                <td>
                                    @if(!empty($kakocho->ageatdeath))
                                        {{ $kakocho->ageatdeath }}歳
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr> 
                            <tr>
                                <th>関係</th>
                                <td>{{ $kakocho->relationship }}</td>
                            </tr> 
                            <tr>
                                <th>備考</th>
                                <td>{{ $kakocho->kakocho_memo }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>