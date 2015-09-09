<?php namespace Ems\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ems\App\Repositories\GroupRepository;
use Cmsable\Http\Resource\CleanedRequest;
use Ems\App\Helpers\ProvidesTexts;

use Cmsable\Resource\Contracts\Mapper;
use Cmsable\View\Contracts\Notifier;
use Versatile\Query\Builder;
use App\Group;

class GroupController extends Controller
{

    use ProvidesTexts;

    protected $repository;

    protected $searchColumns = [
        'id', 'name'
    ];

    public function __construct(GroupRepository $repository, Notifier $notifier)
    {
        $this->repository = $repository;
        $this->notifier = $notifier;

        $this->middleware('auth');
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function index(Request $request)
    {
        $search = new Builder(new Group());

        $orderBy = $request->get('sort') ?: 'created_at';
        $desc = $request->get('order') ?: 'desc';

        $search->withColumn($this->searchColumns)
               ->orderBy($orderBy, $desc);

        foreach ($this->searchColumns as $col) {
            if ($request->has($col)) {
                $search->where($col, 'like', '%'.$request->input($col).'%');
            }
        }

        if ($request->has('groups__ids')) {
            $search->whereIn('groups.id', $request->input('groups__ids'));
        }



        return view('users.index')->withSearch($search);
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(CleanedRequest $request)
    {
        $group = $this->repository->store($request->cleaned());
        $this->notifier->success($this->routeMessage('stored'));
        return redirect()->route('groups.edit',[$group->getKey()]);
    }

    public function edit($id)
    {
        $user = $this->repository->find($id);
        return view('groups.edit')->withModel($user);
    }

    public function update(CleanedRequest $request, $id)
    {
        $user = $this->repository->find($id);
        $this->repository->update($user, $request->cleaned());
        $this->notifier->success($this->routeMessage('updated'));
        return redirect()->route('groups.edit',[$id]);
    }

    public function destroy($id)
    {

        if(!$user = $this->repository->find($id)) {
            $this->notifier->error($this->routeMessage('not-found'));
            return 'ERROR';
        }

        $this->repository->delete($user);

        $this->notifier->success($this->routeMessage('destroyed'));

        return 'OK';
    }

}