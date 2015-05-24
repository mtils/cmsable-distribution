<?php namespace Ems\App\Cms\ControllerCreators;

use App;
use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Routing\ControllerCreatorInterface;
use Permit\Authentication\CredentialsBrokerInterface as CredentialsBroker;
use Cmsable\Mail\MailerInterface as Mailer;
use Permit\Authentication\AuthenticatorInterface as Auth;
use Cmsable\PageType\CurrentPageTypeProviderInterface as PageTypes;
use Illuminate\Routing\Router;
use Cmsable\Html\Breadcrumbs\Factory as BreadcrumbFactory;

class PasswordResetControllerCreator implements ControllerCreatorInterface
{

    protected $pageTypes;

    protected $router;

    protected $breadcrumbs;

    public function __construct(PageTypes $pageTypes, Router $router,
                                BreadcrumbFactory $breadcrumbs)
    {
        $this->pageTypes = $pageTypes;
        $this->router = $router;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $controllerName The classname of the routed controller
     * @param \Cmsable\Model\SiteTreeNodeInterface $page (optional) Null if no match
     * @return \Illuminate\Routing\Controller
     **/
    public function createController($name, SiteTreeNodeInterface $page=NULL){

        switch ($this->router->currentRouteName()) {

            case 'password.create-email':
            case 'password.send-email':
                return $this->createControllerForEmailAction($name, $page);

            case 'password.create-reset':
            case 'password.store-reset':
                return $this->createControllerForResetAction($name, $page);
        }


        return App::make($name);

    }

    protected function createControllerForEmailAction($name, $page)
    {

        $broker = $this->makeBroker();

        $mailer = $this->makeMailer();

        $this->configureMailer($mailer);

        $controller = App::make($name, [$broker, $mailer]);

        return $controller;

    }

    protected function createControllerForResetAction($name, $page)
    {

        $controller = App::make($name);

        if (!$config = $this->pageTypes->currentConfig()) {
            return $controller;
        }

        if (!$title = $config->resetpage_title) {
            return $controller;
        }

        $content = $config->resetpage_content;

        $this->breadcrumbs->register('password.create-reset', function($breadcrumbs, $code=null) use ($title, $content) {
            $breadcrumbs->add($title, null, $title, $content);
        });


        return App::make($name);

    }


    protected function configureMailer(Mailer $mailer)
    {

        if (!$currentConfig = $this->pageTypes->currentConfig()) {
            return;
        }

        if ($currentConfig->resetmail_subject) {
            $mailer->overwrite('subject', $currentConfig->resetmail_subject);
        }

        if ($currentConfig->resetmail_body) {
            $mailer->overwrite('body', $currentConfig->resetmail_body);
        }
    }

    protected function makeBroker()
    {
        return App::make('Permit\Authentication\CredentialsBrokerInterface');
    }

    protected function makeMailer()
    {
        return App::make('Cmsable\Mail\MailerInterface');
    }

}