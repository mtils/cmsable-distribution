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

class UserController extends Controller
{

    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
    }

    public function show(ShowRequest $request, $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function index()
    {
        return $this->repository->getModel()->all();
    }

    public function edit(EditRequest $request, UserForm $form,  $id)
    {
        $user = $request->findModel($id);
        $form->fillByArray($user->toArray());
        $form->fillByArray(['ids'=>$user->groups()->getRelatedIds()], 'groups');
        return view('users.edit')->withForm($form)->withResource($user);
    }

    public function update(UserRequest $request, $id)
    {
        $user = $request->findModel($id);
        $this->repository->update($user, $request->casted());
        return redirect()->route('users.edit',[$id]);
    }

}