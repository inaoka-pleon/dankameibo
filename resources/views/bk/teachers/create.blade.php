<x-app-layout>
    <x-slot name="header">
        <h2 class="header header-inner">師 / 新規登録</h2>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/app.css" >

    <div class="registration">
        <form action="{{ route('teacher.store') }}" method="post">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4 mb-4">
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
                        <th class="registration-item">師
                            <span class="registration-item-required">必須</span>
                        </th>
                        <td class="registration-body">
                            <input type="text" name="teacher" class="registration-input" placeholder="師を入力してください" />
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="form-btn">
                <a class="form-btn2 form-inline-block" href="{{ route('teacher.index') }}">戻る</a>
                <button type="submit" class="form-btn1 form-inline-block"
                        onclick="return confirm('登録します。よろしいですか？')">登録する</button>
            </div>
        </form>
    </div>
</x-app-layout>