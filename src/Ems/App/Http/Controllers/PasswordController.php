<?php namespace Ems\App\Http\Controllers;

use DateTime;
use URL;
use Permit\Authentication\CredentialsBrokerInterface as CredentialsBroker;
use FormObject\Validator\ValidationException;
use Permit\User\UserNotFoundException;
use Cmsable\Mail\MailerInterface as Mailer;
use Permit\Token\TokenExpiredException;
use Permit\Token\TokenCollisionException;
use Permit\Token\TokenException;
use Permit\Authentication\AuthenticatorInterface as Auth;
use App\Http\Controllers\Controller;
use Ems\App\Helpers\ProvidesTexts;
use Cmsable\Http\Resource\CleanedRequest;
use Cmsable\View\Contracts\Notifier;


class PasswordController extends Controller
{

    use ProvidesTexts;

    public $createEmailView = 'passwords.email';

    public $sentEmailView = 'passwords.email';

    public $createResetView = 'passwords.create-reset';

    /**
     * @var callable
     **/
    public $storeRedirectProvider;

    /**
     * @var \Permit\Authentication\CredentialsBrokerInterface
     **/
    protected $broker;

    /**
     * @var \Cmsable\Mail\MailerInterface
     **/
    protected $mailer;

    /**
     * @var \Permit\Authentication\AuthenticatorInterface
     **/
    protected $auth;

    /**
     * @var \Cmsable\View\Contracts\Notifier
     **/
    protected $notifier;

    /**
     * @param \Permit\Authentication\CredentialsBrokerInterface $broker
     * @param \Cmsable\Mail\MailerInterface $mailer
     * @param \Permit\Authentication\AuthenticatorInterface $auth
     **/
    public function __construct(CredentialsBroker $broker, Mailer $mailer,
                                Auth $auth, Notifier $notifier)
    {
        $this->broker = $broker;
        $this->mailer = $mailer;
        $this->auth = $auth;
        $this->notifier = $notifier;
        $this->storeRedirectProvider = function($user) {
            return redirect()->to('/');
        };
    }

    /**
     * Display the form to reset the password
     *
     * @return Response
     */
    public function createEmail()
    {
        return view($this->createEmailView);
    }

    /**
     * Processes submit of createEmail form, sends the reset email
     *
     * @return Response
     */
    public function sendEmail(CleanedRequest $request)
    {

        try {

            $this->broker->reserveReset($request->cleaned(), function($user, $token) {

                $data = [
                    'subject' => $this->routeMailText('subject'),
                    'body'    => $this->routeMailText('body'),
                    'link'    => URL::route('password.create-reset',[$token]),
                    'user'    => $user,
                    'token'   => $token
                ];

                $this->mailer->to($user->email)->send('emails.empty', $data);

            });

            $this->notifier->success($this->routeMessage('reset-sent'));

            return redirect()->route('password.create-email');

        } catch (UserNotFoundException $e) {

            $message = $this->routeMessage('user-not-found');

        } catch (TokenCollisionException $e) {

            if ($e->validUntil instanceof DateTime) {
                $params = ['valid_until' => $this->formatDateTime($e->validUntil)];
                $message = $this->routeMessage('token-collision-until', $params);
            } else {
                $message = $this->routeMessage('token-collision-forever');
            }
        }

        $this->notifier->error($message);
        return redirect()->route('password.create-email');


    }

    /**
     * Display the form which allows to change the password after requesting
     *
     * @return Response
     */
    public function createReset($token)
    {
        return view($this->createResetView)->withToken($token);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function storeReset(CleanedRequest $request, $token)
    {

        try {

            $user = $this->broker->reset($request->withConfirmations()->cleaned());
            $this->auth->loginUser($user, false);

            $this->notifier->success($this->routeMessage('password-changed'));

            return call_user_func($this->storeRedirectProvider, $user);

        } catch (UserNotFoundException $e) {
            $message = $this->routeMessage('user-not-found');
            return redirect()->route('password.create-email');
        } catch (TokenExpiredException $e) {
            $params = ['expired_at' => $this->formatDateTime($e->expiryDate)];
            $message = $this->routeMessage('token-expired', $params);
        } catch (TokenException $e) {
            $message = $this->routeMessage('token-invalid');
        }

        $this->notifier->error($message);
        return redirect()->route('password.create-email');

    }

    /**
     * Pass a callable which deceides where the user will be redirected after
     * he stored his new password.
     *
     * @param callable $provider
     * @return self
     **/
    public function provideStoreRedirect(callable $provider)
    {
        $this->storeRedirectProvider = $provider;
        return $this;
    }

}
