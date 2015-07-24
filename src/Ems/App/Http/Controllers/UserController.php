<?php namespace Ems\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ems\App\Repositories\UserRepository;
use Cmsable\Http\Resource\CleanedRequest;

use Ems\App\Http\Forms\UserForm;
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

    public function index()
    {
        $search = new Builder(new User());
        $search->withColumn($this->searchColumns);

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