@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6 px-4">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Journaux d'actions</h1>
        <span class="text-sm text-gray-500">{{ $logs->total() }} entrée(s) trouvée(s)</span>
    </div>

    {{-- ───── Formulaire de filtres ───── --}}
    <form method="GET" action="{{ route('logs.index') }}"
          class="bg-white border rounded-lg p-4 flex flex-wrap gap-4 items-end shadow-sm">

        {{-- Filtre par date --}}
        <div class="flex flex-col gap-1">
            <label for="date" class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                Date
            </label>
            <input type="date"
                   id="date"
                   name="date"
                   value="{{ request('date') }}"
                   class="border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        {{-- Filtre par type d'action --}}
        <div class="flex flex-col gap-1">
            <label for="action" class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                Type d'action
            </label>
            <select id="action"
                    name="action"
                    class="border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">— Toutes les actions —</option>
                <optgroup label="Connexion">
                    <option value="login_success" @selected(request('action') === 'login_success')>Connexion réussie</option>
                    <option value="login_failed"  @selected(request('action') === 'login_failed')>Échec de connexion</option>
                </optgroup>
                <optgroup label="Idées">
                    <option value="idea_created" @selected(request('action') === 'idea_created')>Idée créée</option>
                    <option value="idea_updated" @selected(request('action') === 'idea_updated')>Idée modifiée</option>
                    <option value="idea_deleted" @selected(request('action') === 'idea_deleted')>Idée supprimée</option>
                </optgroup>
                <optgroup label="Commentaires">
                    <option value="comment_created" @selected(request('action') === 'comment_created')>Commentaire créé</option>
                    <option value="comment_updated" @selected(request('action') === 'comment_updated')>Commentaire modifié</option>
                    <option value="comment_deleted" @selected(request('action') === 'comment_deleted')>Commentaire supprimé</option>
                </optgroup>
            </select>
        </div>

        {{-- Boutons --}}
        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                Filtrer
            </button>
            <a href="{{ route('logs.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                Réinitialiser
            </a>
        </div>

    </form>

    {{-- ───── Tableau des logs ───── --}}
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="w-full text-sm border-collapse bg-white">
            <thead>
                <tr class="bg-gray-800 text-white text-left">
                    <th class="px-4 py-3 whitespace-nowrap">Date</th>
                    <th class="px-4 py-3 whitespace-nowrap">Utilisateur</th>
                    <th class="px-4 py-3 whitespace-nowrap">Action</th>
                    <th class="px-4 py-3 whitespace-nowrap">Idée</th>
                    <th class="px-4 py-3 whitespace-nowrap">Commentaire</th>
                    <th class="px-4 py-3 whitespace-nowrap">IP</th>
                    <th class="px-4 py-3 whitespace-nowrap">Navigateur</th>
                    <th class="px-4 py-3 whitespace-nowrap">Avant</th>
                    <th class="px-4 py-3 whitespace-nowrap">Après</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)

                    @php
                        // Libellé et couleur selon le type d'action
                        [$label, $badgeClass] = match($log->action) {
                            'login_success'    => ['Connexion réussie',     'bg-green-100 text-green-800'],
                            'login_failed'     => ['Échec de connexion',    'bg-red-100 text-red-800'],
                            'idea_created'     => ['Idée créée',            'bg-blue-100 text-blue-800'],
                            'idea_updated'     => ['Idée modifiée',         'bg-yellow-100 text-yellow-800'],
                            'idea_deleted'     => ['Idée supprimée',        'bg-red-100 text-red-800'],
                            'comment_created'  => ['Commentaire créé',      'bg-purple-100 text-purple-800'],
                            'comment_updated'  => ['Commentaire modifié',   'bg-yellow-100 text-yellow-800'],
                            'comment_deleted'  => ['Commentaire supprimé',  'bg-red-100 text-red-800'],
                            default            => [$log->action,            'bg-gray-100 text-gray-700'],
                        };
                    @endphp

                    <tr class="border-b hover:bg-gray-50">

                        {{-- Date --}}
                        <td class="px-4 py-2 font-mono text-xs text-gray-600 whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>

                        {{-- Utilisateur --}}
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if($log->user)
                                <span class="font-medium">{{ $log->user->name }}</span>
                                <br>
                                <span class="text-xs text-gray-500">{{ $log->user->email }}</span>
                            @else
                                <span class="text-gray-400 italic text-xs">Non authentifié</span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $badgeClass }}">
                                {{ $label }}
                            </span>
                        </td>

                        {{-- Idée --}}
                        <td class="px-4 py-2 text-center text-gray-500 text-xs">
                            {{ $log->idea_id ?? '—' }}
                        </td>

                        {{-- Commentaire --}}
                        <td class="px-4 py-2 text-center text-gray-500 text-xs">
                            {{ $log->comment_id ?? '—' }}
                        </td>

                        {{-- IP --}}
                        <td class="px-4 py-2 font-mono text-xs text-gray-600 whitespace-nowrap">
                            {{ $log->ip_address ?? '—' }}
                        </td>

                        {{-- Navigateur --}}
                        <td class="px-4 py-2 text-xs text-gray-600 whitespace-nowrap">
                            {{ $log->user_agent ?? '—' }}
                        </td>

                        {{-- Données avant --}}
                        <td class="px-4 py-2 text-xs text-gray-500 max-w-xs">
                            @if($log->data_before)
                                <details>
                                    <summary class="cursor-pointer text-blue-600 hover:underline select-none">
                                        Voir
                                    </summary>
                                    <pre class="mt-1 p-2 bg-gray-50 border rounded text-xs whitespace-pre-wrap break-all">{{ $log->data_before }}</pre>
                                </details>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Données après --}}
                        <td class="px-4 py-2 text-xs text-gray-500 max-w-xs">
                            @if($log->data_after)
                                <details>
                                    <summary class="cursor-pointer text-blue-600 hover:underline select-none">
                                        Voir
                                    </summary>
                                    <pre class="mt-1 p-2 bg-gray-50 border rounded text-xs whitespace-pre-wrap break-all">{{ $log->data_after }}</pre>
                                </details>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-gray-400">
                            Aucun log trouvé pour ces critères.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ───── Pagination ───── --}}
    @if($logs->hasPages())
        <div class="flex justify-center">
            {{ $logs->links() }}
        </div>
    @endif

</div>

@endsection
