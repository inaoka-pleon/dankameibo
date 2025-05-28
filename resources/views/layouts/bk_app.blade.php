<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .submenu {
            display: none; /* 初期状態は非表示 */
        }
        .open > .submenu {
            display: block; /* openクラスが付与されたら表示 */
        }
        .open > a > svg.arrow {
            transform: rotate(180deg); /* openクラスが付与されたら矢印を回転 */
        }
        .header {
            width: 100%; /* 幅いっぱいにする */
            background-color: #485262; /* 変更：ヘッダーの背景色をサイドバーと同じに */
            color: #d1d5db; /* 変更：ヘッダーの文字色をサイドバーと同じに */
            border-bottom: 1px solid #616a78; /* 変更：下線の色を調整 */
            padding: 0.5rem 1rem; /* 例：ヘッダーのpadding */
            display: flex;
            justify-content: space-between; /* ロゴとユーザー情報を左右に配置 */
            align-items: center;
        }
        .header a {
            color: #d1d5db; /* ヘッダー内のリンクの色も変更 */
        }
        .header a:hover {
            background-color: #616a78; /* ヘッダー内のリンクのホバー色も調整 */
            color: #fff; /* ホバー時の文字色 */
        }
        .header-logo {
            /* ロゴのスタイル */
        }
        .sidebar {
            width: 12%; /* 例：サイドバーの幅 */
            min-width: 180px;
            background-color: #fff; /* 変更：サイドバーの背景色をヘッダーと同じに */
            color: #4b5563; /* 変更：サイドバーの文字色をヘッダーの文字色に近い色に */
            height: auto; /* ヘッダーの高さ分を引く（調整が必要な場合あり） */
            padding-top: 1rem;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            border-right: 1px solid #d1d5db; /* 区切り線を追加 */
        }
        .menu a {
            color: #4b5563; /* サイドバーメニューの文字色 */
        }
        .menu a:hover {
            background-color: #f3f4f6; /* サイドバーメニューのホバー色 */
            color: #1e293b; /* サイドバーメニューのホバー時の文字色 */
        }
        .main-content {
            flex-grow: 1; /* メインコンテンツが残りのスペースを埋める */
            /* padding: 1rem; */
            padding: 0rem 1.5rem;
            background-color: white; /* 例：メインコンテンツの背景色 */
        }
        .flex-container {
            display: flex;
            flex-direction: column; /* ヘッダーとコンテンツを縦に並べる */
            min-height: 100vh; /* 画面の高さいっぱいにする */
        }
        .content-area {
            display: flex; /* サイドバーとメインコンテンツを横に並べる */
            flex-grow: 1; /* 残りのスペースを埋める */
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex-container bg-white">
        {{-- ヘッダー --}}
        <nav x-data="{ open: false }" class="header">
            <div class="header-logo">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    <span class="ml-2 text-lg font-semibold text-white">テスト</span>
                </a>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-600 hover:text-gray-400 focus:outline-none transition ease-in-out duration-150">
                            <div class="text-white">{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-900 hover:bg-gray-100">
                            {{ __('ユーザー情報') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-gray-900 hover:bg-gray-100">
                                {{ __('ログアウト') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </nav>

        <div class="content-area">
            {{-- サイドバー --}}
            <aside class="sidebar">
                <ul class="menu">
                    <li class="active">
                        <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                                <path d="M16.972 10.971a1 1 0 0 0-1.414 0L12 14.535l-3.558-3.564a1 1 0 0 0-1.414 1.414l4.243 4.242a1 1 0 0 0 1.414 0L16.972 10.971Z"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1 0a1 1 0 0 0-1 1v17a1 1 0 0 0 1 1h20a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1Zm20 1H1v17h20V1Zm-9.293 4.293a1 1 0 0 0 0 1.414l2 2a1 1 0 0 0 1.414 0l2-2a1 1 0 1 0-1.414-1.414L12 6.586l-1.293-1.293a1 1 0 0 0-1.414 1.414Z"/>
                            </svg>
                            <span class="ml-3 text-gray-900">ダッシュボード</span>
                        </a>
                    </li>
                    <li class="has-submenu">
                        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path d="M16 5h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-2v2a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1h2V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3Zm2 4h-2V7h2v2Zm-4 0H6V7h8v2Zm-6-3H2v2h8V6H8Zm0 3H2v2h8V9H8Zm5 3H2a1 1 0 0 0-1 1v2h9v-2a1 1 0 0 0-1-1Zm7-1h2v2h-2v-2Z"/>
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap text-gray-900">マスタ</span>
                            <svg class="w-3 h-3 ml-3 arrow text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </a>
                        <ul class="submenu py-2 space-y-2">
                            <li>
                                <a href="{{ route('generalmaster.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    汎用マスタ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('templemaster.createOrEdit') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    自寺院マスタ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('era.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    元号設定
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kaiki.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    回忌設定
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path d="M16 5h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-2v2a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1h2V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3Zm2 4h-2V7h2v2Zm-4 0H6V7h8v2Zm-6-3H2v2h8V6H8Zm0 3H2v2h8V9H8Zm5 3H2a1 1 0 0 0-1 1v2h9v-2a1 1 0 0 0-1-1Zm7-1h2v2h-2v-2Z"/>
                            </svg>
                            <span class="flex-1 ml-3 whitespace-nowrap text-gray-900">檀信徒名簿</span>
                            <svg class="w-3 h-3 ml-3 arrow text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </a>
                        <ul class="submenu py-2 space-y-2">
                            <li>
                                <a href="{{ route('danka.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    檀家一覧
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dankalist.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    一覧表印刷
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('postcard.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    はがき・封筒
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kaimyoucheck.index') }}" class="flex items-center p-2 pl-11 w-full text-sm text-gray-900 rounded-lg hover:bg-gray-100 group">
                                    同名戒名検索
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>

            {{-- メインコンテンツ --}}
            <main class="main-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hasSubmenuItems = document.querySelectorAll('.menu li.has-submenu > a');

            hasSubmenuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parentLi = this.parentNode;
                    parentLi.classList.toggle('open');
                });
            });
        });
    </script>
</body>
</html>