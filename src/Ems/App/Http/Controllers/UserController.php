<?php namespace Ems\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ems\App\Repositories\UserRepository;
use Ems\App\Http\Requests\UserRequest;

use Cmsable\Http\Resource\ShowRequest;
use Cmsable\Http\Resource\CreateRequest;
use Cmsable\Http\Resource\EditRequest;
use Cmsable\Http\Resource\SaveRequest;
use Cmsable\Http\Resource\DeleteRequest;
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
        $this->mapper->mapFormClass('users', 'Ems\App\Http\Forms\UserForm');
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
//         $form->fillByArray($user->toArray());
//         $form->fillByArray(['ids'=>$user->groups()->getRelatedIds()], 'groups');
        return view('users.edit')->withModel($user);
        return view('users.edit')->withForm($form)->withResource($user);
    }

    public function update(UserRequest $request, $id)
    {
        $user = $request->findModel($id);
        $this->repository->update($user, $request->casted());
        return redirect()->route('users.edit',[$id]);
    }

}