@props(['match', 'slot', 'side'])

<div class="bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col overflow-hidden text-[11px] transition-all hover:border-indigo-300">
    <div class="bg-slate-50/50 px-2 py-1 text-[9px] text-slate-400 font-bold border-b border-slate-100 flex justify-between">
        <span>{{ $match ? \Carbon\Carbon::parse($match->kickoff_at)->format('d/m H:i') : '' }}</span>
    </div>

    <div @class([
        'flex items-center gap-2 px-2 py-1.5 border-b border-slate-50',
        'flex-row-reverse text-right' => $side === 'right'
    ])>
        <div class="shrink-0">
            @if($match && $match->homeTeam)
                <img src="{{ asset($match->homeTeam->flag_path) }}" class="w-5 h-3.5 object-cover rounded-sm">
            @else
                <div class="w-5 h-3.5 bg-slate-100 rounded-sm"></div>
            @endif
        </div>
        <span @class([
            'truncate font-bold w-full',
            'text-indigo-900' => $match && $match->winner_team_id === $match->home_team_id,
            'text-slate-400' => !$match || ($match->winner_team_id && $match->winner_team_id !== $match->home_team_id)
        ])>
            {{ $match->homeTeam->nameFr ?? 'À définir' }}
        </span>
        <span class="font-black">{{ $match->home_score ?? '-' }}</span>
    </div>

    <div @class([
        'flex items-center gap-2 px-2 py-1.5',
        'flex-row-reverse text-right' => $side === 'right'
    ])>
        <div class="shrink-0">
            @if($match && $match->awayTeam)
                <img src="{{ asset($match->awayTeam->flag_path) }}" class="w-5 h-3.5 object-cover rounded-sm">
            @else
                <div class="w-5 h-3.5 bg-slate-100 rounded-sm"></div>
            @endif
        </div>
        <span @class([
            'truncate font-bold w-full',
            'text-indigo-900' => $match && $match->winner_team_id === $match->away_team_id,
            'text-slate-400' => !$match || ($match->winner_team_id && $match->winner_team_id !== $match->away_team_id)
        ])>
            {{ $match->awayTeam->nameFr ?? 'À définir' }}
        </span>
        <span class="font-black">{{ $match->away_score ?? '-' }}</span>
    </div>
</div>