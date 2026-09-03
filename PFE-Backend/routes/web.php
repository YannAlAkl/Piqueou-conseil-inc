<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\adminController;
use App\Http\Controllers\Admin\AnalaystController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\clientController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Analyst\AnalystDashboardController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\QuestionnaireController;
use App\Http\Controllers\Analyst\QuestionnaireController as AnalystQuestionnaireController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->get('/dashboard', function () {

    $user = auth()?->user();

    if ($user?->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user?->hasRole('analyst')) {
        return redirect()->route('analyst.dashboard');
    }

    if ($user?->hasRole('client')) {
        return redirect()->route('client.dashboard');
    }

    abort(403);

})->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [adminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/analysts', [AnalaystController::class, 'index'])
            ->name('analyst.index');

        Route::get('/analysts/create', [AnalaystController::class, 'create'])
            ->name('analyst.create');

        Route::post('/analysts', [AnalaystController::class, 'store'])
            ->name('analyst.store');

        Route::get('/analysts/{id}', [AnalaystController::class, 'show'])
            ->name('analyst.show');

        Route::get('/analysts/{id}/edit', [AnalaystController::class, 'edit'])
            ->name('analyst.edit');

        Route::put('/analysts/{id}', [AnalaystController::class, 'update'])
            ->name('analyst.update');

        Route::post('/analysts/{id}/verify', [AnalaystController::class, 'verify'])
            ->name('analyst.verify');

        Route::delete('/analysts/{id}', [AnalaystController::class, 'destroy'])
            ->name('analyst.destroy');

        Route::get('/admins', [AdminAccountController::class, 'index'])
            ->name('admin.index');

        Route::get('/admins/create', [AdminAccountController::class, 'create'])
            ->name('admin.create');

        Route::post('/admins', [AdminAccountController::class, 'store'])
            ->name('admin.store');

        Route::get('/admins/{id}', [AdminAccountController::class, 'show'])
            ->name('admin.show');

        Route::get('/admins/{id}/edit', [AdminAccountController::class, 'edit'])
            ->name('admin.edit');

        Route::put('/admins/{id}', [AdminAccountController::class, 'update'])
            ->name('admin.update');

        Route::post('/admins/{id}/verify', [AdminAccountController::class, 'verify'])
            ->name('admin.verify');

        Route::delete('/admins/{id}', [AdminAccountController::class, 'destroy'])
            ->name('admin.destroy');


        Route::get('/clients', [clientController::class, 'index'])
            ->name('client.index');

        Route::get('/clients/create', [clientController::class, 'create'])
            ->name('client.create');

        Route::post('/clients', [clientController::class, 'store'])
            ->name('client.store');

        Route::get('/clients/{id}', [clientController::class, 'show'])
            ->name('client.show');

        Route::get('/clients/{id}/edit', [clientController::class, 'edit'])
            ->name('client.edit');

        Route::put('/clients/{id}', [clientController::class, 'update'])
            ->name('client.update');

        Route::post('/clients/{id}/activate', [clientController::class, 'activate'])
            ->name('client.activate');

        Route::delete('/clients/{id}', [clientController::class, 'destroy'])
            ->name('client.destroy');

        Route::get('/submissions', [SubmissionController::class, 'index'])
            ->name('submission.index');

        Route::post('/submissions/{id}/assign', [SubmissionController::class, 'assign'])
            ->name('submission.assign');

        Route::get('/newsletters', [NewsletterController::class, 'index'])
            ->name('newsletter.index');

        Route::post('/newsletters/retrieve', [NewsletterController::class, 'retrieve'])
            ->name('newsletter.retrieve');

        Route::get('/newsletters/{id}/edit', [NewsletterController::class, 'edit'])
            ->name('newsletter.edit');

        Route::put('/newsletters/{id}/update', [NewsletterController::class, 'update'])
            ->name('newsletter.update');

        Route::delete('/newsletters/{id}', [NewsletterController::class, 'destroy'])
            ->name('newsletter.destroy');

        Route::post('/newsletters/send', [NewsletterController::class, 'sendNewsletter'])
            ->name('newsletter.send');

        Route::get('/users/{id}/edit', [adminController::class, 'edit'])
            ->name('user.edit');

        Route::put('/users/{id}', [adminController::class, 'update'])
            ->name('user.update');

        Route::delete('/users/{id}', [adminController::class, 'destroy'])
            ->name('user.destroy');

        Route::get('/submissions/{id}', [SubmissionController::class, 'viewQuestionnaire'])
            ->name('submission.show');

        Route::get('/questionnaires', [adminController::class, 'viewQuestionnaires'])
            ->name('questionnaire.index');

        Route::get('/questionnaires/{id}', [adminController::class, 'showQuestionnaire'])
            ->name('questionnaire.show');

    });


/*
|--------------------------------------------------------------------------
| ANALYST
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:analyst'])
    ->prefix('analyst')
    ->name('analyst.')
    ->group(function () {

        Route::get('/dashboard', [AnalystDashboardController::class, 'dashboard'])
            ->name('dashboard');

        Route::get(
            '/questionnaires',
            [AnalystQuestionnaireController::class, 'index']
        )->name('questionnaire.index');

        Route::get(
            '/questionnaires/{id}',
            [AnalystQuestionnaireController::class, 'show']
        )->name('questionnaire.show');

        Route::post(
            '/questionnaires/{id}',
            [AnalystQuestionnaireController::class, 'store']
        )->name('questionnaire.store');

    });
/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get(
            '/dashboard',
            [ClientDashboardController::class, 'dashboard']
        )->name('dashboard');

        Route::get(
            '/questionnaires',
            [QuestionnaireController::class, 'index']
        )->name('questionnaire.index');

        Route::get(
            '/questionnaires/{id}',
            [QuestionnaireController::class, 'show']
        )->name('questionnaire.show');


        Route::post(
            '/questionnaires/{id}',
            [QuestionnaireController::class, 'submit']
        )->name('questionnaire.submit');
    });
/*
|--------------------------------------------------------------------------
| ACTIVATION
|--------------------------------------------------------------------------
*/

Route::middleware(['signed'])
    ->get(
        '/activate/{id}/{role}',
        [adminController::class, 'activate']
    )
    ->name('activate.account');
/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


require __DIR__ . '/auth.php';




