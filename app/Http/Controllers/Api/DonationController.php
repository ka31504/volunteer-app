<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreDonationRequest;
use App\Http\Requests\Api\UpdateDonationRequest;
use App\Http\Resources\DonationResource;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    private const ALLOWED_SORTS = ['id', 'donated_at', 'amount'];

    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), self::ALLOWED_SORTS, true)
            ? $request->query('sort')
            : 'id';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $query = Donation::query()->with('sponsor');

        if ($projectId = $request->query('project_id')) {
            $query->where('project_id', $projectId);
        }
        if ($sponsorId = $request->query('sponsor_id')) {
            $query->where('sponsor_id', $sponsorId);
        }

        $donations = $query->orderBy($sort, $direction)
            ->paginate($request->integer('per_page', 15));

        return DonationResource::collection($donations);
    }

    public function show(Donation $donation)
    {
        $donation->load(['project', 'sponsor']);

        return new DonationResource($donation);
    }

    public function store(StoreDonationRequest $request)
    {
        $donation = Donation::create($request->validated());

        return (new DonationResource($donation))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        $donation->update($request->validated());

        return new DonationResource($donation);
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return response()->json(null, 204);
    }
}
