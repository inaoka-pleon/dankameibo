<x-app-layout>

    @vite('/css/style.css')
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">元号設定 / 新規作成</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="registration">
            <form action="{{ route('era.store') }}" method="post">
                @csrf

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
                            <th class="registration-item">元号
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="text" name="name" class="registration-input" value="{{ old('name') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">開始年月日
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="date" name="start_ymd" class="registration-input" value="{{ old('start_ymd') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">終了年月日
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input type="date" name="end_ymd" class="registration-input" value="{{ old('end_ymd') }}" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('era.index') }}">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <button type="submit" class="form-btn1 form-inline-block"　onclick="return confirm('登録します。よろしいですか？')">
                         <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">登録</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
