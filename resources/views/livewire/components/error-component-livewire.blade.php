<div class="flex min-h-[calc(100dvh)] w-full justify-center items-center">
    <div class="flex-1 text-center text-red-400">
        <div class="text-3xl font-semibold">{{ $errorTitle ?? 'Error 404' }}</div>
        <div class="italic">{{ $message ?? 'Page not found' }}</div>
    </div>

</div>
