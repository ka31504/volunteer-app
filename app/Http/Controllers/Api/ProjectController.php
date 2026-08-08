<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Đồng nhất với web (bản 3_16): mặc định sort theo id desc
    private const ALLOWED_SORTS = ['id', 'name', 'status', 'start_date', 'target_amount'];

    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), self::ALLOWED_SORTS, true)
            ? $request->query('sort')
            : 'id';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $query = Project::query();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $projects = $query->orderBy($sort, $direction)
            ->paginate($request->integer('per_page', 15));

        return ProjectResource::collection($projects);
    }

    public function show(Project $project)
    {
        // Eager-load giống bản nâng cấp 3_15 bên web (donations + participants)
        $project->load([
            'donations' => fn ($q) => $q->latest('donated_at')->limit(10),
            'participants' => fn ($q) => $q->latest('joined_at')->limit(10),
        ]);

        return new ProjectResource($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(null, 204);
    }
}
