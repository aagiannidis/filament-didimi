// resources/views/filament/resources/building/rooms/view.blade.php
<div class="space-y-6">
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium">Room Details</h3>
                <dl class="mt-2 grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Room Number</dt>
                        <dd class="text-sm text-gray-900">{{ $room->number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $room->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="text-sm text-gray-900">{{ str_replace('_', ' ', $room->type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                        <dd class="text-sm text-gray-900">{{ $room->capacity }} people</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Area</dt>
                        <dd class="text-sm text-gray-900">{{ $room->area_sqm }} m²</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ $room->status }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h4 class="text-md font-medium">Assets</h4>
                <div class="mt-2 space-y-3">
                    @foreach(['hvac', 'lighting', 'network', 'electrical'] as $assetType)
                        @if(count($room->assets[$assetType] ?? []) > 0)
                            <div>
                                <h5 class="text-sm font-medium text-gray-500">{{ strtoupper($assetType) }}</h5>
                                <ul class="mt-1 text-sm text-gray-900">
                                    @foreach($room->assets[$assetType] as $assetId)
                                        <li>{{ $assetId }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium mb-4">Wall Ports</h3>
            <div class="space-y-3">
                @foreach($room->wallPorts as $port)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">{{ $port->id }}</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $port->status === 'ACTIVE' ? 'bg-green-100 text-green-800' : 
                                   $port->status === 'INACTIVE' ? 'bg-gray-100 text-gray-800' : 
                                   'bg-red-100 text-red-800' }}">
                                {{ $port->status }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Type:</span>
                                <span class="ml-1">{{ $port->type }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Location:</span>
                                <span class="ml-1">{{ $port->location }}</span>
                            </div>
                            @if($port->type === 'DATA')
                                <div>
                                    <span class="text-gray-500">Speed:</span>
                                    <span class="ml-1">{{ $port->speed }}</span>
                                </div>
                            @else
                                <div>
                                    <span class="text-gray-500">Extension:</span>
                                    <span class="ml-1">{{ $port->extension }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="text-gray-500">Last Tested:</span>
                                <span class="ml-1">{{ \Carbon\Carbon::parse($port->last_tested_date)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
