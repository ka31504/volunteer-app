<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreParticipantRequest;
use App\Http\Requests\Api\UpdateParticipantRequest;
use App\Http\Resources\ParticipantResource;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    private const ALLOWED_SORTS = ['id', 'joined_at', 'full_name'];

    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), self::ALLOWED_SORTS, true)
            ? $request->query('sort')
            : 'id';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $query = Participant::query();

        if ($projectId = $request->query('project_id')) {
            $query->where('project_id', $projectId);
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($term = $request->query('search')) {
            $query->search($term); // scope có sẵn trong model
        }

        $participants = $query->orderBy($sort, $direction)
            ->paginate($request->integer('per_page', 15));

        return ParticipantResource::collection($participants);
    }

    public function show(Participant $participant)
    {
        return new ParticipantResource($participant);
    }

    public function store(StoreParticipantRequest $request)
    {
        $participant = Participant::create($request->validated());

        return (new ParticipantResource($participant))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        $participant->update($request->validated());

        return new ParticipantResource($participant);
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return response()->json(null, 204);
    }
}
