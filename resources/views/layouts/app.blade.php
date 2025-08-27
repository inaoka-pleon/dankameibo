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
    <link rel="stylesheet" href="/css/style.css" > {{-- style.cssの読み込み --}}
    {{-- <link rel="stylesheet" href="/css/app.css" > --}} {{-- @viteでapp.cssは読み込まれているはずなのでコメントアウト --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* サブメニュー開閉のスタイルはそのまま */
        .submenu {
            display: none; /* 初期状態は非表示 */
        }
        .open > .submenu {
            display: block; /* openクラスが付与されたら表示 */
        }
        /* openクラスが付与されたら矢印を回転 - アイコンを削除したのでこのルールは不要になる可能性 */
        /* .open > a > svg.arrow {
            transform: rotate(180deg);
        } */

        /* -------- レスポンシブ対応CSS -------- */

        /* 新しいトップヘッダーのスタイル */
        .top-header {
            display: none; /* デフォルトは非表示 (大きい画面用) */
            align-items: center;
            justify-content: space-between; /* 子要素を両端に配置 */
            padding: 0.5rem 1rem; /* px-4 py-3 に相当 */
            background-color: #485262; /* ヘッダー背景色 */
            color: #d1d5db; /* ヘッダー文字色 */
            border-bottom: 1px solid #616a78; /* 下線 */
            position: fixed; /* 上部に固定 */
            top: 0;
            left: 0;
            width: 100%;
            z-index: 40; /* サイドバーより下 */
        }
        /* トップヘッダー内のタイトル/ロゴを含む要素 */
        .top-header .header-title-area {
             display: flex; /* ロゴとタイトルをFlexboxで横並び */
             align-items: center; /* 垂直方向中央揃え */
        }
        .top-header a {
            color: inherit; /* 親要素の色を継承 */
            text-decoration: none;
        }
         .top-header .x-application-logo {
             color: #d1d5db; /* ロゴの色 */
           }
        .top-header .menu-toggle-button {
            background: none;
            border: none;
            color: #d1d5db; /* ボタンアイコン色 */
            cursor: pointer;
            padding: 0.5rem; /* py-2 px-2 に相当 */
            display: flex;
            align-items: center;
        }
          .top-header .menu-toggle-button:hover {
             color: #fff; /* ホバー時のアイコン色 */
           }


        /* サイドバーのスタイル調整 */
        .sidebar {
            width: 12%; /* 例：サイドバーの幅 */
            min-width: 180px; /* 最小幅 */
            height: 100vh; /* 画面の高さいっぱいにする */
            padding: 0; /* サイドバー自体のpaddingは削除 */
            border-right: 1px solid #d1d5db; /* メインコンテンツとの区切り線 */
            display: flex; /* Flexboxに */
            flex-direction: column; /* 子要素を縦に並べる */
            /* overflow-y は各セクション（.menu, .main-content）に移動 */
            background-color: #fff; /* メニュー本体の背景色 */
            color: #4b5563; /* メニュー本体の文字色 */

            /* 小さい画面での初期状態 (非表示) */
            position: fixed;
            top: 0; /* トップヘッダーの下から開始する場合はトップヘッダーの高さ分を指定 (ここでは0にしてレスポンシブで調整) */
            left: -100%; /* 左に隠しておく */
            z-index: 50; /* トップヘッダーより上 */
            transition: left 0.3s ease-in-out; /* スライドイン/アウトのアニメーション */
            width: 250px; /* 小さい画面でのサイドバーの幅 */
            min-width: 250px; /* 小さい画面での最小幅 */
        }

         /* サイドバーが開いたときのスタイル (小さい画面用) */
         .sidebar.is-open {
             left: 0; /* 表示位置 */
           }

        /* サイドバー内のヘッダーエリア（タイトル/ロゴ、小さい画面用閉じるボタン） */
        .sidebar-header {
            background-color: #fff; /* 背景色を白に変更 */
            color: #4b5563; /* 文字色をメニュー本体に合わせる */
            padding: 0.5rem 0.75rem; /* 上下のpaddingを追加 */
            flex-shrink: 0; /* 要素が縮まないようにする */
            display: flex; /* Flexboxに */
            align-items: center; /* 垂直中央揃え */
            justify-content: space-between; /* タイトル/ロゴと閉じるボタンを両端に配置 */
        }
         /* ヘッダー内のリンクとロゴの色を親要素から継承するか、メニュー本体の文字色に合わせる */
         .sidebar-header a {
             color: inherit; /* 親要素 (sidebar-header) の色を継承 */
             text-decoration: none; /* 下線を削除 */
             display: flex;
             align-items: center;
           }
         .sidebar-header .x-application-logo {
             color: #4b5563; /* メニュー本体の文字色に合わせる */
           }
        /* ホバー時のスタイルはメニューリンクに合わせるか任意で設定 */
         .sidebar-header a:hover {
             color: #1e293b; /* ホバー時の色をメニューリンクに合わせる */
           }

        /* サイドバー内の閉じるボタンのスタイル (小さい画面用) */
        .close-sidebar-button {
             background: none;
             border: none;
             color: #4b5563; /* ボタンアイコン色をメニュー本体に合わせる */
             cursor: pointer;
             padding: 0.5rem;
             display: none; /* デフォルトは非表示 */
             align-items: center;
             margin-right: auto;
           }
         .close-sidebar-button:hover {
             color: #1e293b; /* ホバー時のアイコン色 */
           }


        /* サイドバー内のメニュー本体 */
        .sidebar .menu {
            flex-grow: 1; /* メニュー部分が残りのスペースを埋めるように伸縮 */
            list-style: none;
            padding: 0.75rem 0.75rem; /* paddingを追加 */
            margin: 0;
            background-color: #fff; /* メニュー本体の背景色 */
            color: #4b5563; /* メニュー本体の文字色 */
             overflow-y: auto; /* メニューが長い場合にメニュー部分だけスクロール */
        }
         .sidebar .menu li {
           margin-bottom: 0.5rem; /* メニュー項目の間隔 */
         }
         .sidebar .menu > li:last-child {
             margin-bottom: 0;
           }

        .sidebar .menu a {
            color: #4b5563; /* メニューリンクの色 */
            padding: 0.5rem;
            display: flex;
            align-items: center;
            border-radius: 0.25rem;
            text-decoration: none;
        }
        .sidebar .menu a:hover {
            background-color: #f3f4f6; /* ホバー背景色 */
            color: #1e293b;
        }
        /* サブメニュー開閉トリガーの矢印アイコンのスタイル調整 */
        .sidebar .menu li.has-submenu > a > svg.arrow {
            transition: transform 0.3s ease-in-out; /* 回転アニメーションを追加 */
        }

        /* openクラスが付与されたら矢印を回転 */
        .sidebar .menu li.has-submenu.open > a > svg.arrow {
            transform: rotate(180deg);
        }

        /* サブメニューのスタイル */
         .sidebar .submenu {
             padding-left: 1rem; /* サブメニューのインデント */
             list-style: none;
             padding-top: 0.5rem;
             padding-bottom: 0.5rem;
           }
         .sidebar .submenu li a {
             padding: 0.25rem 0.5rem; /* サブメニューリンクのpadding */
             padding-left: 1rem; /* アイコン分も含めたインデント調整 */
             font-size: 0.875rem; /* text-sm */
              color: #4b5563; /* サブメニューリンクの色 */
           }
          .sidebar .submenu li a:hover {
              background-color: #f3f4f6; /* サブメニューホバー背景色 */
              color: #1e293b; /* サブメニューホバー文字色 */
            }

        /* サブメニュー開閉トリガーのスタイル */
        .sidebar .menu li.has-submenu > a {
             display: flex; /* Flexboxを使用 */
             align-items: center; /* 垂直方向中央揃え */
             width: 100%; /* 親要素いっぱいに広げる */
             padding: 0.5rem; /* メニューリンクと同じpadding */
             border-radius: 0.25rem; /* 角丸 */
             text-align: left; /* テキスト左揃え */
             font-size: 1rem; /* text-base (他のメニューリンクに合わせる) */
             font-weight: 400; /* font-normal (他のメニューリンクに合わせる) */
             color: #4b5563; /* デフォルトの文字色 */
             background-color: transparent; /* 背景色なし */
             border: none; /* ボーダーなし */
             transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out; /* ホバー時のトランジション */
             justify-content: flex-start; /* 左寄せ（テキストと矢印アイコンの間をスペースで埋める） */
           }
         .sidebar .menu li.has-submenu > a:hover {
             background-color: #f3f4f6; /* ホバー背景色 */
             color: #1e293b; /* ホバー文字色 */
           }
         /* ユーザー情報トリガーの矢印アイコンスタイル */
         .sidebar .menu li.has-submenu > a svg {
             color: #b4bcc7; /* アイコンのデフォルト色 */
             transition: color 0.75s ease; /* ホバー時の色変化を滑らかに */
           }
         .sidebar .menu li.has-submenu > a:hover svg {
             color: #1e293b; /* ホバー時のアイコン色 */
           }
         .sidebar .menu li.has-submenu > a span:first-child {
            flex-grow: 1; /* テキストがスペースを埋めるように */
           }

        /* ユーザー情報サブメニュー内のリンクのスタイル調整 */
         .sidebar .menu li.has-submenu .submenu li a {
             padding: 0.25rem 0.5rem;
             padding-left: 1rem; /* アイコン分も含めたインデント調整 */
             font-size: 0.875rem; /* text-sm */
             color: #4b5563;
             border-radius: 0.25rem;
             text-decoration: none;
             display: flex;
             align-items: center;
           }
         .sidebar .submenu li a:hover {
             background-color: #f3f4f6;
             color: #1e293b;
           }

        /* サイドバー下部エリアのスタイル */
        .sidebar-footer {
            flex-shrink: 0; /* 縮まないようにする */
            padding: 0.75rem; /* 上下左右にパディング */
            margin-top: auto; /* Flexアイテムとして利用可能なスペースを全て上に押しやり、自身は下部に配置される */
            border-top: 1px solid #d1d5db; /* 必要に応じて区切り線 */
            background-color: #fff; /* サイドバー本体と同じ背景色 */
        }
         .sidebar-footer ul {
             list-style: none;
             padding: 0;
             margin: 0;
         }
        /* サイドバー下部内のカスタムドロップダウンのスタイル */
        .sidebar-footer .custom-dropdown {
            position: relative; /* ドロップダウンメニューをここに絶対配置するため */
            width: 100%; /* ボタンとメニューが親要素の幅いっぱいに広がるように */
            display: inline-flex; /* ボタンのFlexboxスタイルに合わせる */
        }

        /* サイドバー下部内のカスタムドロップダウンメニューのスタイル */
        .sidebar-footer .custom-dropdown-menu {
            position: absolute;
            bottom: 100%; /* ボタンの上に配置する場合。下に配置ならtop: 100%; */
            left: 0;
            /* Tailwindのhiddenクラス (display: none;) と opacity-0 で初期状態を制御 */
            transition: opacity 0.2s ease-in-out; /* Opacityの変化をアニメーションさせる */
            z-index: 20; /* 他の要素より手前に表示 */
            min-width: 100%; /* ボタンと同じ幅 */
            background-color: #fff; /* 背景色 */
            border: 1px solid #d1d5db; /* 枠線 */
            border-radius: 0.25rem; /* 角丸 */
            box-shadow: 0 2px 8px rgba(0,0,0,0.15); /* 影 */
            /* paddingはHTML内のp-1クラスで設定 */
        }
         /* 表示時のスタイル */
         .sidebar-footer .custom-dropdown-menu.show {
             display: block; /* display: none; を上書き */
             opacity: 1; /* 透明度を戻して表示 */
         }

        /* サイドバーフッターのカスタムドロップダウンメニュー内のリンクの
           ホバー/フォーカス時の文字色をデフォルト色（text-gray-800 / #1f2937）に固定 */
        .sidebar-footer .custom-dropdown-menu a:hover,
        .sidebar-footer .custom-dropdown-menu a:focus {
            color: #1f2937; /* Tailwindのgray-800の色 */
        }

        /* ダークモード時のホバー/フォーカス時の文字色をデフォルト色（dark:text-neutral-300 / #d4d4d4）に固定 */
        .dark .sidebar-footer .custom-dropdown-menu a:hover,
        .dark .sidebar-footer .custom-dropdown-menu a:focus {
            color: #d4d4d4; /* Tailwindのneutral-300の色 */
        }


        /* メインコンテンツエリアを包括するFlexコンテナ */
        .content-area {
            display: flex; /* サイドバーとメインコンテンツを横に並べる (大きい画面用) */
            flex-grow: 1;
            min-height: 100vh; /* 少なくとも画面高さいっぱいワの高さを確保 */
            background-color: #fff; /* content-area自体の背景色も白に */
            /* 大きい画面ではサイドバーの幅を考慮 */
             /* flex-direction: column; レスポンシブで上部に積む場合はメディアクエリでoverride */
        }


        /* メインコンテンツエリア */
        .main-content {
            flex-grow: 1; /* 残りのスペースを埋める */
            padding: 0rem 1rem; /* トップpaddingを調整 (トップヘッダーがない場合) */
            /* background-color: #f8f8f8; メインコンテンツの背景色 */
            /* overflow-y はメインコンテンツ自体ではなく、この中の要素や content-area に設定することが多いですが、
               画面高さいっぱいのレイアウトの場合はメインコンテンツ自体にスクロール設定が必要です */
            min-height: 100vh; /* 画面高さいっぱいにする */
            background-image: url('/images/bg_base.jpg'); /* 背景画像 */
             background-size: auto; /* 画像の表示方法 */
             background-position: center; /* 画像の位置 */
             background-attachment: fixed; /* 背景を固定（スクロールしても動かない） */
        }
        /* メインコンテンツ内のコンテンツのパディング調整 */
         .main-content > *:not(.flex-col):not(.justify-center):not(.items-center) {
             padding-top: 1rem; /* 必要に応じてメインコンテンツの先頭にパディング */
         }
         .main-content .flex-col.justify-center.items-center {
             padding-top: 0; /* 特定のレイアウト要素にはパディングを適用しない */
         }


        /* -------- メディアクエリ (画面幅 <= 1140px) -------- */
        @media (max-width: 1140px) {
            .top-header {
                display: flex; /* トップヘッダーを表示 */
                 height: 56px; /* トップヘッダーの高さ */
            }

            .sidebar {
                /* 小さい画面での表示位置と幅は上に定義済み */
                 /* top: 56px; トップヘッダーの下から開始 */
                 height: 100vh;
            }

            .sidebar-header {
                display: flex; /* 小さい画面でもサイドバーヘッダーは表示 (モバイルメニューとして開いたとき) */
                justify-content: flex-end;
            }

            .sidebar-header a {
                display: none;
            }

            /* サイドバーが開いているときに閉じるボタンを表示 */
             .sidebar.is-open .close-sidebar-button {
                 display: flex;
               }

            /* 画面が狭く、サイドバーが開いているときに、サイドバーヘッダー内のロゴとタイトルを非表示にする */
            .sidebar.is-open .sidebar-header a {
                display: none;
            }


            .content-area {
                display: block; /* Flexboxを解除し、要素を縦に積む */
                padding-left: 0; /* サイドバー非表示のため左paddingを0に */
                margin-top: 56px; /* トップヘッダーの高さ分下にずらす */
                 height: calc(100vh - 56px); /* トップヘッダーの高さを引いた高さ */
                 overflow-y: auto; /* メインコンテンツエリアがスクロール可能になるように */
            }
            .main-content {
                 min-height: 100vh; /* 親要素に合わせる（画面高さいっぱいにする必要なし） */
                 padding-top: 0rem; /* メインコンテンツ自体のトップパディングをリセット */
                 /* content-areaのpadding-topで調整 */
            }
             .main-content > *:not(.flex-col):not(.justify-center):not(.items-center) {
                 padding-top: 1rem; /* 必要に応じてメインコンテンツの先頭にパディング */
             }
        }

        /* -------- メディアクエリ (画面幅 > 1140px) -------- */
        @media (min-width: 1141px) {
            .top-header {
                display: none; /* トップヘッダーを非表示 */
            }

            .sidebar {
                position: static; /* 固定解除 */
                left: auto; /* 位置指定解除 */
                width: 12%; /* 大きい画面での幅に戻す */
                min-width: 180px;
                 height: 100vh; /* 画面高さいっぱい */
                 top: 0; /* 位置指定解除 */
            }

            .sidebar-header {
                 display: flex; /* 大きい画面でサイドバーヘッダーを表示 */
            }

            /* 大きい画面ではサイドバー内の閉じるボタンは非表示 */
            .close-sidebar-button {
                 display: none;
               }

            .content-area {
                display: flex; /* Flexboxでサイドバーとメインコンテンツを横並び */
                 margin-top: 0; /* トップヘッダーがないためmargin-topを0に */
                 height: 100vh; /* 画面高さいっぱい */
                 overflow: hidden; /* 子要素（sidebar, main-content）でスクロールさせるため自身は非表示 */
            }
             .main-content {
                  height: 100vh; /* Flexアイテムとして画面高さいっぱい */
                  padding-top: 0.25rem; /* トップパディング */
                  overflow-y: auto; /* メインコンテンツ部分だけスクロール */
             }
             .main-content > *:not(.flex-col):not(.justify-center):not(.items-center) {
                 padding-top: 8px; /* content-areaのpadding-topで調整するためここは0 */
             }
              /* メインコンテンツ内のコンテンツの左パディングは、content-areaのflexboxとサイドバーの幅によって自動的にできる */
        }

        /* 必要に応じて、sidebarが開いているときにメインコンテンツにオーバーレイをかけるスタイルを追加 */
        /* .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }
         .overlay.is-visible {
             display: block;
          } */


         /* チェックボックスの改行が効かない問題へのCSS修正例 */
         /* .checkbox-container に display: flex; などが設定されている場合、以下のCSSで折り返しを許可 */
          .checkbox-container {
              /* 例: display: flex; になっている場合を想定 */
              flex-wrap: wrap; /* これを追加すると折り返されます */
              gap: 10px; /* チェックボックス間の隙間（任意） */
              /* justify-content: flex-start; /* 左寄せを明示 */
          }
          /* チェックボックスとラベルをセットでFlexアイテムにする場合 */
          /* .checkbox-container > div { // inputとラベルをdivで囲むなどの構造にした場合
              display: flex;
              align-items: center;
          } */


    </style>
</head>
<body class="font-sans antialiased">

    {{-- -------- 新しいトップヘッダー (小さい画面用) -------- --}}
    <header class="top-header">
        {{-- モバイルメニュー開閉ボタンを一番左に移動 --}}
        <button class="menu-toggle-button" aria-label="Toggle menu">
             {{-- Font Awesomeアイコンに変更 --}}
            <i id="menu-icon" class="fa-solid fa-bars w-6 h-6"></i> {{-- Burger menu icon --}}
        </button>

        {{-- ロゴとタイトルをその右に配置 --}}
        <div class="header-title-area"> {{-- 新しいdivで囲み、Flexboxでロゴとタイトルを配置 --}}
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-d1d5db" />
            </a>
            <span class="ml-2 text-lg font-semibold">檀家管理</span>
        </div>
         {{-- トップヘッダーの右側に何かボタンなどを置く場合はここに追加 --}}
    </header>

    {{-- メインコンテンツエリアを含むコンテナ --}}
    {{-- content-areaをトップレベルのFlexコンテナとして利用 (大きい画面用) --}}
    <div class="content-area">

        {{-- サイドバー --}}
        {{-- 小さい画面では position: fixed で表示/非表示を制御 --}}
        <aside class="sidebar">
            {{-- タイトル/ロゴと閉じるボタンをサイドバーの最上部に追加 --}}
            <div class="sidebar-header">
                {{-- モバイル表示時はJSで非表示になる --}}
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current" style="color: #4b5563;" />
                    <span class="ml-2 text-lg font-semibold" style="color: #4b5563;">檀家管理</span>
                </a>
                {{-- 閉じるボタンをここに追加 (モバイル表示時に表示) --}}
                <button class="close-sidebar-button" aria-label="Close menu">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                     </svg>
                </button>
            </div>

            {{-- サイドバーメニュー本体 (flex-grow: 1 でスペースを埋める) --}}
            <ul class="menu">
                {{-- ダッシュボードリンク --}}
                <li class="active">
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100 group">
                        {{-- <svg class="w-5 h-5 text-gray-900 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21"><path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-.069ZM10 18.975a8.454 8.454 0 0 1-5.395-2.32a1 1 0 0 0-.14-.16c-.928-1.676-1.449-3.591-1.449-5.49C3.41 6.6 6.6 3.41 10 3.41v15.565Z"/></svg> --}}
                        <span class="ml-3 text-gray-900">ダッシュボード</span>
                    </a>
                </li>
                {{-- マスタサブメニュー --}}
                <li class="has-submenu">
                    <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100 group">
                        {{-- <i class="fas fa-cogs mr-3"></i> --}} {{-- マスタ用アイコン例 --}}
                        <span class="flex-1 ml-3 whitespace-nowrap text-gray-900">マスタ</span>
                        <svg class="w-3 h-3 ml-3 arrow text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </a>
                    <ul class="submenu py-2 space-y-2">
                        <li>
                            <a href="{{ route('generalmaster.index') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                汎用マスタ
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('templemaster.createOrEdit') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                自寺院マスタ
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kaiki.index') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                回忌設定
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- 檀信徒名簿サブメニュー --}}
                <li class="has-submenu">
                    <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-100 group">
                        {{-- <i class="fas fa-address-book mr-3"></i> --}} {{-- 名簿用アイコン例 --}}
                        <span class="flex-1 ml-3 whitespace-nowrap text-gray-900">檀信徒名簿</span>
                        <svg class="w-3 h-3 ml-3 arrow text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </a>
                    <ul class="submenu py-2 space-y-2">
                        <li>
                            <a href="{{ route('danka.index') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                檀家一覧
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dankalist.index') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                一覧表印刷
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('postcard.index') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                はがき・封筒
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kaimyoucheck.index') }}" class="flex items-center p-2 pl-11 w-full text-sm rounded-lg hover:bg-gray-100 group">
                                同名戒名検索
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            {{-- サイドバー下部エリア（カスタムドロップダウン） --}}
            <footer class="sidebar-footer"> {{-- footerタグを使用 --}}
                <div class="custom-dropdown relative w-full inline-flex"> {{-- カスタムドロップダウンのラッパー --}}
                     {{-- ドロップダウントリガーボタン --}}
                    <button id="user-menu-trigger" type="button" class="w-full inline-flex shrink-0 items-center gap-x-2 p-2 text-start text-sm text-gray-800 rounded-md hover:bg-gray-100 focus:outline-none focus:bg-gray-100" aria-haspopup="menu" aria-expanded="false" aria-label="User menu">
                        {{ Auth::user()->name }}
                        {{-- ドロップダウン矢印アイコン --}}
                        <svg class="shrink-0 size-3.5 ms-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
                    </button>

                    {{-- ドロップダウンメニュー本体 --}}
                    {{-- 初期状態はhiddenとopacity-0で非表示 --}}
                    <div id="user-menu" class="custom-dropdown-menu absolute w-60 opacity-0 hidden z-20 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-neutral-900 dark:border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-trigger">
                        <div class="p-1">
                            {{-- ユーザー情報リンク --}}
                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-100" href="{{ route('profile.edit') }}">
                                ユーザー情報
                            </a>
                            {{-- ログアウトフォームとリンク --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-100" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    ログアウト
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </footer>

        </aside>

        {{-- メインコンテンツ --}}
        {{-- この中の {{ $slot }} に、各ページのコンテンツが入ります --}}
        <main class="main-content">

            {{ $slot }}

        </main>
    </div> {{-- content-area end --}}

    {{-- 必要に応じて、メニューが開いているときに背景を暗くするオーバーレイ要素 --}}
    {{-- <div class="overlay" id="sidebar-overlay"></div> --}}

    {{-- スクリプト --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // サブメニュー開閉のJavaScript (サイドバー内の通常メニュー用)
            const hasSubmenuTriggers = document.querySelectorAll('.sidebar .menu li.has-submenu > a'); // .sidebar .menu に限定

            hasSubmenuTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parentLi = this.closest('li.has-submenu');
                    if (parentLi) {
                        parentLi.classList.toggle('open');

                        // ★他のサブメニューを閉じる処理を削除またはコメントアウト★
                        // parentLi.parentNode.querySelectorAll('li.has-submenu').forEach(otherLi => {
                        //      if (otherLi !== parentLi && otherLi.classList.contains('open')) {
                        //          otherLi.classList.remove('open');
                        //      }
                        // });
                    }
                });
            });

            // -------- レスポンシブ対応 JavaScript --------

            const sidebar = document.querySelector('.sidebar');
            const menuToggleButton = document.querySelector('.menu-toggle-button');
            const closeSidebarButton = document.querySelector('.close-sidebar-button');
             // Font Awesomeアイコン要素を取得
            const menuIcon = menuToggleButton ? menuToggleButton.querySelector('i') : null; // ボタンの子要素からアイコンを取得
            // const overlay = document.getElementById('sidebar-overlay'); // オーバーレイを使用する場合

            // モバイルメニュー開閉ボタンのクリックイベント
            if (menuToggleButton && sidebar && menuIcon) { // menuIconの存在チェックを追加
                menuToggleButton.addEventListener('click', function() {
                    const isOpening = !sidebar.classList.contains('is-open'); // トグル前の状態を確認
                    sidebar.classList.toggle('is-open');

                    // アイコンクラスを切り替え
                    if (isOpening) { // サイドバーが開く場合
                        menuIcon.classList.remove('fa-bars');
                        menuIcon.classList.add('fa-xmark');
                    } else { // サイドバーが閉じる場合
                        menuIcon.classList.remove('fa-xmark');
                        menuIcon.classList.add('fa-bars');
                    }
                     // ... overlay logic if used ...
                });
            }

            // 閉じるボタンのクリックイベント (sidebar internal close button)
            if (closeSidebarButton && sidebar && menuIcon) { // menuIconの存在チェックを追加
                closeSidebarButton.addEventListener('click', function() {
                    sidebar.classList.remove('is-open'); // サイドバーを閉じる
                    // アイコンを常に三本線に戻す
                    menuIcon.classList.remove('fa-xmark');
                    menuIcon.classList.add('fa-bars');
                    // ... overlay logic if used ...
                });
            }

            // ※ オプション: サイドバーの外側をクリックしたら閉じる処理
             // document.addEventListener('click', function(e) {
             //     // クリックされた要素がサイドバー自体、またはサイドバー内の要素でない場合
             //     if (!sidebar.contains(e.target) && !menuToggleButton.contains(e.target) && sidebar.classList.contains('is-open')) {
             //          sidebar.classList.remove('is-open');
             //          // if (overlay) { overlay.classList.remove('is-visible'); }
             //     }
             // });

            // -------- カスタムドロップダウン JavaScript (サイドバーフッター用) --------

            const userMenuTrigger = document.getElementById('user-menu-trigger');
            const userMenu = document.getElementById('user-menu');

            if (userMenuTrigger && userMenu) {
                const closeUserMenu = () => {
                    userMenu.classList.remove('show');
                    userMenu.classList.add('hidden'); // メニュー非表示時はdisplay: noneに戻す
                    userMenuTrigger.setAttribute('aria-expanded', 'false');
                    document.removeEventListener('click', handleUserMenuOutsideClick); // ドキュメントリスナーを解除
                };

                const openUserMenu = () => {
                    // 他のドロップダウンがもし開いていれば閉じる処理をここに追加（任意）

                    userMenu.classList.remove('hidden'); // display: noneを解除
                    // すぐにopacityを1にするとトランジションしない可能性があるため、少し遅延させるか、
                    // CSSのtransition設定とclassの追加順序に依存します。
                    // ここではシンプルにhidden解除とshow追加を同時に行います。
                    // トランジションが効かない場合は、hiddenを外す -> （短いタイマー） -> opacity:1 のように調整が必要かもしれません。
                    userMenu.classList.add('show');
                    userMenuTrigger.setAttribute('aria-expanded', 'true');

                     // ドキュメントのどこかをクリックしたときにドロップダウンを閉じるリスナーを追加
                     // すでにリスナーが追加されている可能性もあるため、remove -> add とするのが安全ですが、
                     // closeUserMenu() で必ず remove していれば重複しません。
                    document.addEventListener('click', handleUserMenuOutsideClick);
                };

                const handleUserMenuOutsideClick = (event) => {
                    // クリックされた要素がトリガー自身、またはメニュー内部に含まれているか確認
                    const isClickInside = userMenu.contains(event.target) || userMenuTrigger.contains(event.target);

                    if (!isClickInside) {
                        // 外部をクリックした場合のみメニューを閉じる
                        closeUserMenu();
                    }
                };

                // トリガーボタンのクリックイベント
                userMenuTrigger.addEventListener('click', function(event) {
                    event.stopPropagation(); // クリックイベントがドキュメントまで伝播するのを防ぎ、即座に閉じないようにする

                    // メニューの現在の表示状態を確認して切り替え
                    if (userMenu.classList.contains('show')) {
                        closeUserMenu(); // 開いていれば閉じる
                    } else {
                        openUserMenu(); // 閉じていれば開く
                    }
                });

                 // ドロップダウンメニュー内部でのクリックイベント伝播を停止
                 // これがないと、メニュー内のリンクなどをクリックしたときに、そのイベントがdocumentまで伝播してメニューが閉じてしまう
                 userMenu.addEventListener('click', function(event) {
                      event.stopPropagation();
                 });

                 // (任意) メニュー内のリンクをクリックしたときにメニューを閉じる処理
                 userMenu.querySelectorAll('a').forEach(link => {
                      link.addEventListener('click', () => {
                          // リンク遷移の前に少し遅延を入れて閉じる（見た目を滑らかにするため）
                          // ログアウトフォームのsubmitなども含む
                          setTimeout(closeUserMenu, 50);
                      });
                 });
            }


        }); // DOMContentLoaded end

    </script>
    {{-- 檀家詳細ページの固有スクリプト（削除確認など） --}}
    {{-- DOMContentLoadedリスナーの外に定義します --}}
    <script>
        function deleteFamily(){
            if(confirm('削除します。よろしいですか？')){
                return true;
            } else {
                return false;
            }
        }
         function deleteKakocho(){
            if(confirm('削除します。よろしいですか？')){
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>
</html>