<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Gigs\GigsController;
use App\Http\Controllers\Skill\SkillController;
use App\Http\Controllers\Navbar\NavbarController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\JobType\JobTypeController;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Analytics\AnalyticsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Configuration\FooterController;
use App\Http\Controllers\Onboarding\FreelancerController;
use App\Http\Controllers\Settings\NotificationController;
use App\Http\Controllers\Monitor\MonitorActivityController;
use App\Http\Controllers\Reviews\FreelancerReviewController;
use App\Http\Controllers\Testimonial\TestimonialsController;
use App\Http\Controllers\Transactions\TransactionController;
use App\Http\Controllers\Verification\VerificationController;
use App\Http\Controllers\Configuration\ConfigurationController;
use App\Http\Controllers\SupportTicket\SupportTicketController;
use App\Http\Controllers\Testimonial\TestimonialCardController;
use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\DiscoverTalent\DiscoverTalentController;
use App\Http\Controllers\BidPaymentConfig\BidPaymentConfigController;
use App\Http\Controllers\WhyChooseUs\WhyChooseUsController;
use App\Http\Controllers\YearsOfExperience\YearsOfExperienceController;



Route::get('/generate-swagger', function () {
    Artisan::call('l5-swagger:generate');
    return Artisan::output();
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['csrf-token' => csrf_token()]);
});


Route::prefix('user')->group(function(){

    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/admin-onboard', 'onboard_administrator');
        Route::post('/login', 'login');
        Route::put('/update_role', 'update_role');
        Route::get('/authenticated', 'redirectLogin')->name('login');
        Route::get('facebook', 'facebook_login');
        Route::get('facebook/callback', 'facebook_callback');
        Route::get('google_login', 'google_login');
        Route::get('google_callback', 'google_callback');
    });

    Route::controller(AccountController::class)->group(function () {
        Route::post('/forgot-password', 'forgot_password');
        Route::post('/reset-password/{token}', 'reset_password')->name('password.reset');


      });

  Route::middleware(['auth:sanctum', 'monitor_api_usage', 'audit_log'])->group(function () {
      Route::controller(AccountController::class)->group(function () {
        Route::get('/clients', 'getClients')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/freelancers', 'getAllUsers')->middleware(['2fa','is_email_verified','re_login', 'is_admin']);
        Route::get('/admins', 'getAllAdmins')->middleware(['2fa','is_email_verified','re_login', 'is_superuser']);
        Route::get('/freelancer/{userId}', 'getUserById')->middleware(['2fa','is_email_verified','re_login', 'is_admin']);
        Route::post('/upload-profile', 'upload_profile')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/freelancer-profile/{user_id}', 'getFreelancerProfile')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/freelancer-public-profile/{user_id}', 'getFreelancerPublicProfile')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/client/{clientId}', 'getClientById')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/freelancer_bids', 'getFreelancerBids')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/freelancer_bid', 'getFreelancerBid')->middleware(['2fa','is_email_verified','re_login']);
        Route::post('/create_update_portfolio', 'upsert_portfolio')->middleware(['2fa','is_email_verified','re_login']);
        Route::delete('/portfolio/delete/{id}', 'delete_portfolio')->middleware(['2fa','is_email_verified','re_login']);
        Route::post('/generate_chatId', 'generate_chatId')->middleware(['2fa','is_email_verified','re_login']);
        Route::put('/update-skills', 'updateSkills')->middleware(['2fa','is_email_verified','re_login', 'is_freelancer']);
        Route::put('/update-hourly-rate', 'updateHourlyRate')->middleware(['2fa','is_email_verified','re_login', 'is_freelancer']);
        Route::put('/update-short-bio', 'updateShortBio')->middleware(['2fa','is_email_verified','re_login', 'is_freelancer']);
        Route::put('/update-language', 'updateLanguage')->middleware(['2fa','is_email_verified','re_login', 'is_freelancer']);
        Route::put('/update-looking-for', 'updateLookingFor')->middleware(['2fa','is_email_verified','re_login', 'is_freelancer']);
        Route::put('/activate/{userId}', 'activateUser')->middleware(['2fa','is_email_verified','re_login', 'is_superuser']);
        Route::put('/deactivate/{userId}', 'deactivateUser')->middleware(['2fa','is_email_verified','re_login', 'is_superuser']);
        Route::delete('/delete_user/{userId}', 'deleteUser')->middleware(['2fa','is_email_verified','re_login', 'is_superuser']);
        Route::get('/track-profile', 'trackProfile')->middleware(['2fa','is_email_verified','re_login', 'is_freelancer']);
        Route::put('/update-details', 'updateUserDetail')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/current_user', 'getCurrentUser')->middleware(['2fa','is_email_verified','re_login']);
        Route::put('/change_password', 'changePassword')->middleware(['2fa','is_email_verified','re_login']);
        Route::get('/user_check_keys', 'checkUser');


      });

      Route::controller(AuthenticationController::class)->group(function () {
        Route::post('/2fa/enable2fa', 'enable2fa')->name('2fa.enable');
        Route::post('/2fa/verify2fa','verify2fa')->name('2fa.verify');
        Route::get('/2fa/prompt', 'prompt')->name('2fa.prompt');
        Route::post('/2fa/disable2fa', 'disable2fa')->name('2fa.disable');
        Route::post('/2fa/verify','verify')->name('2fa.verify.secondlogin');
        Route::post('/2fa/resend-code', 'resendCode')->name('resendCode');
        Route::post('/logout', 'logout')->name('logout');

        Route::get('/email/prompt', 'prompt_email')->name('email.prompt');
        Route::post('/email/verify','verify_email')->name('verify.email');
        Route::post('/email/resend-code', 'resendEmailCode')->name('resend.code');
        Route::post('/create-user', 'create_user')->middleware(['is_superuser','2fa','is_email_verified','re_login',]);

      });


  });
});



Route::prefix('file')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(FileController::class)->group(function () {
         Route::post('/upload', 'uploadFile');
         Route::delete('/delete', 'deleteFile');
         Route::post('/download-file',  'downloadFile');

        });

    });

});

Route::prefix('chat')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_client_verified','monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(ChatController::class)->group(function () {
         Route::post('/initiate-chat', 'initiate_chat');
         Route::post('/send-message', 'send_message');
         Route::post('/send-attachment', 'send_attachments');
         Route::get('/messages/{chat_id}', 'getAllMessages');
         Route::get('/latest_chat', 'getLatestChatMessages');
         Route::get('/my-chats', 'getMyChats');
         Route::delete('/delete/{message_id}', 'delete_message');
         Route::delete('/delete-chat/{chat_id}', 'delete_chat');
        });

    });

});

Route::prefix('category')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(CategoryController::class)->group(function () {
         Route::post('/upsert-category', 'upsertCategory')->middleware(["is_admin"]);
         Route::delete('/delete/{id}', 'deleteCategory')->middleware(["is_admin"]);
         Route::put('/activate/{id}', 'activateCategory')->middleware(["is_admin"]);
         Route::put('/deactivate/{id}', 'deactivateCategory')->middleware(["is_admin"]);
         Route::get('/get_category/{id}', 'getCategoryById');
         Route::get('/categories', 'getAllCategories');
         Route::get('/get_categories_admin', 'getAllCategoriesAdmin')->middleware(["is_admin"]);
        });

    });

});

Route::prefix('skill')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(SkillController::class)->group(function () {
         Route::post('/upsert-skill', 'upsertSkill')->middleware(["is_admin"]);
         Route::delete('/delete/{id}', 'deleteSkill')->middleware(["is_admin"]);
         Route::put('/activate/{id}', 'activateSkill')->middleware(["is_admin"]);
         Route::put('/deactivate/{id}', 'deactivateSkill')->middleware(["is_admin"]);
         Route::get('/get_skill/{id}', 'getSkillById')->middleware(["is_admin"]);
         Route::get('/skills/{category_id}', 'getAllSkills');
         Route::get('/get_skills_admin', 'getAllSkillsAdmin')->middleware(["is_admin"]);
        });

    });

});

Route::prefix('analytics')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(AnalyticsController::class)->group(function () {
            Route::get('/user-growth', 'getUserGrowth')->middleware('is_admin');
            Route::get('/user-demographics', 'getUserDemographics')->middleware('is_admin');
            Route::get('/behavior-metrics','getBehaviorMetrics')->middleware('is_admin');
            Route::get('/project-status', 'getProjectStatus')->middleware('is_admin');
            Route::get('/project-types', 'getProjectTypes')->middleware('is_admin');
            Route::post('/time-spent', 'updateTimeSpent');
        });

    });

});

Route::prefix('report')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(ReportsController::class)->group(function () {
            Route::post('/users-report', 'getUsersReport');
            Route::post('/jobs-report', 'getJobsReport');
            Route::post('/applied-jobs-reports','getAppliedJobsReport');
            Route::post('/contracts-reports', 'getContractsReport');
            Route::post('/transaction-reports', 'getTransactionReport');
        });

    });

});


Route::prefix('privacy')->group(function(){
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/get', 'getPrivacyPolicy');
    });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(SupportTicketController::class)->group(function () {
            Route::post('/create', 'privacyPolicy');
            Route::put('/update', 'updatePrivacyPolicy');
        });

    });

});
Route::prefix('contact')->group(function(){
    Route::controller(SupportTicketController::class)->group(function () {
        Route::post('/create', 'contactUs');
    });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(SupportTicketController::class)->group(function () {
            Route::get('/getAll', 'getContacts');
            Route::delete('/delete/{id}', 'deleteContact');
        });

    });

});
Route::prefix('banner')->group(function(){
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/getAll', 'getBanners');
    });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(SupportTicketController::class)->group(function () {
            Route::post('/create', 'createBannerImage');
            Route::delete('/delete/{id}', 'deleteBanner');
            Route::put('/activate/{id}', 'activateBanner');
            Route::put('/deactivate/{id}', 'deactivateBanner');
        });

    });

});

Route::prefix('notification')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'getAllNotification');
            Route::get('/notification/{id}', 'getNotificationById');
            Route::put('/read/{id}','readNotification');
        });

    });

});


Route::prefix('setting')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', 'is_client_verified', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(NotificationController::class)->group(function () {
            Route::put('/email-notifications', 'updateEmailNotifications')->middleware('2fa');
            Route::put('/push-notifications', 'updatePushNotifications')->middleware('2fa');
            Route::put('/sms-notifications', 'updateSmsNotifications')->middleware('2fa');
            Route::put('/in-app-notifications', 'updateInAppNotifications')->middleware('2fa');
            Route::put('/profile-visibility', 'updateProfileVisibility')->middleware('2fa');
            Route::put('/search-visibility', 'updateSearchVisibility')->middleware('2fa');
            Route::put('/data-sharing', 'updateDataSharing')->middleware('2fa');
            Route::put('/location-settings', 'updateLocationSettings')->middleware('2fa');
            Route::put('/ad-preferences', 'updateAdPreferences')->middleware('2fa');
            Route::put('/activity-status', 'updateActivityStatus')->middleware('2fa');
            Route::get('/settings', 'getSettings')->middleware('2fa');
            Route::get('/request-data','requestAccountData')->middleware('2fa');
            Route::get('/is-2fa-verified','Is2FaVerified');
            Route::delete('/delete-account','deleteAccountPermanently')->middleware('2fa');

        });

    });

});


Route::prefix('configuration')->group(function(){
      Route::controller(ConfigurationController::class)->group(function () {
            Route::get('/countries', 'getAllCountries');
            Route::get('/doctypes', 'getAllDocTypes');

       });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_superuser', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(ConfigurationController::class)->group(function () {
            Route::post('/create-role', 'create_role');
            Route::get('/roles', 'getAllRoles');
            Route::get('/role/{id}', 'getRoleById');
            Route::put('/role/update', 'update_role');
            Route::delete('/role/delete/{id}', 'delete_role');

            Route::post('/create-ability', 'create_ability');
            Route::get('/abilities', 'getAllAbilities');
            Route::get('/ability/{id}', 'getAbilityById');
            Route::put('/ability/update', 'update_ability');
            Route::delete('/ability/delete/{id}', 'delete_ability');

            Route::post('/create-prefix', 'create_prefix');
            Route::get('/prefixes', 'getAllPrefixes');
            Route::get('/prefix/{id}', 'getPrefixById');
            Route::put('/prefix/update', 'update_prefix');
            Route::delete('/prefix/delete/{id}', 'delete_prefix');

            Route::post('/create-middleware', 'create_middleware');
            Route::get('/middlewares', 'getAllMiddlewares');
            Route::get('/middleware/{id}', 'getMiddlewareById');
            Route::put('/middleware/update', 'update_middleware');
            Route::delete('/middleware/delete/{id}', 'delete_middleware');

            Route::post('/create-controller', 'create_controller');
            Route::get('/controllers', 'getAllControllers');
            Route::get('/controller/{id}', 'getControllerById');
            Route::put('/controller/update', 'update_controller');
            Route::delete('/controller/delete/{id}', 'delete_controller');

            Route::post('/create-route', 'create_route');
            Route::get('/routes', 'getAllRoutes');
            Route::get('/route/{id}', 'getRouteById');
            Route::put('/route/update', 'update_route');
            Route::delete('/route/delete/{id}', 'delete_route');


            Route::post('/create-submiddleware', 'create_SubMiddleware');
            Route::get('/sub-middlewares', 'getAllSubMiddlewares');
            Route::get('/sub-middleware/{id}', 'getSubMiddlewareById');
            Route::put('/sub-middleware/update', 'update_SubMiddleware');
            Route::delete('/sub_middleware/delete/{id}', 'delete_SubMiddleware');


            Route::post('/create-country', 'create_country');
            Route::get('/country/{id}', 'getCountryById');
            Route::put('/country/update', 'update_country');
            Route::delete('/country/delete/{id}', 'delete_country');


            Route::post('/set-timezone', 'set_time_zone');
            Route::get('/tokens', 'getAllTokens');
            Route::delete('/token/delete/{id}', 'delete_token');

            Route::post('/create-documentType', 'create_docType');
            Route::delete('/docType/delete/{id}', 'delete_docType');

        });

    });


});


Route::prefix('freelancer_onboarding')->group(function(){
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(FreelancerController::class)->group(function () {
            Route::post('/onboard', 'onboard');
        });
    });
});



Route::prefix('navbar')->group(function(){
    Route::controller(NavbarController::class)->group(function () {
        Route::get('/navbar', 'getFullNavs') ;
        Route::get('/banner_text', 'getBannerText');

    });
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        // NavbarController endpoints
        Route::controller(NavbarController::class)->group(function () {
            Route::post('/create-navbar-type', 'create_navbar_type');
            Route::get('/navbar-types', 'getAllNavbarTypes');
            Route::get('/navbar_type/{id}', 'getNavbarTypeById');
            Route::put('/navbar_type/update', 'update_navbar_type');
            Route::delete('/navbar_type/delete/{id}', 'delete_navbar_type');

            // Navmenu routes
            Route::post('/create-navmenu', 'createNavMenu');
            Route::get('/navmenus', 'getAllNavMenu');
            Route::get('/navmenu/{id}', 'getNavMenuById');
            Route::put('/navmenu/update', 'updateNavMenu');
            Route::delete('/navmenu/delete/{id}', 'deleteNavMenu');

            // Navtext routes
            Route::post('/create-navtext', 'createNavText');
            Route::get('/navtexts', 'getAllNavText');
            Route::get('/navtext/{id}', 'getNavTextById');
            Route::put('/navtext/update', 'updateNavText');
            Route::put('/navtext/activate/{id}', 'activateNavText');
            Route::delete('/navtext/delete/{id}', 'deleteNavText');

            // Navbutton routes
            Route::post('/create-navbutton', 'createNavButton');
            Route::get('/navbuttons', 'getAllNavButton');
            Route::get('/navbutton/{id}', 'getNavButtonById');
            Route::put('/navbutton/update', 'updateNavButton');
            Route::delete('/navbutton/delete/{id}', 'deleteNavButton');

            // Navlogo routes
            Route::post('/upsert-logo', 'upsertLogo');
            Route::get('/get-logo', 'getNavLogo');
            Route::delete('/logo/delete/{id}', 'deleteNavLogo')->name('navbarLogo.delete');

        });
    });

});

Route::prefix('discoverTalent')->group(function () {
    Route::controller(DiscoverTalentController::class)->group(function () {
        Route::get('/discover-talents', 'getAllDiscover');
        Route::get('/discover-talent-with-bg', 'getDiscoverTalentWithBg');
        Route::get('/backgrounds', 'getAllDiscoverBackground');
    });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(DiscoverTalentController::class)->group(function () {
            Route::post('/create-talent', 'createDiscover');
            Route::get('/discover-talent/{id}', 'getDiscoverById');
            Route::put('/discover-talent/update', 'updateDiscover');
            Route::delete('/discover-talent/delete/{id}', 'deleteDiscover');

            Route::post('/create-background', 'createDiscoverBackground');
            Route::get('/background/{id}', 'getDiscoverBackgroundById');
            Route::put('/background/update', 'updateDiscoverBackground');
            Route::delete('/background/delete/{id}', 'deleteDiscoverBackground');
        });
    });

});

Route::prefix('whychooseus')->group(function () {

    Route::controller(WhyChooseUsController::class)->group(function () {
        Route::get('/why-choose-us', 'getAllFE');

    });
    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa','is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(WhyChooseUsController::class)->group(function () {
            Route::post('/create', 'create');
            Route::get('/whychooseus/{id}', 'getById');
            Route::put('/update', 'update');
            Route::delete('/delete/delete/{id}', 'delete');
            Route::get('/whychooseus', 'getAll');


            Route::post('/create-card', 'create_card');
            Route::get('/card/{id}', 'getCardById');
            Route::put('/card/update', 'update_card');
            Route::delete('/card/delete/{id}', 'delete_card');
            Route::get('/cards', 'getAllCards');

        });
    });

});

Route::prefix('gigs')->group(function () {
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {

        Route::controller(GigsController::class)->group(function () {
            Route::post('/create-gigs', 'createGigs')->middleware('is_admin');
            Route::get('/getGigsList', 'getAllGigs');
            Route::get('/getGig/{id}', 'getGigById')->middleware('is_admin');
            Route::put('/giglist/update', 'updateGigList')->middleware('is_admin');
            Route::delete('/giglist/delete/{id}', 'deleteGigList')->middleware('is_admin');
        });
    });

});

Route::prefix('verification')->group(function () {
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {

        Route::controller(VerificationController::class)->group(function () {
            Route::post('/submit', 'submitVerification')->middleware('is_client');
            Route::get('/getAll', 'getSubmittedVerifications')->middleware('is_admin');
            Route::get('/my-verification', 'getMySubmittedVerification')->middleware('is_client');
            Route::get('/get-verification-id/{id}', 'getSubmittedVerificationById')->middleware('is_admin');
            Route::put('/approve/{id}', 'approve')->middleware('is_admin');
            Route::put('/reject/{id}', 'reject')->middleware('is_admin');
            Route::delete('/delete/{id}', 'deleteVerification')->middleware('is_admin');
        });
    });

});


Route::prefix('yearsofexperience')->group(function () {
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {

        Route::controller(YearsOfExperienceController::class)->group(function () {
            Route::post('/create', 'createYearsOfExperience')->middleware('is_admin');
            Route::get('/getAll', 'getAllYearsOfExperience');
            Route::get('/getById/{id}', 'getYearsOfExperiencebyId')->middleware('is_admin');
            Route::put('/update', 'updateYearsOfExperience')->middleware('is_admin');
            Route::delete('/delete/{id}', 'deleteYearsOfExperience')->middleware('is_admin');
        });
    });

});

Route::prefix('jobtype')->group(function () {
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
            Route::controller(JobTypeController::class)->group(function () {
                Route::post('/create', 'createJobType')->middleware('is_admin');
                Route::get('/getAll', 'getAllJobType');
                Route::get('/getById/{id}', 'getJobTypebyId')->middleware('is_admin');
                Route::put('/update', 'updateJobType')->middleware('is_admin');
                Route::delete('/delete/{id}', 'deleteJobType')->middleware('is_admin');
            });
    });

});

Route::prefix('reviews')->group(function () {
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa','is_client_verified', 'monitor_api_usage', 'audit_log'])->group(function () {

        Route::controller(FreelancerReviewController::class)->group(function () {
            Route::post('/freelancer-reviews/{freelancer_id}', 'freelancer_reviews')->middleware(['is_client']);
            Route::get('/rate-freelancer', 'rate_freelancer')->middleware(['is_client']);
        });
    });

});

Route::prefix('dashboard')->group(function () {
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa','is_client_verified', 'monitor_api_usage', 'audit_log'])->group(function () {

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/client-tables', 'getClientTables')->middleware(['is_client']);
            Route::get('/client-statistics', 'getStatistics')->middleware(['is_client']);
            Route::get('/admin-tables', 'getAdminTables')->middleware(['is_admin']);
            Route::get('/admin-statistics', 'getAdminStatistics')->middleware(['is_admin']);
            Route::get('/dashboard-counters', 'getCounters')->middleware(['is_admin']);
            Route::get('/latest-tickets', 'getLatestSupportTickets')->middleware(['is_admin']);
            Route::get('/latest-users', 'getLatestUsers')->middleware(['is_admin']);
        });
    });

});

Route::prefix('job')->group(function(){
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_client_verified', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(JobController::class)->group(function () {
            Route::post('/upsert-job', 'upsertJob');
            Route::get('/get_Job_by_slug/{slug}', 'getJobBySlug');
            Route::get('/all_jobs', 'getAllJobs');
            Route::get('/available_jobs', 'getAllAvailableJobs');
            Route::get('/my_jobs', 'getMyJobs');
            Route::get('/recommended_jobs', 'getRecommendedJobs');
            Route::get('/extract_cover_letter', 'extractText');
            Route::post('/apply', 'apply');
            Route::get('/applied-jobs/{jobId}', 'getAppliedJobs');
            Route::get('/client-applied-jobs/{jobId}', 'getClientAppliedJobs');
            Route::get('/applied-job/{applied_id}', 'getAppliedJob');
            Route::post('/approve-job/{applied_id}', 'approveJob');
            Route::post('/reject-job/{applied_id}', 'rejectJob');
            Route::delete('/delete-job/{job_id}', 'deleteJob');
            Route::delete('/delete-application/{applied_id}', 'deleteApplicationJob');
            Route::get('/contracts', 'contracts');
            Route::get('/contract/{contract_id}', 'contract');
            Route::get('/contracts_for_client', 'contractsForClient');
            Route::get('/contract_for_client/{contract_id}', 'contractForClient');
            Route::put('/contract/update-freelancer/{contract_id}', 'updateContractStatusFreelancer');
            Route::put('/contract/update-client/{contract_id}', 'updateContractStatusClient');
            Route::put('/update-progress/{contract_id}/{progress}', 'updateProgress');
            Route::put('/update-milestone-progress/{milestone_id}/{progress}', 'updateMilestoneProgress');
            Route::get('/get_job_by_id/{job_id}', 'getJobById');
            Route::get('/freelancer-applied-jobs', 'getFreelancerAppliedJobs');
            Route::get('/freelancer-applied-job/{applied_id}', 'getFreelancerAppliedJob');



        });

    });

});

Route::prefix('invites')->group(function(){
       Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_client_verified', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(JobController::class)->group(function () {
            Route::post('/search-freelancer', 'search_freelancer')->middleware(['is_client']);
            Route::get('/only-invites-jobs', 'onlyInvites')->middleware(['is_client']);
            Route::post('/invite-freelancer', 'invite_freelancer')->middleware(['is_client']);
            Route::get('/search-history', 'searchHistory')->middleware(['is_client']);
            Route::get('/invites-sent', 'InvitesSent')->middleware('is_client');
            Route::get('/my-invites', 'MyInvites')->middleware('is_freelancer');
            Route::put('/accept_invite/{invite_id}', 'acceptInvite')->middleware('is_freelancer');
            Route::put('/decline_invite/{invite_id}', 'declineInvite')->middleware('is_freelancer');

        });

    });

});

//Testimonial Routes

Route::prefix('testimonial')->group(function(){
    Route::controller(TestimonialsController::class)->group(function () {
        Route::get('/getAllTestimonials', 'getAllTestimonials');
    });

    Route::controller(TestimonialCardController::class)->group(function () {
        Route::get('/getAllTestimonialCards', 'getAllTestimonialCards');
    });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(TestimonialsController::class)->group(function () {
            Route::post('/createTestimonial', 'createTestimonial');
            Route::put('/updateTestimonial/{id}', 'updateTestimonial');
            Route::get('/getTestimonial/{id}', 'getTestimonial');
            Route::delete('/deleteTestimonial/{id}', 'deleteTestimonial');


        });

        Route::controller(TestimonialCardController::class)->group(function () {
            Route::post('/createTestimonialCard', 'createTestimonialCard');
            Route::put('/updateTestimonialCard/{id}', 'updateTestimonialCard');
            Route::get('/getTestimonialCard/{id}', 'getTestimonialCard');
            Route::delete('/deleteTestimonialCard/{id}', 'deleteTestimonialCard');

        });
    });


});

Route::prefix('footer')->group(function(){
    Route::controller(FooterController::class)->group(function () {

        Route::get('/getFooters', 'getFooters');
        Route::get('/getAllFooterCopyrights', 'getAllFooterCopyrights');
        Route::get('/getAllFooterSocialMedia', 'getAllFooterSocialMedia');
        Route::get('/footer-with-socials', 'getFooterSocialMediaFE');
    });

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'is_admin', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(FooterController::class)->group(function () {

            Route::get('/getFooters', 'getFooters');
            Route::post('/createFooter', 'createFooter');
            Route::put('/updateFooter/{id}', 'updateFooter');
            Route::delete('/deleteFooter/{id}', 'deleteFooter');

            Route::post('/createFooterCopyright', 'createFooterCopyright');
            Route::put('/updateFooterCopyright/{id}', 'updateFooterCopyright');
            Route::get('/getFooterCopyright/{id}', 'getFooterCopyright');
            Route::delete('/deleteFooterCopyright/{id}', 'deleteFooterCopyright');

            Route::post('/createFooterSocialMedia', 'createFooterSocialMedia');
            Route::put('/updateFooterSocialMedia/{id}', 'updateFooterSocialMedia');
            Route::get('/getFooterSocialMedia/{id}', 'getFooterSocialMedia');
            Route::delete('/deleteFooterSocialMedia/{id}', 'deleteFooterSocialMedia');
            Route::put('/activateFooterSocialMedia/{id}', 'activateFooterSocialMedia');
            Route::put('/deactivateFooterSocialMedia/{id}', 'deactivateFooterSocialMedia');

        });
    });
});

Route::prefix('transaction')->group(function(){

    Route::middleware(['auth:sanctum', 'is_email_verified','re_login', '2fa', 'monitor_api_usage', 'audit_log'])->group(function () {
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/get_all_transactions', 'getAllPayments')->middleware('is_admin');
            Route::get('/get_payment_by_id/{id}', 'getPaymentById')->middleware('is_admin');
            Route::get('/get_freelancer_payments', 'getAllFreelancerPayments')->middleware('is_freelancer');
            Route::get('/get_client_payments', 'getAllClientPayments')->middleware('is_client');
            Route::get('/get_total_income_payouts/{user_id}', 'getEarnings')->middleware('is_freelancer');
            Route::post('/pay',  'redirectToGateway')->name('payment');
            Route::get('/payment/callback/{reference}', 'handleGatewayCallback')->name('payment.callback');
            Route::post('/payment/verify_payment/{reference}', 'verifyPayment');
            Route::post('/pay-guppa',  'payGuppa')->name('payment.guppa');
            Route::post('/payment/verify_guppa_payment/{reference}', 'verifyGuppaPayment');
            Route::get('/payment/pending-job-payments', 'pendingJobPayments');

        });
    });
});

Route::prefix('supportticket')->group(function () {
    Route::middleware(['auth:sanctum', 'monitor_api_usage', 'audit_log', 'audit_log', 'audit_log', 'is_email_verified','re_login', '2fa'])->group(function () {

        Route::controller(SupportTicketController::class)->group(function () {
            Route::post('/create', 'createSupportTicket');
            Route::get('/getAll', 'getAllSupportTicket')->middleware(['is_admin']);
            Route::get('/get-my-tickets', 'getAllMySupportTicket');
            Route::get('/getById/{id}', 'getSupportTicketbyId');
            Route::put('/update', 'updateSupportTicket');
            Route::delete('/delete/{id}', 'deleteSupportTicket');
            Route::put('/resolve/{id}', 'resolveSupportTicket')->middleware(['is_admin']);
            Route::put('/close/{id}', 'closeSupportTicket')->middleware(['is_admin']);
        });
    });

});

Route::prefix('bidpaymentconfig')->group(function () {
    Route::middleware(['auth:sanctum', 'monitor_api_usage', 'audit_log', 'audit_log', 'audit_log', 'is_email_verified','re_login', '2fa'])->group(function () {

        Route::controller(BidPaymentConfigController::class)->group(function () {
            Route::post('/create', 'createBidPaymentConfig');
            Route::get('/getBidConfig', 'getBidPaymentConfig');
        });
    });

});

Route::prefix('monitor')->group(function () {
    Route::middleware(['auth:sanctum', 'monitor_api_usage', 'audit_log', 'audit_log', 'is_email_verified','re_login', '2fa', 'is_superuser'])->group(function () {

        Route::controller(MonitorActivityController::class)->group(function () {
            Route::get('/all-api-usage', 'getAllApiUsage');
            Route::get('/api-usage/{id}', 'getApiUsage');
            Route::get('/audit-logs', 'getAllAuditLogs');
            Route::get('/audit-log/{id}', 'getAuditLog');
        });
    });

});



