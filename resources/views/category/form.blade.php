<div class="row">
    <div class="col-md-12">
        
        <div class="form-group mb-3">
            <label for="name" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category?->name) }}" id="name" placeholder="Nombre">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="form-group mb-3">
            <label for="description" class="form-label">{{ __('Descripción') }}</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Descripción">{{ old('description', $category?->description) }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
    </div>
</div>