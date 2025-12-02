<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.available_tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($tasks as $task)
                            <div class="border rounded-lg p-6 shadow-sm hover:shadow-md transition">
                                <h3 class="text-lg font-semibold mb-2">
                                    @if(app()->getLocale() === 'hr')
                                        {{ $task->naziv_rada }}
                                    @else
                                        {{ $task->naziv_rada_en }}
                                    @endif
                                </h3>
                                
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>{{ __('messages.teacher') }}:</strong> {{ $task->nastavnik->name }}
                                </p>
                                
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>{{ __('messages.study_type') }}:</strong> {{ $task->tip_studija }}
                                </p>
                                
                                <p class="text-gray-700 mb-4">
                                    @if(app()->getLocale() === 'hr')
                                        {{ Str::limit($task->zadatak_rada, 150) }}
                                    @else
                                        {{ Str::limit($task->zadatak_rada_en, 150) }}
                                    @endif
                                </p>
                                
                                @if(in_array($task->id, $appliedTaskIds))
                                    <button disabled class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                        {{ __('messages.already_applied') }}
                                    </button>
                                @else
                                    <form action="{{ route('student.apply', $task) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            {{ __('messages.apply') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-3 text-center text-gray-500 py-8">
                                {{ __('messages.no_tasks') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>