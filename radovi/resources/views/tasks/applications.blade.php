<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.applications') }}
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

                    @forelse($tasks as $task)
                        @if($task->applications->count() > 0)
                            <div class="mb-8 border rounded-lg p-6">
                                <h3 class="text-xl font-semibold mb-4">
                                    @if(app()->getLocale() === 'hr')
                                        {{ $task->naziv_rada }}
                                    @else
                                        {{ $task->naziv_rada_en }}
                                    @endif
                                    
                                    @if($task->accepted_student_id)
                                        <span class="ml-2 px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                                            {{ __('messages.accepted') }}
                                        </span>
                                    @endif
                                </h3>

                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('messages.student') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('messages.email') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('messages.status') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('messages.actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($task->applications as $application)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $application->student->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $application->student->email }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($application->status === 'accepted')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            {{ __('messages.accepted') }}
                                                        </span>
                                                    @elseif($application->status === 'rejected')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            {{ __('messages.rejected') }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            {{ __('messages.pending') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($application->status === 'pending' && !$task->accepted_student_id)
                                                        <form action="{{ route('tasks.applications.accept', $application) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                                {{ __('messages.accept') }}
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('tasks.applications.reject', $application) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                {{ __('messages.reject') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @empty
                        <p class="text-gray-500 text-center">{{ __('messages.no_tasks') }}</p>
                    @endforelse

                    @if($tasks->every(fn($task) => $task->applications->count() === 0))
                        <p class="text-gray-500 text-center">{{ __('messages.no_applications') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>