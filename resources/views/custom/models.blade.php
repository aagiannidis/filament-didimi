
@php
    // Retrieve the files state (assuming it’s stored as an array of file paths)
    //$cdata = $getRecord()->toArray();
    //dd($models);
    // $models = $getRecord()->toArray();
    // dd($models);
@endphp



@if(is_array($models) && count($models))
    <ul class="space-y-2">
        @foreach($models as $item)
            <li class="flex items-center">
                {{$item}}
            </li>
        @endforeach
    </ul>
@else
    <p>No models uploaded.</p>
@endif
