{{-- Shared fields for creating / editing a testimonial. Expects optional $testimonial. --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group mb-3">
    <label for="patient_name">{{ trans('AdminSb.patient_name') }}</label>
    <input type="text" id="patient_name" name="patient_name" class="form-control" maxlength="255" required
        value="{{ old('patient_name', $testimonial->patient_name ?? '') }}">
</div>

<div class="form-group mb-3">
    <label for="comment">{{ trans('AdminSb.comment') }}</label>
    <textarea id="comment" name="comment" rows="5" class="form-control" required>{{ old('comment', $testimonial->comment ?? '') }}</textarea>
</div>

<div class="form-group mb-4">
    <label for="status">{{ trans('AdminSb.status') }}</label>
    @php($currentStatus = old('status', $testimonial->status ?? 'pending'))
    <select id="status" name="status" class="form-control">
        <option value="pending" @selected($currentStatus === 'pending')>{{ trans('AdminSb.pending_review') }}</option>
        <option value="approved" @selected($currentStatus === 'approved')>{{ trans('AdminSb.approved') }}</option>
        <option value="rejected" @selected($currentStatus === 'rejected')>{{ trans('AdminSb.rejected') }}</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">{{ trans('AdminSb.save') }}</button>
<a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">{{ trans('AdminSb.cancel') }}</a>
