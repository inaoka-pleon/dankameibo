<x-app-layout>
    <x-slot name="header">
        <h2 class="header header-inner">護持会会費受付一覧表 / 編集</h2>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >

    <div class="registration">
        <form action="{{ route('gozikaikaihilist.update', ['danka_id' => $gozikaikaihilist->danka_id, 'id' => $gozikaikaihilist->id] )}}" method="post">
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounred-md p-4 my-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="registration-table">
                <tbody>
                    <tr>
                        <input type="hidden" name="id" value="{{ $id ?? '' }}" />
                        <input type="hidden" name="danka_id" value="{{ $danka_id }}" />
                        <input type="hidden" name="target_year" value="{{ JA_to_AD_conv($target_year['era'], $target_year['year'] ) ?? ''}}"/>
                        <th class="registration-item">入金日</th>
                        <td class="registration-body">
                            <input type="date" name="payment_date" class="registration-input" value="{{ old('payment_date', $gozikaikaihilist->payment_date ?? '') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">入金区分</th>
                        <td class="registration-body">
                            <select id="payment_class" name="payment_class" class="registration-select">
                                <option value=""></option>
                                @foreach($gozikaikaihis as $gozikaikaihi)
                                    @if($gozikaikaihi->value1 === $gozikaikaihilist->payment_class)
                                        <option value="{{ $gozikaikaihi->value1 }}" selected>{{ $gozikaikaihi->value1 }}</option>
                                    @else
                                        <option value="{{ $gozikaikaihi->value1 }}">{{ $gozikaikaihi->value1 }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">入金額</th>
                        <td class="registration-body">
                            <input type="text" name="deposit_amount" id="deposit_amount" class="registration-input" value="{{ old('deposit_amount', $gozikaikaihilist->deposit_amount ?? '') }}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="registration-item">備考</th>
                        <td class="registration-body">
                            <textarea type="text" name="memo" class="registration-textarea" value="{{ old('memo', $gozikaikaihilist->memo ?? '') }}">{{ $gozikaikaihilist->memo }}</textarea>
                        </td>
                    </tr>
                    <!-- <tr>
                        <th class="registration-item">年度</th>
                        <td class="registration-body">
                            <input type="text" name="target_year" class="registration-input" value="{{ JA_to_AD_conv($target_year['era'], $target_year['year'] ) ?? '' }}" readonly />
                        </td>
                    </tr> -->
                </tbody>
            </table>
            <div class="form-btn">
                <a class="form-btn2 form-inline-block" href="{{ route('gozikaikaihilist.index') }}">戻る</a>
                <button type="submit" class="form-btn1 form-inline-block"
                        onclick="return confirm('変更します。よろしいですか？')">変更</button>
            </div>
        </form>
    </div>
    <script>
        // 入金区分を選択したら入金額を表示
        document.addEventListener('DOMContentLoaded', function () {
            const paymentClass = document.getElementById('payment_class');
            const depositAmount = document.getElementById('deposit_amount');

            paymentClass.addEventListener('change', function () {
                if (paymentClass.value === '現金') {
                    depositAmount.value = '3,000';
                } else if (paymentClass.value === '振込') {
                    depositAmount.value = '2,930';
                } else {
                    depositAmount.value = '3,000';
                }
            });
        });
    </script>
</x-app-layout>
          