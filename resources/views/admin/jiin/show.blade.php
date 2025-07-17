<x-admin-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')
    
    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">寺院詳細</div>
                <div class="inline-flex -mt-1">
                    <a class="btn-entry" href="{{ route('admin.user.create', $jiin->id) }}">
                        <i class="fa-solid fa-user-plus"></i><span class="mx-2">ユーザー追加</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6" >
                <div class="">
                    <div class="">
                            <table class="tableSample">
                                <tr>
                                    <th>寺院名</th>
                                    <td>{{ $jiin->jiin_name }}</td>
                                </tr>
                                <tr>
                                    <th>備考</th>
                                    <td>{{ $jiin->memo }}</td>
                                </tr>
                            </table>
                            <div class="form-btn">
                                <a class="form-btn2 form-inline-block" href="{{ route('admin.jiin.index') }}">
                                    <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-admin-layout>