<?php namespace Ems\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ems\App\Repositories\UserRepository;
use Cmsable\Http\Resource\CleanedRequest;

use Ems\App\Http\Forms\UserForm;
use Cmsable\Resource\Contracts\Mapper;

class UserController extends Controller
{

    protected $repository;

    protected $mapper;

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
        return $this->repository->getModel()->all();
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