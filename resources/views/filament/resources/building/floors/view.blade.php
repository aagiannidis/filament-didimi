// resources/views/filament/resources/buildingasasa/floors/view.blade.php
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-lg font-medium">Floor Details</h3>
            <dl class="mt-2 space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Floor Number</dt>
                    <dd class="text-sm text-gray-900">{{ $floor->number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="text-sm text-gray-900">{{ $floor->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Rooms</dt>
                    <dd class="text-sm text-gray-900">{{ $floor->rooms_count }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Capacity</dt>
                    <dd class="text-sm text-gray-900">{{ $floor->rooms->sum('capacity') }}</dd>
                </div>
            </dl>
        </div>
        @if($floor->floor_plan_url)
            <div>
                <img src="{{ Storage::url($floor->floor_plan_url) }}" 
                     alt="Floor Plan" 
                     class="w-full h-auto rounded-lg shadow">
            </div>
        @endif
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-medium">Rooms</h3>
        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($floor->rooms as $room)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium">{{ $room->name }}</h4>
                    <p class="text-sm text-gray-500">Capacity: {{ $room->capacity }}</p>
                    <p class="text-sm text-gray-500">Type: {{ str_replace('_', ' ', $room->type) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
