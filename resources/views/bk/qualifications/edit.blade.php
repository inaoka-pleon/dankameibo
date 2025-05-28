<x-app-layout>
    <x-slot name="header">
        <h2 class="header header-inner">資格 / 編集</h2>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css" >
    <!-- <link rel="stylesheet" href="/css/bootstrap.css"> -->
    <link rel="stylesheet" href="/css/app.css" >

    <div class="registration">
        <form action="{{ route('qualification.update', $qualification->id) }}" method="post">
            @csrf
            @method('PATCH')

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
                        <th class="registration-item">資格
                            <span class="registration-item-required">必須</span>
                        </th>
                        <td class="registration-body">
                            <input type="text" name="qualification" class="registration-input" value="{{ $qualification->qualification }}" />  
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-btn">
                <a class="form-btn2 form-inline-block" href="{{ route('qualification.index') }}">戻る</a>
                <button type="submit" class="form-btn1 form-inline-block"
                        onclick="return confirm('変更します。よろしいですか？')">変更</button>
            </div>
        </form>
    </div>
</x-app-layout>