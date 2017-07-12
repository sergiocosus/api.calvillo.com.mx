<?php

namespace CalvilloComMx\Http\Controllers\Auth;

use CalvilloComMx\Core\User\SocialToken;
use CalvilloComMx\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookController extends Controller
{

    public function postLogin(Request $request, LaravelFacebookSdk $fb)
    {
        \Log::info($request->get('access_token'));
        $fb->setDefaultAccessToken($request->get('access_token'));
        $token =$fb->getDefaultAccessToken();

        if (! $token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauth_client = $fb->getOAuth2Client();

            $token = $oauth_client->getLongLivedAccessToken($token);

        }

        $fb->setDefaultAccessToken($token);

        if (!$socialToken = \Auth::user()->socialToken) {
            $socialToken = new SocialToken();
            $socialToken->user()->associate(\Auth::user());
        }

        $socialToken->facebook_access_token = $token;
        $socialToken->save();

        // Get basic info on the user from Facebook.
        try {
            $response = $fb->get('/me?fields=id,name,email');
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            return $this->error($e->getMessage());
        }

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebook_user = $response->getGraphUser();

        // Create the user if it does not exist or update the existing entry.
        // This will only work if you've added the SyncableGraphNodeTrait to your User model.

        // Log the user into Laravel
        $name = $facebook_user->getName();

        return $this->success(compact('name'));
    }

    public function getStatus()
    {
        $exist_token = \Auth::user()->socialToken && \Auth::user()->socialToken->facebook_access_token;

        return $this->success(compact('exist_token'));
    }
}
