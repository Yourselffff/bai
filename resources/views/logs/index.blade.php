@extends('layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">Journaux système</h1>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse bg-white shadow rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="px-4 py-3 text-left w-44">Date</th>
                        <th class="px-4 py-3 text-left w-28">Niveau</th>
                        <th class="px-4 py-3 text-left">Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 font-mono text-xs text-gray-600">{{ $log['date'] }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $levelClass = match($log['level']) {
                                        'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY' => 'bg-red-100 text-red-800',
                                        'WARNING' => 'bg-yellow-100 text-yellow-800',
                                        'INFO' => 'bg-blue-100 text-blue-800',
                                        'DEBUG' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $levelClass }}">
                                    {{ $log['level'] }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-700 text-xs">{{ $log['message'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">Aucun log disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
