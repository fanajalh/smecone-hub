<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smecone Hub @yield('title')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-['Plus_Jakarta_Sans',_sans-serif] antialiased pb-20 md:pb-0">

    @auth
    <nav class="hidden md:flex bg-white shadow-sm border-b border-gray-100 py-4 px-8 justify-between items-center sticky top-0 z-50">
        
        <div class="flex items-center gap-8">
            <a href="/dashboard" class="text-2xl font-extrabold text-red-600 tracking-tight">Smecone Hub</a>
            
            <div class="flex gap-6 font-semibold text-sm text-gray-500">
                <a href="/dashboard" class="hover:text-red-600 transition {{ request()->is('dashboard') ? 'text-red-600' : '' }}">Dashboard</a>
                <a href="/lost-found" class="hover:text-red-600 transition {{ request()->is('lost-found*') ? 'text-red-600' : '' }}">Lost & Found</a>
                <a href="/marketplace" class="hover:text-red-600 transition {{ request()->is('marketplace*') ? 'text-red-600' : '' }}">Marketplace</a>
                <a href="/repository" class="hover:text-red-600 transition {{ request()->is('repository*') ? 'text-red-600' : '' }}">Repository</a>
                <a href="/forum" class="hover:text-red-600 transition {{ request()->is('forum*') ? 'text-red-600' : '' }}">Forum</a>
            </div>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 bg-red-50 px-3 py-1.5 rounded-full border border-red-100">
                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span class="text-sm font-bold text-red-700">{{ auth()->user()->reputation_points ?? 0 }} Poin</span>
            </div>
            
            <a href="/dashboard" class="flex items-center gap-2 hover:opacity-80 transition">
                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold shadow-sm border border-red-200">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="text-sm font-bold text-gray-700">{{ auth()->user()->name }}</span>
            </a>

            <form action="/logout" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-600 transition">Logout</button>
            </form>
        </div>
    </nav>
    @endauth

    <main class="w-full">
        @yield('content')
    </main>

    @auth
    <div class="md:hidden fixed bottom-0 w-full max-w-md left-1/2 transform -translate-x-1/2 bg-white border-t border-gray-100 flex justify-around items-center py-3 px-2 shadow-[0_-4px_20px_rgba(0,0,0,0.04)] z-50 rounded-t-3xl">
        
        <a href="/dashboard" class="flex flex-col items-center flex-1 {{ request()->is('dashboard') ? 'text-red-600' : 'text-gray-400 hover:text-red-500' }} transition">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-[10px] font-bold">Profil</span>
        </a>

        <a href="/lost-found" class="flex flex-col items-center flex-1 {{ request()->is('lost-found*') ? 'text-red-600' : 'text-gray-400 hover:text-red-500' }} transition">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <span class="text-[10px] font-bold">Barang</span>
        </a>

        <a href="/marketplace" class="flex flex-col items-center flex-1 {{ request()->is('marketplace*') ? 'text-red-600' : 'text-gray-400 hover:text-red-500' }} transition">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="text-[10px] font-bold">Jajan</span>
        </a>

        <a href="/forum" class="flex flex-col items-center flex-1 {{ request()->is('forum*') ? 'text-red-600' : 'text-gray-400 hover:text-red-500' }} transition">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            <span class="text-[10px] font-bold">Forum</span>
        </a>

    </div>
    @endauth

</body>
</html>