<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Http\Requests\StoreSponsorRequest;
use App\Http\Requests\UpdateSponsorRequest;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        $query = Sponsor::query();

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($type = $request->input('type')) {
            $query->ofType($type);
        }

        $sort = $request->get('sort');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['name', 'phone', 'email', 'created_at'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        $sponsors = $query->paginate(15)->withQueryString();

        return view('sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        return view('sponsors.create');
    }

    public function store(StoreSponsorRequest $request)
    {
        Sponsor::create($request->validated());

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Đã thêm nhà tài trợ thành công!');
    }

    public function show(Sponsor $sponsor)
    {
        $sponsor->load(['donations' => fn ($q) => $q->with('project')->latest('donated_at')]);

        return view('sponsors.show', compact('sponsor'));
    }

    public function edit(Sponsor $sponsor)
    {
        return view('sponsors.edit', compact('sponsor'));
    }

    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {
        $sponsor->update($request->validated());

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Đã cập nhật thông tin nhà tài trợ!');
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->donations()->exists()) {
            return redirect()
                ->route('sponsors.index')
                ->with('error', 'Không thể xoá: nhà tài trợ này đang gắn với đóng góp hiện có.');
        }

        $sponsor->delete();

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Đã xoá nhà tài trợ!');
    }
}