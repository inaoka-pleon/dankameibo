<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="/css/dialog.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('dialog')
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">払込票</div>
                <div class="header-buttons">
                    <button id="printButton" class="header-btn">印刷</button>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-3">
            <hr class="w-full">
        </div>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6">
                <div class="text-gray-900">
                    <form action="{{ route('paymentslip.store') }}" method="POST">
                        @csrf

                        <div>
                            <table class="table-jiin">
                                <tr>
                                    <th class="registration-item">口座番号</th>
                                    <td class="registration-body">
                                        <input type="text" name="accountno1" class="registration-input-middle" placeholder="５文字" value="{{ old('accountno1') }}" /> ー
                                        <input type="text" name="accountno2" class="registration-input-middle" placeholder="１文字" value="{{ old('accountno2') }}" /> ー
                                        <input type="text" name="accountno3" class="registration-input-middle" placeholder="７文字以内" value="{{ old('accountno3') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">加入者名</th>
                                    <td class="registration-body">
                                        <input type="text" name="name" class="registration-input" value="{{ old('name') }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">金額</th>
                                    <td class="registration-body">
                                        <input type="text" name="price" class="registration-input-middle" value="{{ old('price') }}" /> 円
                                    </td>
                                </tr>
                                <tr>
                                    <th class="registration-item">部数</th>
                                    <td class="registration-body">
                                        <input type="text" id="copies" name="copies" class="registration-input" value="{{ old('copies', 1) }}" />
                                    </td>
                                </tr>
                            </table>
                            <div class="form-btn">
                                <a class="form-btn2 form-inline-block" href="{{ route('dankalist.index') }}">
                                    <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                                </a>
                                <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('登録します。よろしいですか？')">
                                    <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">登録</span>
                                </button>
                            </div>
                            <br>
                        </div>
                    </form>
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