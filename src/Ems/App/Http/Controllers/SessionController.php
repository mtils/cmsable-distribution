<?php namespace Ems\App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Notification;
use Ems\App\Http\Forms\LoginForm;
use Redirect;
use Ems\App\Helpers\ProvidesTexts;
use Cmsable\Http\Resource\CleanedRequest;

use URL;
use Session;
use CMS;

// Two or three exceptions

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Permit\CurrentUser\DualContainerInterface;
use Permit\Authentication\AuthenticatorInterface as Auth;
use Permit\User\ProviderInterface as UserProvider;
use Permit\Doorkeeper\UserBannedException;
use Permit\Authentication\Exception\CredentialsNotFoundException;
use Permit\Authentication\Exception\CredentialsInvalidException;
use Permit\Registration\UserNotActivatedException;
use Permit\Throttle\UserSuspendedException;
use Permit\CurrentUser\LoginAsRequiresActualUserException;
use Permit\CurrentUser\LoginAsSameUserException;
use Permit\CurrentUser\LoginAsSuperUserException;
use Permit\CurrentUser\LoginAsSpecialUserException;
use Permit\CurrentUser\UnsufficientPermissionsException;
use Permit\CurrentUser\LessPermissionsThanStackedException;
use Cmsable\View\Contracts\Notifier;

class SessionController extends Controller
{

    use ProvidesTexts;

    protected $auth;

    protected $userProvider;

    protected $dualAuth;

    protected $notifier;

    protected $loginTemplate = 'session.create';

    protected $intendedFallbackUrl;

    protected $logoutUrl;

    protected $loginAsUrl;

    public function __construct(Auth $auth, UserProvider $userProvider,
                                DualContainerInterface $dualAuth,
                                Notifier $notifier)
    {

        $this->auth = $auth;
        $this->userProvider = $userProvider;
        $this->dualAuth = $dualAuth;
        $this->notifier = $notifier;

    }

    public function index()
    {
        return $this->create();
    }

    public function create()
    {
        return view($this->loginTemplate);
    }

    public function store(CleanedRequest $request)
    {

        try {

            $user = $this->auth->authenticate($request->cleaned(), false);
            return Redirect::intended($this->intendedFallbackUrl());

        } catch(CredentialsInvalidException $e) {

            $this->notifier->warning($this->routeMessage('credentials-invalid'));

        } catch(CredentialsNotFoundException $e) {

            $this->notifier->warning($this->routeMessage('credentials-not-found'));

        } catch(UserNotActivatedException $e) {

            $this->notifier->warning($this->routeMessage('user-not-activated'));

        } catch(UserSuspendedException $e) {

            $this->notifier->warning($this->routeMessage('user-suspended'));

        } catch(UserBannedException $e) {

            $this->notifier->error($this->routeMessage('user-banned'));

        }

        return Redirect::route('session.create')->withInput();

    }

    public function destroy()
    {

        // If the user is logged in as a different user, we fall back to its
        // real account

        if (!$this->dualAuth->isActual()) {

            // Logout the stacked user
            return $this->logoutAs();

        }

        $user = $this->dualAuth->user();

        $this->auth->logout();

        $this->notifier->info($this->routeMessage('loggedout'));

        return Redirect::to($this->logoutUrl());

    }

    public function loginAs($id)
    {
        return $this->update($id);
    }

    public function update($id)
    {

        $previousUrl = URL::previous();

        try {

            $user = $this->userProvider->retrieveByAuthId((int)$id);

            $this->dualAuth->setStackedUser($user);

            if ($previousUrl) {
                Session::set('login-as.referer', $previousUrl);
            }

            $this->notifier->success($this->routeMessage('loggedin-as', ['email'=>$user->getEmailForPasswordReset()]));

            return Redirect::to($this->loginAsUrl());

        } catch(ModelNotFoundException $e) {

            $message = $this->routeMessage('user-not-found');

        } catch(LoginAsRequiresActualUserException $e) {

            $message = $this->routeMessage('not-authenticated');

        } catch(LoginAsSameUserException $e) {

            $message = $this->routeMessage('login-as-same');

        } catch(LoginAsSuperUserException $e) {

            $message = $this->routeMessage('login-as-admin');

        } catch(LoginAsSpecialUserException $e) {

            $message = $this->routeMessage('login-as-system');

        } catch(UnsufficientPermissionsException $e) {

            $message = $this->routeMessage('permission-denied');

        } catch(LessPermissionsThanStackedException $e) {

            $message = $this->routeMessage('less-permissions');

        }


        $this->notifier->error($message);

        $url = $previousUrl ? $previousUrl : '/session/create';

        return Redirect::to($url);

    }

    public function intendedFallbackUrl(callable $url=null)
    {

        if ($url) {
            $this->intendedFallbackUrl = $url;
            return $this;
        }

        if ($this->intendedFallbackUrl) {
            return call_user_func($this->intendedFallbackUrl);
        }

        return Url::to('/');

    }

    public function logoutUrl(callable $url=null)
    {

        if ($url) {
            $this->logoutUrl = $url;
            return $this;
        }

        if ($this->logoutUrl) {
            return call_user_func($this->logoutUrl);
        }

        return Url::to('/');

    }

    public function loginAsUrl(callable $url=null)
    {

        if ($url) {
            $this->loginAsUrl = $url;
            return $this;
        }

        if ($this->loginAsUrl) {
            return call_user_func($this->loginAsUrl);
        }

        return Url::scope('default')->to('/');

    }

    protected function logoutAs()
    {

        $stackedUser = $this->dualAuth->stackedUser();
        $this->dualAuth->reset(DualContainerInterface::STACKED);

        if ($actualUser = $this->dualAuth->actualUser()) {
            $this->notifier->info($this->routeMessage('loggedout-as', ['email'=>$actualUser->getEmailForPasswordReset()]));
        }

        if (Session::has('login-as.referer')) {
            $url = Session::get('login-as.referer');
            Session::forget('login-as.referer');
        } else {
            $url = '/';
        }

        return Redirect::to($url);
    }
}
