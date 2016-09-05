<?php namespace Ems\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Container\Container;
use App\Http\Controllers\Controller;
use Ems\App\Repositories\MailConfigRepository;
use Cmsable\Http\Resource\CleanedRequest;
use Ems\App\Helpers\ProvidesTexts;

use Cmsable\Resource\Contracts\Mapper;
use Cmsable\View\Contracts\Notifier;
use Versatile\Query\Builder;
use Ems\App\Http\Forms\SystemMailConfigForm;
use Ems\App\Contracts\Mail\EmailingPreviewer;
use Ems\Contracts\Mail\MailConfig;
use Versatile\Search\Contracts\Search;
use Ems\Contracts\Mail\AddressExtractor;
use Ems\Contracts\Mail\Mailer;
use App\Group;
use Menu;
use URL;

class MailConfigController extends Controller
{

    use ProvidesTexts;

    protected $repository;

    /**
     * @var \Ems\Contracts\Mail\AddressExtractor
     **/
    protected $addressExtractor;

    protected $searchColumns = [
        'id', 'name'
    ];

    /**
     * @var int
     **/
    protected $redirectToTree = 0;

    public function __construct(MailConfigRepository $repository, Notifier $notifier, AddressExtractor $addressExtractor)
    {
        $this->repository = $repository;
        $this->notifier = $notifier;
        $this->addressExtractor = $addressExtractor;

        $this->middleware('auth');
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    public function index(Container $app, Request $request)
    {

        if ($this->redirectToTree) {
            return $app->call([$this,'edit'], [$this->redirectToTree]);
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
        return redirect()->route('mail-configurations.edit',[$group->getKey()]);
    }

    public function edit(SystemMailConfigForm $form, $id)
    {
        $config = $this->repository->find($id);
        $form->setModel($config);
        return view('mail-configurations.edit-tree', [
            'model'     => $config,
            'editUrl'   => $this->getEditUrl(),
            'form'      => $form
        ]);
    }

    public function update(CleanedRequest $request, $id)
    {
        $user = $this->repository->find($id);
        $this->repository->update($user, $request->cleaned());
        $this->notifier->success($this->routeMessage('updated'));
        return redirect()->route('mail-configurations.edit',[$id]);
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

    public function previewMailing(EmailingPreviewer $previews, $id)
    {
        $config = $this->repository->find($id);

        $search = $previews->recipientSearch($config);

        $paginator = $this->getPreviewPaginator($search);

        $message = $this->buildPreviewMessage($previews, $config, $paginator);

        $vars = [
            'paginator' => $paginator,
            'message'   => $message,
            'config'    => $config,
            'previewRecipient' => $this->getPreviewMailRecipient()
        ];

        return view('mail-configurations.preview-emailing', $vars);

    }

    public function sendPreviewMailing(Mailer $mailer, EmailingPreviewer $previews, Request $request, $configId, $indexInResult)
    {
        // TODO: Dirty hack to manipulate the paginators current page
        $request->request->set('page', $indexInResult);

        $config = $this->repository->find($configId);

        $search = $previews->recipientSearch($config);

        $paginator = $this->getPreviewPaginator($search);

        $message = $this->buildPreviewMessage($previews, $config, $paginator);

        $message->clearRecipientHeaders();

        $message->to($this->getPreviewMailRecipient());

        $result = $mailer->sendMessage($message);

        if (count($result)) {
            return 'OK';
        }

        throw new \RuntimeException("Mail could not be sent to " . $this->getPreviewMailRecipient());
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

    protected function getPreviewPaginator(Search $search)
    {
        return $search->paginate([], 1);
    }

    protected function buildPreviewMessage(EmailingPreviewer $previews, MailConfig $config, $paginator)
    {
        $recipient = $paginator->isEmpty() ? null : $paginator[0];
        return $previews->previewMessage($config, $recipient);
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

    protected function getPreviewMailRecipient()
    {
        return $this->addressExtractor->email(\Auth::user());
    }

}
