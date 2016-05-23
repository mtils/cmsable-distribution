<?php namespace Ems\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ems\App\Repositories\MailConfigRepository;
use Cmsable\Http\Resource\CleanedRequest;
use Ems\App\Helpers\ProvidesTexts;

use Cmsable\Resource\Contracts\Mapper;
use Cmsable\View\Contracts\Notifier;
use Versatile\Query\Builder;
use App\Group;
use Menu;
use URL;

class MailConfigController extends Controller
{

    use ProvidesTexts;

    protected $repository;

    protected $searchColumns = [
        'id', 'name'
    ];

    /**
     * @var int
     **/
    protected $redirectToTree = 0;

    public function __construct(MailConfigRepository $repository, Notifier $notifier)
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

        if ($this->redirectToTree) {
            return $this->edit($this->redirectToTree);
        }

        $search = new Builder($this->repository->make());

        $orderBy = $request->get('sort') ?: 'created_at';
        $desc = $request->get('order') ?: 'desc';

        $search->withColumn($this->searchColumns)
               ->orderBy($orderBy, $desc);

        foreach ($this->searchColumns as $col) {
            if ($request->has($col)) {
                $search->where($col, 'like', '%'.$request->input($col).'%');
            }
        }


        return view('mail-configurations.index')->withSearch($search);
    }

    public function create()
    {
        return view('mail-configurations.create');
    }

    public function store(CleanedRequest $request)
    {
        $group = $this->repository->store($request->cleaned());
        $this->notifier->success($this->routeMessage('stored'));
        return redirect()->route('groups.edit',[$group->getKey()]);
    }

    public function edit($id)
    {
        $config = $this->repository->find($id);
        return view('mail-configurations.edit-tree', [
            'model'     => $config,
            'editUrl'   => $this->getEditUrl()
        ]);
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

    /**
     * Tell the controller to instead of return a list on index(), redirect it
     * mail-configurations/$rootId/edit
     *
     * @var int $rootId
     **/
    public function redirectIndexToTree($rootId)
    {
        $this->redirectToTree = $rootId;
        return $this;
    }

    /**
     * Get the tree redirection id
     *
     * @see self::redirectIndexToTree
     * @return int
     **/
    public function getIndexToTreeRedirectionId()
    {
        return $this->redirectToTree;
    }

    protected function getEditUrl()
    {
        if (!$current = Menu::current()) {
            return Url::route('mail-configurations.index');
        }
        if (trim($current->getPath(), '/ ') == '') {
            return Url::route('mail-configurations.index');
        }
        return URL::to($current);
    }

}