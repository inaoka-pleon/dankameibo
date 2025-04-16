<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">はがき汎用一覧</div>
                <div class="header-buttons">
                    <a class="btn-entry" href="{{ route('generalpostcard.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規作成</span>
                    </a>
                    <a href="{{ route('postcard.index') }}">
                        <button class="btn-entry">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="table-scroll">
                    <!-- はがき汎用一覧表示 -->
                    @if ($general_postcards->isNotEmpty())
                        <table class="table-title radius-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="uramen_col sticky-head">
                                        <span></span>
                                    </th>
                                    <th class="uramen_title sticky-head">題名</th>
                                    <th class="uramen_createdate sticky-head">作成日</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($general_postcards as $general_postcard)
                                    <input type="hidden" name="general_postcard_id" value="{{ $general_postcard->id }}" /></td>
                                    <tr>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('generalpostcard.edit', $general_postcard->id)}}"  class="btn-other">選択</a>
                                                <button id="printButton-{{ $general_postcard->id }}" class="btn-other printButton" data-id="{{ $general_postcard->id }}">印刷</button>
                                            </div>
                                        </td>
                                        <td>{{ $general_postcard->title }}</td>

                                        <td>{{ $general_postcard->CreatedEraName. $general_postcard->CreatedEraYear. '年'. $general_postcard->CreatedMonth. '月'. $general_postcard->CreatedDay. '日' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">裏面印刷情報の登録がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printButtons = document.querySelectorAll('.printButton');

            printButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                    const id = this.getAttribute('data-id');
                    showListDialog(
                        `{{ route('generalpostcard.print', ['id' => '__ID__', 'action' => 'download']) }}`.replace('__ID__', id),
                        `{{ route('generalpostcard.print', ['id' => '__ID__', 'action' => 'display']) }}`.replace('__ID__', id),
                        "印刷"
                    );
                });
            });
        });
    </script>
</x-app-layout>