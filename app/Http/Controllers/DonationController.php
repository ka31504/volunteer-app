<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Sponsor;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;

class DonationController extends Controller
{
    /**
     * Danh sách tất cả đóng góp (có lọc & tìm kiếm).
     */
    public function index(Request $request)
    {
        // 1. XÓA BỎ ->latest('donated_at') ở đây
        $query = Donation::with('project');

        // Tìm kiếm theo tên người đóng góp hoặc số điện thoại
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                    ->orWhere('donor_phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo dự án
        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        // Lọc theo loại đóng góp
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // ====================== SORTING ======================
        $sort = $request->get('sort');
        $direction = $request->get('direction', 'desc'); // Chuyển mặc định thành desc cho hợp logic mới nhất

        // 2. THÊM allowedSorts ĐỂ BẢO MẬT VÀ TRÁNH LỖI
        $allowedSorts = ['donor_name', 'amount', 'donated_at', 'type', 'created_at'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            // 3. CHUYỂN latest() XUỐNG ĐÂY LÀM MẶC ĐỊNH
            $query->latest('donated_at');
        }
        // ====================================================

        $donations = $query->paginate(15)->withQueryString();

        // Truyền danh sách dự án cho bộ lọc
        $projects = Project::orderBy('name')->get(['id', 'name']);

        return view('donations.index', compact('donations', 'projects'));
    }

    /**
     * Form tạo đóng góp mới.
     */
    public function create()
    {
        $projects = Project::orderBy('name')->get(['id', 'name']);
        $sponsors = Sponsor::orderBy('name')->get(['id', 'name', 'type']);
        return view('donations.create', compact('projects', 'sponsors'));
    }

    /**
     * Lưu đóng góp mới
     */
    public function store(StoreDonationRequest $request)
    {
        $validated = $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'donor_name'        => 'required|string|max:255',
            'donor_phone'       => 'nullable|string|max:20',
            'donated_at'        => 'required|date',
            'type'              => 'required|in:money,goods',

            // Thêm 'nullable' trước rule kiểu dữ liệu, để khi field không áp dụng
            // (bị middleware chuyển thành null) không bị chặn bởi string/integer/numeric
            'amount'            => 'nullable|required_if:type,money|numeric|min:1000',
            'payment_method'    => 'nullable|string|in:cash,transfer,other',
            'goods_description' => 'nullable|required_if:type,goods|string|max:500',
            'goods_quantity'    => 'nullable|required_if:type,goods|integer|min:1',

            'note'              => 'nullable|string|max:1000',
        ], [
            'amount.required_if'            => 'Vui lòng nhập số tiền.',
            'goods_description.required_if' => 'Vui lòng nhập mô tả hiện vật.',
            'goods_quantity.required_if'    => 'Vui lòng nhập số lượng hiện vật.',
        ]);

        Donation::create($request->validated());
        Cache::forget("statistics.cards." . now()->year);
        return redirect()->route('donations.index')
            ->with('success', 'Đã thêm khoản đóng góp thành công!');
    }

    /**
     * Cập nhật đóng góp
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'project_id'        => 'required|exists:projects,id',
            'donor_name'        => 'required|string|max:255',
            'donor_phone'       => 'nullable|string|max:20',
            'donated_at'        => 'required|date',
            'type'              => 'required|in:money,goods',

            // Thêm 'nullable' trước rule kiểu dữ liệu, để khi field không áp dụng
            // (bị middleware chuyển thành null) không bị chặn bởi string/integer/numeric
            'amount'            => 'nullable|required_if:type,money|numeric|min:1000',
            'payment_method'    => 'nullable|string|in:cash,transfer,other',
            'goods_description' => 'nullable|required_if:type,goods|string|max:500',
            'goods_quantity'    => 'nullable|required_if:type,goods|integer|min:1',

            'note'              => 'nullable|string|max:1000',
        ], [
            'amount.required_if'            => 'Vui lòng nhập số tiền.',
            'goods_description.required_if' => 'Vui lòng nhập mô tả hiện vật.',
            'goods_quantity.required_if'    => 'Vui lòng nhập số lượng hiện vật.',
        ]);

        $donation->update($validated());
        Cache::forget("statistics.cards." . now()->year);
        return redirect()->route('donations.index')
            ->with('success', 'Đã cập nhật khoản đóng góp thành công!');
    }

    /**
     * Xoá đóng góp.
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();
        Cache::forget("statistics.cards." . now()->year);
        return redirect()
            ->route('donations.index')
            ->with('success', 'Đã xoá khoản đóng góp!');
    }

    // Hiển thị chi tiết đóng góp (nếu cần)
    public function show(Donation $donation)
    {
        $donation->load('project');
        return view('donations.show', compact('donation'));
    }

    // Form chỉnh sửa đóng góp (nếu cần)
    public function edit(Donation $donation)
    {
        $projects = Project::orderBy('name')->get(['id', 'name']);
        $sponsors = Sponsor::orderBy('name')->get(['id', 'name', 'type']);
        return view('donations.edit', compact('donation', 'projects', 'sponsors'));
    }
}
