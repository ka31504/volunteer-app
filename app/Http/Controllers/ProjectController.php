<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Hiển thị danh sách dự án (có lọc & tìm kiếm)
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Tìm kiếm theo tên hoặc mô tả
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $sort = $request->get('sort');
        $direction = $request->get('direction', 'asc');

        if ($sort) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $projects = $query->latest()
                          ->paginate(15)
                          ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    /**
     * Form tạo dự án mới
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Lưu dự án mới
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Tạo dự án thành công!');
    }

    /**
     * Xem chi tiết dự án
     */
    public function show(Project $project)
    {
        $project->load(['donations', 'participants']);
        return view('projects.show', compact('project'));
    }

    /**
     * Form chỉnh sửa dự án
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Cập nhật dự án
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Cập nhật dự án thành công!');
    }

    /**
     * Xóa dự án
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Xóa dự án thành công!');
    }
}