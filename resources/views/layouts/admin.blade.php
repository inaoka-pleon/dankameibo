<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet' type='text/css'>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('css/spinner.css') }}" rel="stylesheet">
        <link href="{{ mix('css/file_upload.css') }}" rel="stylesheet">
        <link href="{{ mix('css/common.css') }}" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ mix('js/common.js') }}"></script>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

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
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{-- フラッシュメッセージの表示 --}}
                    @if (Session::has('flash_message'))
                        <div class="bg-green-50 border border-green-200 text-sm text-green-600 rounded-md p-4">{{ Session::get('flash_message') }}</div>
                    @endif
                    {{-- フラッシュエラーメッセージの表示 --}}
                    @if (Session::has('flash_error_message'))
                        <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4" role="alert">
                            {{ Session::get('flash_error_message') }}
                        </div>
                    @endif
                    {{-- フラッシュ警告メッセージの表示 --}}
                    @if (Session::has('flash_warning_message'))
                        <div class="bg-orange-50 border border-orange-200 text-sm text-orange-600 rounded-md p-4">{{ Session::get('flash_warning_message') }}</div>
                    @endif
                    {{-- フラッシュ情報メッセージの表示 --}}
                    @if (Session::has('flash_info_message'))
                        <div class="bg-blue-50 border border-blue-200 text-sm text-blue-600 rounded-md p-4">{{ Session::get('flash_info_message') }}</div>
                    @endif
                    @include('common.toastr')
                </div>
                {{ $slot }}
            </main>
        </div>
        <!-- Body Script -->
        @if (isset($body_script))
            {{ $body_script }}
        @endif
    </body>
</html>
