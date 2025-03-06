<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css" >

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">払込票</div>
                <div class="inline-flex -mt-1">
                    <form action="{{ route('paymentslip.print', ['copies' => request('copies', 1)]) }}" method="get">
                        <a id="printButton" class="header-btn">印刷</a>
                    </form>
                    <a href="{{ route('dankalist.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form action="{{ route('paymentslip.update', $paymentslip->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="flex flex-row-reverse">
                                <button type="submit" class="header-btn"
                                onclick="return confirm('更新します。よろしいですか？')">更新</button>
                            </div>
                            <br>
                            <div>
                                <table class="table-jiin">
                                    <tr>
                                        <th class="registration-item">口座番号</th>
                                        <td class="registration-body">
                                            <input type="text" name="accountno1" class="" placeholder="５文字" value="{{ old('accountno1', $paymentslip->accountno1) }}" /> ー
                                            <input type="text" name="accountno2" class="" placeholder="１文字" value="{{ old('accountno2', $paymentslip->accountno2) }}" /> ー
                                            <input type="text" name="accountno3" class="" placeholder="７文字以内" value="{{ old('accountno3', $paymentslip->accountno3) }}" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="registration-item">加入者名</th>
                                        <td class="registration-body">
                                            <input type="text" name="name" class="registration-input" value="{{ old('name', $paymentslip->name) }}" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="registration-item">金額</th>
                                        <td class="registration-body">
                                            <input type="text" name="price" class="" value="{{ old('price', $paymentslip->price) }}" /> 円
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="registration-item">部数</th>
                                        <td class="registration-body">
                                            <input type="text" id="copies" name="copies" class="registration-input" value="{{ old('copies', session('copies', 1)) }}" />
                                        </td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="/js/dialog.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('printButton').addEventListener('click', function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                showListDialog("{{ route('paymentslip.print', ['action' => 'download']) }}", "{{ route('paymentslip.print', ['action' => 'display']) }}", "一覧表印刷");
            });
        });
        // copies の変更を監視して、値を即座にセッションに保存するAJAX
        document.getElementById('copies').addEventListener('input', function() {
            var copies = this.value;
            // AJAX リクエストでコピーの値をセッションに保存
            fetch("{{ route('paymentslip.updateCopies') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ copies: copies })
            })
            .then(response => response.json())
            .then(data => {
                // 成功した場合の処理（必要に応じて）
                console.log("Copies updated in session:", data);
            })
            .catch(error => {
                console.error("Error updating copies:", error);
            });
        });
    </script>
</x-app-layout>