@extends('Blogbackend.components.layout')


@section('content')
<div class="form-container" style="max-width: 600px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <form action="{{ route('Generating') }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        @csrf
        <input type="hidden" name="model" value="{{ $modelName }}">
        <input type="hidden" name="table" value="{{ $tableName }}">
        <h1 style="text-align: center; color: #333; font-size: 24px;">Generate MVC</h1>
        <div style="display: flex; flex-wrap: wrap; gap: 10px; flex-direction: column; margin: auto 50px;">
            <h4>Select listing columns:</h4>
            @foreach ($columns as $item)
                <label style="display: flex; align-items: center; gap: 5px; font-size: 16px; color: #555;">
                    <input type="checkbox" name="columns[]" value="{{ $item }}" style="transform: scale(1.2); margin-right: 5px;">
                    {{ ucfirst($item) }}
                </label>
            @endforeach
        </div>
        <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Generate
        </button>
    </form>
</div>
@endsection

