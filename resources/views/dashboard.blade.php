<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl px-2">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @livewire('dashboard.my-stats')
            @livewire('dashboard.next-matches')
            @livewire('dashboard.best-players')
            @livewire('dashboard.last-results')
            @livewire('dashboard.my-pony')
            @livewire('dashboard.boost-radar')
            @livewire('dashboard.score-progression')
            @livewire('dashboard.best-prono')
        </div>
    </div>
</x-layouts::app>
