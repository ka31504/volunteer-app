<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Project;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Danh sách tất cả tình nguyện viên (có lọc & tìm kiếm + sắp xếp).
     */
    public function index(Request $request)
    {
        $query = Participant::with('project');

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $query->search($search);           // giả sử bạn có scope search()
        }

        // Lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Lọc theo dự án
        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        // ====================== SORTING ======================
        $sort = $request->get('sort');
        $direction = $request->get('direction', 'desc');   // mặc định mới nhất

        $allowedSorts = ['full_name', 'hours_contributed', 'joined_at', 'role', 'created_at'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest('joined_at');   // mặc định sắp theo ngày tham gia mới nhất
        }
        // ====================================================

        $participants = $query->paginate(15)->withQueryString();
        $projects     = Project::orderBy('name')->get(['id', 'name']);

        return view('participants.index', compact('participants', 'projects'));
    }

    /**
     * Form tạo tình nguyện viên mới.
     */
    public function create()
    {
        $projects = Project::orderBy('name')->get(['id', 'name']);
        return view('participants.create', compact('projects'));
    }

    /**
     * Lưu tình nguyện viên mới vào DB.
     */
    public function store(StoreParticipantRequest $request)
    {
        Participant::create($request->validated());

        return redirect()
            ->route('participants.index')
            ->with('success', 'Đã thêm tình nguyện viên thành công!');
    }

    /**
     * Xem chi tiết tình nguyện viên.
     */
    public function show(Participant $participant)
    {
        $participant->load('project');
        return view('participants.show', compact('participant'));
    }

    /**
     * Form chỉnh sửa tình nguyện viên.
     */
    public function edit(Participant $participant)
    {
        $projects = Project::orderBy('name')->get(['id', 'name']);
        return view('participants.edit', compact('participant', 'projects'));
    }

    /**
     * Cập nhật thông tin tình nguyện viên.
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        $participant->update($request->validated());

        return redirect()
            ->route('participants.index')
            ->with('success', 'Đã cập nhật thông tin tình nguyện viên!');
    }

    /**
     * Xoá tình nguyện viên.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()
            ->route('participants.index')
            ->with('success', 'Đã xoá tình nguyện viên!');
    }
}