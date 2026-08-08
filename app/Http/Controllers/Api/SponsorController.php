<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSponsorRequest;
use App\Http\Requests\Api\UpdateSponsorRequest;
use App\Http\Resources\SponsorResource;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    private const ALLOWED_SORTS = ['id', 'name'];

    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), self::ALLOWED_SORTS, true)
            ? $request->query('sort')
            : 'id';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $query = Sponsor::query();

        if ($type = $request->query('type')) {
            $query->ofType($type); // scope có sẵn trong model
        }
        if ($term = $request->query('search')) {
            $query->search($term);
        }

        $sponsors = $query->orderBy($sort, $direction)
            ->paginate($request->integer('per_page', 15));

        return SponsorResource::collection($sponsors);
    }

    public function show(Sponsor $sponsor)
    {
        // Giống web: kèm lịch sử đóng góp liên quan
        $sponsor->load(['donations' => fn ($q) => $q->latest('donated_at')]);

        return new SponsorResource($sponsor);
    }

    public function store(StoreSponsorRequest $request)
    {
        $sponsor = Sponsor::create($request->validated());

        return (new SponsorResource($sponsor))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {
        $sponsor->update($request->validated());

        return new SponsorResource($sponsor);
    }

    public function destroy(Sponsor $sponsor)
    {
        $sponsor->delete();

        return response()->json(null, 204);
    }
}
