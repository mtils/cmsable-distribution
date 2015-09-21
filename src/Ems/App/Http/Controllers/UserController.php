<?php namespace Ems\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ems\App\Repositories\UserRepository;
use Cmsable\Http\Resource\CleanedRequest;
use Cmsable\Http\Resource\SearchRequest;
use Permit\Registration\RegistrarInterface as Registrar;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ems\App\Helpers\ProvidesTexts;

use Cmsable\Resource\Contracts\Mapper;
use Versatile\Query\Builder;
use App\User;
use Cmsable\View\Contracts\Notifier;
use Cmsable\Resource\ResourceBus;

class UserController extends Controller
{

    use ProvidesTexts;

    use ResourceBus;

    protected $repository;

    protected $registrar;

    protected $notifier;

    protected $searchColumns = [
        'id', 'email', 'created_at', 'activated_at', 'last_login'
    ];

    public function __construct(UserRepository $repository, Registrar $registrar,
                                Notifier $notifier)
    {
        $this->repository = $repository;
        $this->registrar = $registrar;
        $this->notifier = $notifier;
//         $this->middleware('auth');
    }

    public function show($id)
    {
        return view('users.show')->withModel($this->repository->find($id));
    }

    public function index(SearchRequest $request)
    {

        $defaults = [
            'sort'  =>'created_at',
            'order' => 'desc'
        ];

//         if ($request->has('groups__ids')) {
//             $search->whereIn('groups.id', $request->input('groups__ids'));
//         }

        return view('users.index')->withSearch($request->search($defaults));

    }

    public function create()
    {
        return view('users.create');
    }

    public function store(CleanedRequest $request)
    {
        $user = $this->registrar->register($request->cleaned(), $activate=true);
        $this->notifier->success($this->routeMessage('stored'));
        return redirect()->route('users.index');
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
        $this->notifier->success($this->routeMessage('updated'));
        return redirect()->route('users.edit',[$id]);
    }

    public function activate($id)
    {

        if(!$user = $this->repository->find($id)) {
            $this->notifier->error($this->routeMessage('not-found'));
            return 'ERROR';
        }

        $this->registrar->activate($user);

        $this->notifier->success($this->routeMessage('activated'));

        return 'OK';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
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