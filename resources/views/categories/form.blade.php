<div class="form-group">
    <label for="name">ক্যাটাগরির নাম</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name ?? '') }}" required>
    @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="type">প্রকার</label>
    <select name="type" class="form-control @error('type') is-invalid @enderror" required>
        @php $currentType = old('type', $category->type ?? ''); @endphp
        <option value="income" {{ $currentType == 'income' ? 'selected' : '' }}>আয় (Income)</option>
        <option value="expense" {{ $currentType == 'expense' ? 'selected' : '' }}>ব্যয় (Expense)</option>
    </select>
    @error('type')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div><div class="form-group">
    <label for="name">ক্যাটাগরির নাম</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name ?? '') }}" required>
    @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="type">প্রকার</label>
    <select name="type" class="form-control @error('type') is-invalid @enderror" required>
        @php $currentType = old('type', $category->type ?? ''); @endphp
        <option value="income" {{ $currentType == 'income' ? 'selected' : '' }}>আয় (Income)</option>
        <option value="expense" {{ $currentType == 'expense' ? 'selected' : '' }}>ব্যয় (Expense)</option>
    </select>
    @error('type')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>