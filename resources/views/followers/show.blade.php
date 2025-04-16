<x-app-layout>
    <link rel="stylesheet" href="/css/style.css">

    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">家族情報詳細</div>
                <div class="header-buttons">
                    <a href="{{ route('danka.show', $follower->danka_id) }}">
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
                                <th>氏名</th>
                                <td>{{ $follower->name }}</td>
                            </tr>
                            <tr>
                                <th>氏名かな</th>
                                <td>{{ $follower->namekana }}</td>
                            </tr>
                            <tr>
                                <th>続柄</th>
                                <td>{{ $follower->relationship }}</td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $follower->gender }}</td>
                            </tr>

                            <tr>
                                <th>生年月日</th>
                                <td>{{ $follower->birthdate }}</td>
                            </tr>  
                            <tr>  
                                <th>郵便番号</th>
                                <td>{{ $follower->postcode }}</td>
                            </tr>
                            <tr>
                                <th>住所１</th>
                                <td>{{ $follower->address1 }}</td>
                            </tr>
                            <tr>
                                <th>住所２</th>
                                <td>{{ $follower->address2 }}</td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td>{{ $follower->tel }}</td>
                            </tr>
                            <tr>
                                <th>FAX</th>
                                <td>{{ $follower->fax }}</td>
                            </tr>
                            <tr>
                                <th>寺役職</th>
                                <td>
                                    {{ $follower->position }}
                                </td>
                            </tr>
                            <tr>
                                <th>生前戒名</th>
                                <td>{{ $follower->seizenkaimyou }}</td>
                            </tr>
                            <tr>
                                <th>職業</th>
                                <td>
                                    {{ $follower->occupation }}
                                </td>
                            </tr>
                            <tr>
                                <th>備考</th>
                                <td>{{ $follower->memo }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>