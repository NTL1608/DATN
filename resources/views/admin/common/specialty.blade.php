@foreach($specialties as $key => $specialty)
    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
@endforeach