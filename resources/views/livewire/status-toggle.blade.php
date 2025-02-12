<div class="space-x-2">
    @if (session('is_admin') && $model->status=='Inactive')
        @foreach ($actions as $action)
            @if ($action === 'Edit')
                <a href="{{ route($editRoute, $model) }}" class="text-blue-500 hover:underline">Edit</a>
            @endif
            @if ($action === 'Delete')
                <form action="{{ route($deleteRoute, $model) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            @endif
        @endforeach
    @endif
    @if (session('is_admin'))
        <!-- Admin: Status Toggle Form -->
        <div class="inline hidden sm:inline">
            <button wire:click="toggleStatus" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                {{ $model->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}">
                {{ $model->status }}
            </button>
            
            @if (session()->has('error'))
                <p class="text-red-500 mt-2">{{ session('error') }}</p>
            @endif
        </div>
        <!-- Mobile: Read-only Status -->
        <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden {{ $model->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
            {{ $model->status }}
        </span>
    @else
        <!-- Non-admin: Read-only Status -->
        <span class="px-2 py-1 text-white text-sm font-semibold rounded 
            {{ $model->status === 'Active' ? 'bg-green-500' : 'bg-red-500' }}">
            {{ $model->status }}
        </span>
    @endif
</div>
