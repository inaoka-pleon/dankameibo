<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // ボタンのクリックイベントを設定
                document.querySelector("button.reset").addEventListener("click", function () {
                    // input要素の値をリセット
                    document.querySelectorAll('input[type="text"], input[type="search"], input[type="radio"], input[type="date"], select').forEach(function (input) {
                        input.value = "";
                        input.checked = false;
                        input.selected = false;
                        input.dispatchEvent(new Event("change"));
                    });

                    // チェックボックスとセレクトボックスの状態をリセット
                    document.querySelectorAll('input[type="checkbox"], select').forEach(function (input) {
                        input.checked = false;
                        input.dispatchEvent(new Event("change"));
                    });
                });

                // .proc-btn要素のクリックイベントを設定
                document.querySelector(".proc-btn").addEventListener("click", function () {
                    // #overlay要素を表示
                    document.querySelector("#overlay").style.display = "block";
                });

                // #file_url要素のchangeイベントを設定
                document.querySelector("#file_url").addEventListener("change", function () {
                    // 選択されたファイルの名前を取得して#file_name要素に設定
                    let file = this.files[0];
                    document.querySelector("#file_name").value = file.name;
                });
            });            
        </script>
    </body>
</html>
