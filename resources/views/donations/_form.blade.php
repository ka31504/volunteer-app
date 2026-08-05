{{-- resources/views/donations/_form.blade.php --}}
@php $d = $donation ?? null; @endphp

<div class="p-form-grid" x-data="donationForm({
    initialType: '{{ old('type', $d?->type ?? 'money') }}'
})">

    {{-- Cột trái: Thông tin người đóng góp --}}
    <div class="p-form-section">
        <div class="p-section-title">Thông tin người đóng góp</div>

        <div class="p-field">
            <label class="p-label req" for="project_id">Dự án</label>
            <select id="project_id" name="project_id" class="p-input" required>
                <option value="">— Chọn dự án —</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id', $d?->project_id) == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field">
            <label class="p-label req" for="donor_name">Tên người đóng góp</label>
            <input id="donor_name" type="text" name="donor_name" 
                   value="{{ old('donor_name', $d?->donor_name) }}" class="p-input" required>
            @error('donor_name')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <div class="p-field">
            <label class="p-label" for="donor_phone">Số điện thoại</label>
            <input id="donor_phone" type="tel" name="donor_phone"
                   value="{{ old('donor_phone', $d?->donor_phone) }}" class="p-input">
        </div>

        <div class="p-field">
            <label class="p-label req" for="donated_at">Ngày đóng góp</label>
            <input id="donated_at" type="date" name="donated_at"
                   value="{{ old('donated_at', $d?->donated_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" 
                   class="p-input" required>
            @error('donated_at')<div class="p-error">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Cột phải: Chi tiết đóng góp --}}
    <div class="p-form-section">
        <div class="p-section-title">Chi tiết đóng góp</div>

        <div class="p-field">
            <label class="p-label req">Loại đóng góp</label>
            <div class="flex gap-6 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="money" x-model="type" class="accent-[var(--primary)]" required>
                    <span class="text-sm">Tiền</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="goods" x-model="type" class="accent-[var(--primary)]">
                    <span class="text-sm">Hiện vật</span>
                </label>
            </div>
            @error('type')<div class="p-error">{{ $message }}</div>@enderror
        </div>

        <!-- Phần Tiền -->
        <div x-show="type === 'money'" x-transition>
            <div class="p-field">
                <label class="p-label req" for="amount">Số tiền (VNĐ)</label>
                <div class="p-input-wrap">
                    <input id="amount" type="number" name="amount"
                        value="{{ old('amount', $d?->amount) }}" 
                        class="p-input" 
                        min="1000" step="1000"
                        :required="type === 'money'"
                        :disabled="type !== 'money'">
                    <span class="p-input-addon">đ</span>
                </div>
                @error('amount')<div class="p-error">{{ $message }}</div>@enderror
            </div>

            <div class="p-field">
                <label class="p-label" for="payment_method">Hình thức thanh toán</label>
                <select id="payment_method" name="payment_method" class="p-input">
                    <option value="cash" {{ old('payment_method', $d?->payment_method ?? 'cash') === 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                    <option value="transfer" {{ old('payment_method', $d?->payment_method) === 'transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                    <option value="other" {{ old('payment_method', $d?->payment_method) === 'other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
        </div>

        <!-- Phần Hiện vật -->
        <div x-show="type === 'goods'" x-transition>
            

            <div class="p-field">
                <label class="p-label req" for="goods_description">Mô tả hiện vật</label>
                <input id="goods_description" type="text" name="goods_description"
                       value="{{ old('goods_description', $d?->goods_description) }}" 
                       class="p-input" :required="type === 'goods'">
                @error('goods_description')<div class="p-error">{{ $message }}</div>@enderror
            </div>

            <div class="p-field">
                <label class="p-label req" for="goods_quantity">Số lượng</label>
                <input id="goods_quantity" type="number" name="goods_quantity"
                       value="{{ old('goods_quantity', $d?->goods_quantity) }}" 
                       class="p-input" min="1" :required="type === 'goods'">
                @error('goods_quantity')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="p-field">
            <label class="p-label" for="note">Ghi chú</label>
            <textarea id="note" name="note" rows="4" class="p-textarea">{{ old('note', $d?->note) }}</textarea>
        </div>
    </div>
</div>

<script>
function donationForm({ initialType }) {
    return {
        type: initialType,

        init() {
            // Clear dữ liệu không liên quan khi chuyển loại
            this.$watch('type', (newType) => {
                if (newType === 'money') {
                    this.$el.querySelector('[name="goods_description"]').value = '';
                    this.$el.querySelector('[name="goods_quantity"]').value = '';
                } else {
                    this.$el.querySelector('[name="amount"]').value = '';
                }
            });
        }
    }
}
</script>