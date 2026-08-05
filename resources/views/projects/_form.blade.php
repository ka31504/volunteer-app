{{-- Dùng chung cho create & edit. Biến: $project --}}
@php $p = $project ?? null; @endphp

<div class="p-form-grid">

    {{-- Cột trái --}}
    <div class="p-form-section">
        <div class="p-section-title">Thông tin dự án</div>

        <div class="p-field">
            <label class="p-label req" for="name">Tên dự án</label>
            <input id="name" type="text" name="name"
                   class="p-input {{ $errors->has('name') ? 'error' : '' }}"
                   value="{{ old('name', $p?->name) }}" required>
            @error('name')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field">
            <label class="p-label req" for="description">Mô tả dự án</label>
            <textarea id="description" name="description" rows="5"
                      class="p-textarea {{ $errors->has('description') ? 'error' : '' }}" required>
                {{ old('description', $p?->description) }}
            </textarea>
            @error('description')<div class="p-error">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Cột phải --}}
    <div class="p-form-section">
        <div class="p-section-title">Thông tin tài chính & thời gian</div>

        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label req" for="target_amount">Số tiền mục tiêu (VNĐ)</label>
                <input id="target_amount" type="number" name="target_amount"
                       class="p-input {{ $errors->has('target_amount') ? 'error' : '' }}"
                       value="{{ old('target_amount', $p?->target_amount) }}" min="0" required>
                @error('target_amount')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label" for="current_amount">Số tiền đã nhận (VNĐ)</label>
                <input id="current_amount" type="number" name="current_amount"
                       class="p-input {{ $errors->has('current_amount') ? 'error' : '' }}"
                       value="{{ old('current_amount', $p?->current_amount ?? 0) }}" min="0">
                @error('current_amount')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="p-field">
            <label class="p-label" for="status">Trạng thái</label>
            <select id="status" name="status" class="p-input">
                @foreach([
                    'planning'  => 'Planning (Chuẩn bị)',
                    'ongoing'   => 'Ongoing (Đang thực hiện)',
                    'completed' => 'Completed (Hoàn thành)',
                    'closed'    => 'Closed (Đã đóng)',
                ] as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('status', $p?->status ?? 'ongoing') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label req" for="start_date">Ngày bắt đầu</label>
                <input id="start_date" type="date" name="start_date"
                       class="p-input {{ $errors->has('start_date') ? 'error' : '' }}"
                       value="{{ old('start_date', $p?->start_date?->format('Y-m-d')) }}" required>
                @error('start_date')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label req" for="end_date">Ngày kết thúc</label>
                <input id="end_date" type="date" name="end_date"
                       class="p-input {{ $errors->has('end_date') ? 'error' : '' }}"
                       value="{{ old('end_date', $p?->end_date?->format('Y-m-d')) }}" required>
                @error('end_date')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

</div>