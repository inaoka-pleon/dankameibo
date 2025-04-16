<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">はがき</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <div class="w-full max-w-7xl grid grid-cols-12 gap-x-3 gap-y-3">
                <div class="col-span-12">
                    <div class="article-head tracking-widest font-medium px-3 py-2"  style="border-left: 6px solid #718096;">
                        その他
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('atenaheader.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">宛名印刷</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('generalpostcard.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">はがき裏面印刷</a>
                </div>
                <div class="col-span-12 sm:col-span-2"></div>
                <div class="col-span-12 sm:col-span-2"></div>
                <div class="col-span-12 sm:col-span-2"></div>
                <div class="col-span-12 sm:col-span-2"></div>
            </div>
        </div>
    </main>
</x-app-layout>