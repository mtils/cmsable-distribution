<?php namespace Ems\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ems\App\Repositories\UserRepository;
use Cmsable\Http\Resource\CleanedRequest;

use Cmsable\Resource\Contracts\Mapper;
use Versatile\Query\Builder;
use App\User;

class UserController extends Controller
{

    protected $repository;

    protected $mapper;

    protected $searchColumns = [
        'id', 'email', 'last_login'
    ];

    public function __construct(UserRepository $repository, Mapper $mapper)
    {
        $this->repository = $repository;
        $this->mapper = $mapper;
        $this->middleware('auth');
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function index(Request $request)
    {
        $search = new Builder(new User());

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

    public function edit($id)
    {
        $user = $this->repository->find($id);
        return view('users.edit')->withModel($user);
    }

    public function update(CleanedRequest $request, $id)
    {
        $user = $this->repository->find($id);
        $this->repository->update($user, $request->cleaned());
        return redirect()->route('users.edit',[$id]);
    }

}