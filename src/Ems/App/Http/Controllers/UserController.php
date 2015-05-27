<?php namespace Ems\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Ems\App\Managers\UserManager;
use Ems\App\Http\Requests\UserRequest;

use Cmsable\Http\Resource\ShowRequest;
use Cmsable\Http\Resource\CreateRequest;
use Cmsable\Http\Resource\EditRequest;
use Cmsable\Http\Resource\SaveRequest;
use Cmsable\Http\Resource\DeleteRequest;
use Ems\App\Http\Forms\UserForm;

class UserController extends Controller
{

    protected $manager;

    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
        $this->middleware('auth');
    }

    public function show(ShowRequest $request, $id)
    {
        return $this->manager->findOrFail($id);
    }

    public function index()
    {
        return $this->manager->getModel()->all();
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
        $this->manager->update($user, $request->casted());
        return redirect()->route('users.edit',[$id]);
    }

}