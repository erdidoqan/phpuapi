<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group( function () {
    Route::middleware('auth:api')->group(function () {
        Route::prefix('admin')->namespace('Admin')->group(function () {
            Route::apiResource('lessons', 'LessonController');
            Route::apiResource('episodes', 'EpisodeController');
            Route::apiResource('skills', 'SkillController');
            Route::apiResource('tags', 'TagController');
            Route::apiResource('difficulties', 'DifficultyController');
            Route::apiResource('students', 'StudentController');
            Route::apiResource('contacts', 'ContactController');
            Route::apiResource('newsletters', 'NewsletterController');
            Route::apiResource('reports', 'ReportController');
            Route::apiResource('subscriptions', 'SubscriptionController');
            Route::apiResource('subscription-types', 'SubscriptionTypeController');
            Route::apiResource('post-categories', 'PostCategoryController');
            Route::apiResource('posts', 'PostController');
            Route::apiResource('thread-categories', 'ThreadCategoryController');
            Route::apiResource('threads', 'ThreadController');
            Route::apiResource('users', 'UserController');
            Route::apiResource('coupons', 'CouponController');
            Route::apiResource('subscription-payments', 'SubscriptionPaymentController');
            Route::apiResource('comments', 'CommentController');
            Route::get('roles', 'RoleController@index');
            Route::post('images', 'ImageController@store');
            Route::post('videos', 'VideoController@store');
            Route::patch('videos/{episode_id}', 'VideoController@update');
            Route::get('dashboard', 'DashboardController@index');
            Route::post('emails', 'EmailController@send');
        });

        Route::middleware('role:admin|instructor|editor')->group(function () {
            Route::get('admin/auth/user', 'AuthController@user');
        });

        Route::get('auth/user', 'AuthController@user');
        Route::patch('auth/update', 'AuthController@update');
        Route::post('auth/logout', 'AuthController@logout');

        Route::post('messages', 'MessageController@store');

        Route::get('conversations', 'ConversationController@index');
        Route::get('conversations/{id}', 'ConversationController@show');

        Route::post('subscriptions', 'SubscriptionController@store');
        Route::post('credit-cards', 'CreditCardController@store');
        Route::patch('subscriptions', 'SubscriptionController@update');
        Route::delete('subscriptions', 'SubscriptionController@destroy');

        Route::post('episodes/{episode_id}/comments', 'EpisodeCommentController@store');
        Route::apiResource('watched-episodes', 'WatchedEpisodeController')->only(['store', 'destroy']);

        Route::apiResource('threads', 'ThreadController')->only(['store', 'update']);
        Route::post('threads/{thread_id}/comments', 'ThreadCommentController@store');

        Route::post('posts/{post_id}/comments', 'PostCommentController@store');

        Route::patch('comments/{comment_id}', 'CommentController@update');

        Route::post('likes', 'LikeController@store');
        Route::delete('likes', 'LikeController@destroy');

        Route::post('watchlists', 'WatchlistController@store');
        Route::delete('watchlists/{lesson_id}', 'WatchlistController@destroy');

        Route::post('notifylists', 'NotifylistController@store');
        Route::delete('notifylists/{lesson_id}', 'NotifylistController@destroy');

        Route::post('votes', 'VoteController@store');

        Route::delete('notifications/{id}', 'NotificationController@destroy');

        Route::get('coupons/{code}', 'CouponController@show');
    });

    Route::post('auth/login', 'AuthController@login');

    Route::apiResource('lessons', 'LessonController')->only(['index', 'show']);
    Route::apiResource('skills', 'SkillController')->only(['index', 'show']);
    Route::apiResource('tags', 'TagController')->only(['index', 'show']);
    Route::apiResource('difficulties', 'DifficultyController')->only(['index', 'show']);
    Route::apiResource('students', 'StudentController')->only(['index']);

    Route::apiResource('episodes', 'EpisodeController')->only(['index', 'show']);
    Route::get('episodes/{episode_id}/comments', 'EpisodeCommentController@index');

    Route::post('newsletters', 'NewsletterController@store');
    Route::post('contacts', 'ContactController@store');

    Route::get('search', 'SearchController@search');

    Route::get('search-threads', 'SearchThreadController@search');
    Route::get('thread-categories', 'ThreadCategoryController@index');
    Route::get('threads', 'ThreadController@index');
    Route::get('threads/{thread_id}/comments', 'ThreadCommentController@index');

    Route::get('search-posts', 'SearchPostController@search');
    Route::get('post-categories', 'PostCategoryController@index');
    Route::get('posts', 'PostController@index');
    Route::get('posts/{post_id}/comments', 'PostCommentController@index');

    Route::get('subscription-types', 'SubscriptionTypeController@index');

    Route::post('page-views', 'PageViewController@store');

    Route::post('reports', 'ReportController@store');

    Route::post('users', 'UserController@store');
});

Route::get('sitemap', 'Api\SitemapController@index');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
