@if ($errors->any())
    <div class="rounded-xl border border-red-300 bg-red-100 p-4 text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif