{{-- Dùng chung cho create & edit. Biến: $sponsor --}}

@include('participants._styles')

<div class="p-form-grid">

    <div class="p-form-section">
        <div class="p-section-title">Thông tin nhà tài trợ</div>

        <div class="p-field">
            <label class="p-label req" for="name">Tên nhà tài trợ</label>
            <input id="name" type="text" name="name"
                   class="p-input {{ $errors->has('name') ? 'error' : '' }}"
                   value="{{ old('name', $sponsor->name) }}"
                   placeholder="Nguyễn Văn A / Công ty ABC">
            @error('name')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field">
            <label class="p-label req" for="type">Loại</label>
            <select id="type" name="type"
                    class="p-input {{ $errors->has('type') ? 'error' : '' }}">
                <option value="individual"   {{ old('type', $sponsor->type ?? 'individual') === 'individual'   ? 'selected' : '' }}>Cá nhân</option>
                <option value="organization" {{ old('type', $sponsor->type) === 'organization' ? 'selected' : '' }}>Tổ chức</option>
            </select>
            @error('type')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label" for="phone">Số điện thoại</label>
                <input id="phone" type="tel" name="phone"
                       class="p-input {{ $errors->has('phone') ? 'error' : '' }}"
                       value="{{ old('phone', $sponsor->phone) }}"
                       placeholder="09xx xxx xxx">
                @error('phone')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label" for="email">Email</label>
                <input id="email" type="email" name="email"
                       class="p-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email', $sponsor->email) }}"
                       placeholder="email@example.com">
                @error('email')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="p-field">
            <label class="p-label" for="address">Địa chỉ</label>
            <input id="address" type="text" name="address"
                   class="p-input {{ $errors->has('address') ? 'error' : '' }}"
                   value="{{ old('address', $sponsor->address) }}"
                   placeholder="Số nhà, đường, quận/huyện, tỉnh">
            @error('address')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field">
            <label class="p-label" for="tax_code">Mã số thuế</label>
            <input id="tax_code" type="text" name="tax_code"
                   class="p-input {{ $errors->has('tax_code') ? 'error' : '' }}"
                   value="{{ old('tax_code', $sponsor->tax_code) }}"
                   placeholder="Chỉ áp dụng cho tổ chức">
            @error('tax_code')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field">
            <label class="p-label" for="notes">Ghi chú</label>
            <textarea id="notes" name="notes" rows="4"
                      class="p-textarea {{ $errors->has('notes') ? 'error' : '' }}"
                      placeholder="Ghi chú thêm...">{{ old('notes', $sponsor->notes) }}</textarea>
            @error('notes')<div class="p-error">{{ $message }}</div>@enderror
        </div>
    </div>

</div>