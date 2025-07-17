<x-admin-layout>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">ユーザーマスタ</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="mb-4 w-full max-w-7xl">
                <div id="search_head" class="w-full px-4 md:px-6 py-2 text-left text-lg bg-gray-100 font-normal bg-custom-5 border border-b-2 border-gray-200 hover:underline hover:cursor-pointer active:underline rounded-t open">
                    <svg class="w-6 h-6 -mt-1 inline-flex mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件指定
                </div>
                <div id="search_body" class="px-6 sm:px-4 md:px-6 py-4 bg-white shadow-md border border-gray-200 rounded-b-xl" style="display: block;">
                    <form action="{{ route('admin.user.index') }}" method="GET">
                        <div class="grid grid-cols-12">
                            <label for="k_name" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">ユーザー名</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_user[name]" value="{{ $cond_user['name'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_name">
                            </div>
                            <label for="k_name_kana" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">ユーザー名かな</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_user[name_kana]" value="{{ $cond_user['name_kana'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_name_kana">
                            </div>
                            <label for="k_email" class="block col-span-12 sm:col-span-2 sm:py-4 pr-4 pl-2 sm:pl-0 font-semibold text-gray-700 sm:text-right text-sm sm:text-base">メールアドレス</label>
                            <div class="col-span-12 sm:col-span-4 py-1 sm:py-2 sm:pl-2">
                                <input type="text" name="cond_user[email]" value="{{ $cond_user['email'] ?? null }}" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus-ring-opacity-50 w-full" id="k_email">
                            </div>
                        </div>
                        <div class="mt-3 flex items-center sm:justify-end">
                            <button type="submit" class="btn-primary proc-btn text-xs shadow-sm py-3 px-4 mr-3" name="searchType" value="user_search">
                                <i class="fa-solid fa-magnifying-glass"></i><span class="mx-2">指定条件で検索</span>
                            </button>
                            <button type="button" class="btn-default text-xs reset shadow-sm py-3 px-4">
                                <i class="fa-regular fa-circle-xmark"></i><span class="mx-2">指定条件をリセット</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="">
                    @if ($users->isNotEmpty())
                        <div class="mb-2">
                           <p class="ml-2">該当：{{ $userCount }} 件</p> 
                        </div>
                        <table class="table-admin radius-table shadow">
                            <thead>
                                <tr class="">
                                    <th class="user_name">ユーザー名</th>
                                    <th class="user_name_kana">ユーザー名かな</th>
                                    <th class="user_email">メールアドレス</th>
                                    <th scope="col" class="user_col">
                                        <span></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="user_name" data-th="ユーザー名">{{ $user->name }}</td>
                                        <td class="user_name_kana" data-th="ユーザー名かな">{{ $user->name_kana }}</td>
                                        <td class="user_email" data-th="メールアドレス">{{ $user->email }}</td>
                                        <td class="user_col">
                                            <div class="btn-center">
                                                <a href="{{ route('admin.user.show', $user->id) }}" class="btn-show">
                                                <svg class="w-5 h-5 -mt-1 inline-flex" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z"></path>
                                                </svg>
                                                <span class="mx-2">詳細</span>
                                                </a>
                                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <form onsubmit="return deleteUser();"
                                                    class="btn-delete"
                                                    action="{{ route('admin.user.destroy', $user->id) }}" method="post"
                                                    role="menuitem" tabindex="-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-trash"></i><span class="mx-2">削除</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-content-center">
                            {{ $users->appends([
                                'cond_user' => @(Request::get('cond_user')),
                                ])->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">ユーザー情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchHead = document.getElementById('search_head');
            const searchBody = document.getElementById('search_body');

            searchHead.addEventListener('click', function() {
                if (searchBody.style.display === 'none' || searchBody.style.display === '') {
                    searchBody.style.display = 'block';
                } else {
                    searchBody.style.display = 'none';
                }
            });

            document.querySelector('.reset').addEventListener('click', function() {
                document.querySelectorAll('#search_body input[type="text"]').forEach(input => input.value = '');
                document.querySelectorAll('#search_body select').forEach(select => select.selectedIndex = 0);
            });
        });

        function deleteUser(){
            return confirm('削除します。よろしいですか？');
        }
    </script>
</x-admin-layout>

