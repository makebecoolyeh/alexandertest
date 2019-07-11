@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('service.response') }}">
                @csrf
                <div class="form-group row">
                    <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Enter date:') }}</label>
                    <div class="col-md-6">
                        <input id="date" type="input" class="form-control @error('date') is-invalid @enderror" name="date" required>
                        @error('date')
                        <span class="invalid-date" role="alert">
                            <strong>{{'Submitted date is invalid'}}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Enter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection