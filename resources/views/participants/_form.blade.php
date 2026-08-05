{{-- Dùng chung cho create & edit. Biến: $participant, $projects --}}

@include('participants._styles')

<div class="p-form-grid">

    {{-- ── Cột trái: Thông tin cá nhân ── --}}
    <div class="p-form-section">
        <div class="p-section-title">Thông tin cá nhân</div>

        {{-- Dự án --}}
        <div class="p-field">
            <label class="p-label req" for="project_id">Dự án</label>
            <select id="project_id" name="project_id"
                    class="p-input {{ $errors->has('project_id') ? 'error' : '' }}">
                <option value="">— Chọn dự án —</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}"
                        {{ old('project_id', $participant->project_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        {{-- Họ tên --}}
        <div class="p-field">
            <label class="p-label req" for="full_name">Họ và tên</label>
            <input id="full_name" type="text" name="full_name"
                   class="p-input {{ $errors->has('full_name') ? 'error' : '' }}"
                   value="{{ old('full_name', $participant->full_name) }}"
                   placeholder="Nguyễn Văn A">
            @error('full_name')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        {{-- Phone + Email --}}
        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label" for="phone">Số điện thoại</label>
                <input id="phone" type="tel" name="phone"
                       class="p-input {{ $errors->has('phone') ? 'error' : '' }}"
                       value="{{ old('phone', $participant->phone) }}"
                       placeholder="09xx xxx xxx">
                @error('phone')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label" for="email">Email</label>
                <input id="email" type="email" name="email"
                       class="p-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email', $participant->email) }}"
                       placeholder="email@example.com">
                @error('email')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Ngày sinh + Giới tính --}}
        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label" for="birth_date">Ngày sinh</label>
                <input id="birth_date" type="date" name="birth_date"
                       class="p-input {{ $errors->has('birth_date') ? 'error' : '' }}"
                       value="{{ old('birth_date', $participant->birth_date?->format('Y-m-d')) }}">
                @error('birth_date')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label req" for="gender">Giới tính</label>
                <select id="gender" name="gender"
                        class="p-input {{ $errors->has('gender') ? 'error' : '' }}">
                    <option value="male"   {{ old('gender', $participant->gender) === 'male'   ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $participant->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other"  {{ old('gender', $participant->gender) === 'other'  ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Địa chỉ --}}
        <div class="p-field">
            <label class="p-label" for="address">Địa chỉ</label>
            <input id="address" type="text" name="address"
                   class="p-input {{ $errors->has('address') ? 'error' : '' }}"
                   value="{{ old('address', $participant->address) }}"
                   placeholder="Số nhà, đường, quận/huyện, tỉnh">
            @error('address')<div class="p-error">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- ── Cột phải: Thông tin tình nguyện ── --}}
    <div class="p-form-section">
        <div class="p-section-title">Thông tin tình nguyện</div>

        {{-- Ngày tham gia + Ngày kết thúc --}}
        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label req" for="joined_at">Ngày tham gia</label>
                <input id="joined_at" type="date" name="joined_at"
                       class="p-input {{ $errors->has('joined_at') ? 'error' : '' }}"
                       value="{{ old('joined_at', $participant->joined_at?->format('Y-m-d')) }}">
                @error('joined_at')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label" for="ended_at">Ngày kết thúc</label>
                <input id="ended_at" type="date" name="ended_at"
                       class="p-input {{ $errors->has('ended_at') ? 'error' : '' }}"
                       value="{{ old('ended_at', $participant->ended_at?->format('Y-m-d')) }}">
                @error('ended_at')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Số giờ --}}
        <div class="p-field">
            <label class="p-label req" for="hours_contributed">Số giờ tình nguyện</label>
            <div class="p-input-wrap">
                <input id="hours_contributed" type="number" name="hours_contributed"
                       class="p-input {{ $errors->has('hours_contributed') ? 'error' : '' }}"
                       style="padding-right:36px"
                       value="{{ old('hours_contributed', $participant->hours_contributed ?? 0) }}"
                       min="0" max="9999">
                <span class="p-input-addon">giờ</span>
            </div>
            @error('hours_contributed')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        {{-- Vai trò + Trạng thái --}}
        <div class="p-field-row">
            <div class="p-field">
                <label class="p-label req" for="role">Vai trò</label>
                <select id="role" name="role"
                        class="p-input {{ $errors->has('role') ? 'error' : '' }}">
                    <option value="volunteer"   {{ old('role', $participant->role ?? 'volunteer') === 'volunteer'   ? 'selected' : '' }}>Tình nguyện viên</option>
                    <option value="team_lead"   {{ old('role', $participant->role) === 'team_lead'   ? 'selected' : '' }}>Trưởng nhóm</option>
                    <option value="coordinator" {{ old('role', $participant->role) === 'coordinator' ? 'selected' : '' }}>Điều phối viên</option>
                </select>
                @error('role')<div class="p-error">{{ $message }}</div>@enderror
            </div>
            <div class="p-field">
                <label class="p-label req" for="status">Trạng thái</label>
                <select id="status" name="status"
                        class="p-input {{ $errors->has('status') ? 'error' : '' }}">
                    <option value="active"   {{ old('status', $participant->status ?? 'active') === 'active'   ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="pending"  {{ old('status', $participant->status) === 'pending'  ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="inactive" {{ old('status', $participant->status) === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
                </select>
                @error('status')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Ghi chú --}}
        <div class="p-field">
            <label class="p-label" for="notes">Ghi chú</label>
            <textarea id="notes" name="notes" rows="4"
                      class="p-textarea {{ $errors->has('notes') ? 'error' : '' }}"
                      placeholder="Kỹ năng đặc biệt, ghi chú thêm...">{{ old('notes', $participant->notes) }}</textarea>
            @error('notes')<div class="p-error">{{ $message }}</div>@enderror
        </div>
    </div>

</div>
