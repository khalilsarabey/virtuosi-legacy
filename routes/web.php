<?php

use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SectionsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\LowCostBookingController;
use App\Livewire\Admin\Pricing\Index as PricingIndex;
use App\Livewire\Admin\Airline\Create as AirlineCreate;
use App\Livewire\Admin\Airline\Edit as AirlineEdit;
use App\Livewire\Admin\Airline\Index as AirlineIndex;
use App\Livewire\Admin\Blocks\Create as BlockCreate;
use App\Livewire\Admin\Blocks\Edit as BlockEdit;
use App\Livewire\Admin\Blocks\Index as BlockIndex;
use App\Livewire\Admin\Booking\Change as BookingChange;
use App\Livewire\Admin\Booking\Edit as BookingEdit;
use App\Livewire\Admin\Booking\Index as BookingIndex;
use App\Livewire\Admin\Category\Create as CategoryCreate;
use App\Livewire\Admin\Category\Edit as CategoryEdit;
use App\Livewire\Admin\Category\Index as CategoryIndex;
use App\Livewire\Admin\Contract\Create as ContractCreate;
use App\Livewire\Admin\Contract\Edit as ContractEdit;
use App\Livewire\Admin\Contract\Index as ContractIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Flight\Create as FlightCreate;
use App\Livewire\Admin\Flight\Duplicate as FlightDuplicate;
use App\Livewire\Admin\Flight\Edit as FlightEdit;
use App\Livewire\Admin\Flight\Generate as FlightGenerate;
use App\Livewire\Admin\Flight\Index as FlightIndex;
use App\Livewire\Admin\Location\Create as LocationCreate;
use App\Livewire\Admin\Location\Edit as LocationEdit;
use App\Livewire\Admin\Location\Index as LocationIndex;
use App\Livewire\Admin\PricingAirline\Index as PricingAirlineIndex;
use App\Livewire\Admin\PricingAirline\Edit as PricingAirlineEdit;
use App\Livewire\Admin\Section\Create as SectionCreate;
use App\Livewire\Admin\Section\Edit as SectionEdit;
use App\Livewire\Admin\Section\Index as SectionIndex;
use App\Livewire\Admin\Settings\Branding;
use App\Livewire\Admin\Settings\Currencies;
use App\Livewire\Admin\Settings\Email;
use App\Livewire\Admin\Settings\Footer;
use App\Livewire\Admin\Settings\Modules;
use App\Livewire\Admin\Settings\TopMenuLinks;
use App\Livewire\Admin\Update\Create as UpdateCreate;
use App\Livewire\Admin\Update\Edit as UpdateEdit;
use App\Livewire\Admin\Update\Index as UpdateIndex;
use App\Livewire\Admin\User\Create as UserCreate;
use App\Livewire\Admin\User\Edit as UserEdit;
use App\Livewire\Admin\User\Index as UserIndex;
use App\Livewire\Frontend\Book;
use App\Livewire\Frontend\Category\Show as CategoryShow;
use App\Livewire\Frontend\LowCost;
use App\Livewire\Frontend\MyBookings;
use App\Livewire\Frontend\MyProfile;
use App\Livewire\Frontend\Search;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Route::get('/test', function () {
//    $booking = \App\Models\Booking::query()->where('id', 7589)
//        ->with(['rooms', 'user', 'tickets', 'depFlight.contract'])
//        ->orderBy('created_at', 'DESC')
//        ->first();
//
//    return view('emails.template', compact('booking'));
//});

Route::get('/error', function () {
    return view('errors.404');
});

Route::get('/', function () {
    return redirect('/v2/frontend');
});

Route::get('/admin', function () {
    return redirect('/v2/admin');
});

Route::get('change-password', \App\Livewire\ChangePassword::class)->name('change-password');
Route::get('otp', \App\Livewire\Otp::class)->name('otp');
Auth::routes(['register' => false]);

//Route::get('test', function() {
    //$booking = \App\Models\AirlineBooking::find(25);
    //Mail::send(new NewAirlineBookingEmail('AirArabia', 13, auth()->user()->email ));
//    return view('emails.airline-template', ['booking' => $booking, 'request' => json_decode($booking->request, true), 'payload' => json_decode($booking->payload, true)]);
//    $booking = \App\Models\Booking::orderBy('created_at', 'DESC')->first();
//    $agency = Agency::find(1);
//    return view('emails.template', compact('booking', 'agency'));
//});

Route::group(['prefix' => 'v2/frontend', 'as' => 'frontend.', 'middleware' => ['web', 'auth', 'verified', 'active', 'labels']], function () {
    Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
        Route::get('flights_locations', [\App\Http\Controllers\Api\AirportsController::class, 'index'])->name('airports.index');
        Route::get('flights_locations/search', [\App\Http\Controllers\Api\AirportsController::class, 'search'])->name('airports.search');
    });
    //Route::get('/', \App\Livewire\Frontend\Pages::class)->name('home');
    Route::get('/', \App\Livewire\Frontend\Home::class)->name('home');
    Route::get('/page/{slug}', \App\Livewire\Frontend\Pages::class)->name('page.show');
    Route::get('/search', Search::class)->name('search');
    Route::get('/hotels/search', \App\Livewire\Frontend\Hotels\Index::class)->name('hotels.search')->middleware('features:hotels');
    Route::get('/packages/search', Search::class)->name('packages.search')->middleware('features:hotels');
    Route::match(['get', 'post'],'/hotels/{id}/show', \App\Livewire\Frontend\Hotels\Show::class)->name('hotels.show');
    //Low Cost Routes
    Route::get('/low-cost', LowCost\Index::class)->name('low-cost.index')->middleware('samad_only');
    Route::post('/low-cost/search', LowCost\Search::class)->name('low-cost.search')->middleware('samad_only');
    Route::post('/low-cost/booking', [LowCostBookingController::class, 'booking'])->name('low-cost.booking')->middleware('samad_only');
    Route::post('/low-cost/summary', [LowCostBookingController::class, 'summary'])->name('low-cost.summary')->middleware('samad_only');
    Route::post('/low-cost/confirm', [LowCostBookingController::class, 'confirm'])->name('low-cost.confirm')->middleware('samad_only');
    Route::post('/low-cost/seats', [LowCostBookingController::class, 'seats'])->name('low-cost.seats')->middleware('samad_only');
    Route::get('/low-cost/retrieve-pnr', LowCost\RetrievePnr::class)->name('low-cost.retrievepnr')->middleware('samad_only');
    Route::post('/low-cost/retrieve-pnr', [LowCostBookingController::class, 'pnr'])->name('low-cost.post-retrievepnr')->middleware('samad_only');
    Route::post('/low-cost/retrieve-and-pay', [LowCostBookingController::class, 'retrieveAndPay'])->name('low-cost.retrieveandpay')->middleware('samad_only');
    //End Low Cost Routes
    //Theta routes
    Route::post('/flights/search', LowCost\Search::class)->name('flights.search')->middleware('samad_only');
    //End Theta routes
    Route::get('/category/{id}', CategoryShow::class)->name('categories.show');
    Route::post('book', Book::class)->name('book');
    Route::post('book/store', [BookingsController::class, 'store'])->name('book.store');
    Route::get('/booking/details/{id}', [BookingsController::class, 'show'])->name('booking.show');
    Route::get('/booking/details/{id}/print', [BookingsController::class, 'print'])->name('booking.print');
    Route::get('/booking/details/{id}/invoice', [BookingsController::class, 'invoice'])->name('booking.invoice');
    Route::get('/booking/details/{id}/airline-invoice', [BookingsController::class, 'airline_invoice'])->name('booking.airline-invoice');
    Route::get('my-profile', MyProfile::class)->name('my-profile');
    Route::get('my-bookings', MyBookings::class)->name('my-bookings');
    Route::get('auto-pricing', \App\Livewire\Frontend\AutoPricing::class)->name('auto-pricing')->middleware('samad_only');;
    Route::get('auto-pricing/announcements', \App\Livewire\Frontend\Announcements::class)->name('auto-pricing.announcements')->middleware('samad_only');
});

Route::group(['prefix' => 'v2/admin', 'as' => 'admin.', 'middleware' => ['web', 'auth', 'verified', 'admin', 'active', 'labels']], function () {
    Route::get('pages', \App\Livewire\Admin\Pages\Index::class)->name('pages.index');
    Route::get('pages/create', \App\Livewire\Admin\Pages\Create::class)->name('pages.create');
    Route::get('pages/edit/{id}', \App\Livewire\Admin\Pages\Edit::class)->name('pages.edit');
    Route::group(['prefix' => 'hotels', 'middleware' => 'features:hotels'], function () {
        Route::get('/', \App\Livewire\Admin\Hotels\Index::class)->name('hotels.index');
        Route::get('/create', \App\Livewire\Admin\Hotels\Create::class)->name('hotels.create');
        Route::get('/{hotel}/edit', \App\Livewire\Admin\Hotels\Edit::class)->name('hotels.edit');
        Route::get('/{hotel}/show', \App\Livewire\Admin\Hotels\Show::class)->name('hotels.show');
        Route::get('/{hotel}/add-rooms/', \App\Livewire\Admin\Hotels\AddContract::class)->name('hotels.add-rooms');
        Route::get('/{hotel}/rooms', \App\Livewire\Admin\Hotels\Rooms::class)->name('hotels.rooms');
        Route::get('/services', \App\Livewire\Admin\Hotels\Services::class)->name('hotels.services');
        Route::get('/seasons', \App\Livewire\Admin\Hotels\Seasons\Index::class)->name('hotels.seasons.index');
        Route::get('/rooms/{room}/edit', \App\Livewire\Admin\Hotels\EditRoom::class)->name('hotels.rooms.edit');
        Route::get('/{hotel}/rooms/create', \App\Livewire\Admin\Hotels\CreateRoom::class)->name('hotels.rooms.create');
        Route::get('/features', \App\Livewire\Admin\Hotels\Features::class)->name('hotels.features');
        Route::get('/room-types', \App\Livewire\Admin\Hotels\RoomTypes::class)->name('hotels.room-types');
        Route::get('/rules', \App\Livewire\Admin\Hotels\Rules\Index::class)->name('hotels.rules.index');
        Route::get('/rules/create', \App\Livewire\Admin\Hotels\Rules\Create::class)->name('hotels.rules.create');
        Route::get('/rules/{rule}/edit', \App\Livewire\Admin\Hotels\Rules\Edit::class)->name('hotels.rules.edit');
        Route::post('/hotels/store-media', [\App\Http\Controllers\HotelsController::class, 'storeMedia'])->name('hotels.storeMedia');
        Route::post('/rooms/store-media', [\App\Http\Controllers\RoomsController::class, 'storeMedia'])->name('rooms.storeMedia');
    });

    Route::group(['prefix' => 'turkish', 'as' => 'turkish.'], function () {
        Route::get('/{section}/airports', \App\Livewire\Admin\Turkish\Airports\Index::class)->name('airports.index');
        Route::get('/{section}/destinations', \App\Livewire\Admin\Turkish\Destinations\Index::class)->name('destinations.index');
        Route::get('/{section}/custom', \App\Livewire\Admin\Turkish\Custom\Index::class)->name('custom.index');
        Route::get('/sections', \App\Livewire\Admin\Turkish\Sections\Index::class)->name('sections.index');
    })->middleware(['samad_only', 'features:whitelabel']);

    Route::get('/settings/connections', \App\Livewire\Admin\Settings\Connections\Index::class)->name('connections.index')->middleware('features:api');
    Route::get('/settings/connections/create', \App\Livewire\Admin\Settings\Connections\Create::class)->name('connections.create')->middleware('features:api');
    Route::get('/settings/connections/{id}/edit', \App\Livewire\Admin\Settings\Connections\Edit::class)->name('connections.edit')->middleware('features:api');

    Route::get('/settings/integrations', \App\Livewire\Admin\Integrations\Index::class)->name('integrations.index')->middleware('samad_only');
    Route::get('/settings/integrations/create', \App\Livewire\Admin\Integrations\Create::class)->name('integrations.create')->middleware('samad_only');
    Route::get('/settings/integrations/edit/{id}', \App\Livewire\Admin\Integrations\Edit::class)->name('integrations.edit')->middleware('samad_only');

    Route::post('agencies/media', [AgencyController::class, 'storeMedia'])->name('agencies.storeMedia');
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/white-label', \App\Livewire\Admin\WhiteLabel\Index::class)->name('whitelabel.index')->middleware('features:whitelabel');
    Route::get('/white-label/create', \App\Livewire\Admin\WhiteLabel\Create::class)->name('whitelabel.create')->middleware('features:whitelabel');
    Route::get('/white-label/edit/{id}', \App\Livewire\Admin\WhiteLabel\Edit::class)->name('whitelabel.edit')->middleware('features:whitelabel');

//    Route::get('/connections', \App\Livewire\Admin\Connections\Index::class)->name('connections.index')->middleware('features:connections');
//    Route::get('/connections/create', \App\Livewire\Admin\Connections\Create::class)->name('connections.create')->middleware('features:connections');
//    Route::get('/connections/edit/{id}', \App\Livewire\Admin\Connections\Edit::class)->name('connections.edit')->middleware('features:connections');
    //Route::get('/', SectionIndex::class)->name('dashboard');
    Route::get('/flights/duplicate/{id}', FlightDuplicate::class)->name('flights.duplicate')->middleware('features:charter');
    Route::get('/flights/generate', FlightGenerate::class)->name('flights.generate')->middleware('features:charter');
    Route::get('/flights', FlightIndex::class)->name('flights.index')->middleware('features:charter');
    Route::get('/flights/edit/{id}', FlightEdit::class)->name('flights.edit')->middleware('features:charter');
    Route::get('/flights/edit/{id}', FlightEdit::class)->name('flights.edit')->middleware('features:charter');
    Route::get('/flights/create', FlightCreate::class)->name('flights.create')->middleware('features:charter');
    Route::get('/bookings', BookingIndex::class)->name('bookings')->middleware('features:charter');
    Route::get('/bookings/hotels', \App\Livewire\Admin\Booking\Hotels::class)->name('bookings.hotels')->middleware('features:hotels');
    Route::get('/bookings/packages', \App\Livewire\Admin\Booking\Packages::class)->name('bookings.packages')->middleware('features:packages');
    Route::get('/bookings/low-cost', \App\Livewire\Admin\AirlineBooking\Index::class)->name('airline-bookings.index')->middleware('features:lowcost');
    Route::get('/bookings/low-cost/{id}/show', \App\Livewire\Admin\AirlineBooking\Index::class)->name('airline-bookings.show')->middleware('features:lowcost');
    Route::get('/bookings/edit/{id}', BookingEdit::class)->name('bookings.edit')->middleware('features:charter');
    Route::get('/bookings/{id}/edit-package', \App\Livewire\Admin\Booking\EditPackage::class)->name('bookings.package.edit')->middleware('features:packages');
    Route::get('/bookings/{id}/edit-hotel', \App\Livewire\Admin\Booking\EditHotel::class)->name('bookings.hotel.edit')->middleware('features:hotels');
    Route::get('/bookings/change/{id}', BookingChange::class)->name('bookings.change')->middleware('features:charter');
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/categories/create', CategoryCreate::class)->name('categories.create');
    Route::get('/categories/edit/{id}', CategoryEdit::class)->name('categories.edit');
    Route::post('categories/media', [CategoryController::class, 'storeMedia'])->name('categories.storeMedia');
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/edit/{id}', UserEdit::class)->name('users.edit');
    Route::post('users/media', [UserController::class, 'storeMedia'])->name('users.storeMedia');
    Route::get('/locations', LocationIndex::class)->name('locations.index');
    Route::get('/locations/edit/{id}', LocationEdit::class)->name('locations.edit');
    Route::get('/locations/create', LocationCreate::class)->name('locations.create');
    Route::get('/airlines', AirlineIndex::class)->name('airlines.index');
    Route::get('/airlines/create', AirlineCreate::class)->name('airlines.create');
    Route::get('/airlines/edit/{id}', AirlineEdit::class)->name('airlines.edit');
    Route::get('/contracts', ContractIndex::class)->name('contracts.index');
    Route::get('/contracts/create', ContractCreate::class)->name('contracts.create');
    Route::get('/contracts/edit/{id}', ContractEdit::class)->name('contracts.edit');

    Route::get('/blocks', BlockIndex::class)->name('blocks.index');
    Route::get('/blocks/edit/{id}', BlockEdit::class)->name('blocks.edit');
    Route::get('/blocks/create/{block?}', BlockCreate::class)->name('blocks.create');
    Route::get('/blocks/create/sub/{block?}', \App\Livewire\Admin\Blocks\CreateSub::class)->name('blocks.sub.create');
    Route::get('/blocks/edit/sub/{id?}', \App\Livewire\Admin\Blocks\EditSub::class)->name('blocks.sub.edit');
    Route::post('blocks/media', [BlockController::class, 'storeMedia'])->name('blocks.storeMedia');

    Route::post('sections/media', [SectionsController::class, 'storeMedia'])->name('sections.storeMedia');
    Route::get('sections', SectionIndex::class)->name('sections.index');
    Route::get('sections/create', SectionCreate::class)->name('sections.create');
    Route::get('sections/{id}/edit', SectionEdit::class)->name('sections.edit');

    Route::get('updates', UpdateIndex::class)->name('updates.index');
    Route::get('updates/create', UpdateCreate::class)->name('updates.create');
    Route::get('updates/{id}/edit', UpdateEdit::class)->name('updates.edit');

    Route::get('settings/branding', Branding::class)->name('settings.branding');
    Route::get('settings/menu-links', TopMenuLinks::class)->name('settings.top-menu-links');
    Route::get('settings/footer', Footer::class)->name('settings.footer');
    Route::get('settings/modules', Modules::class)->name('settings.modules');
    Route::get('settings/email', Email::class)->name('settings.email');
    Route::get('settings/currencies', Currencies::class)->name('settings.currencies');

    Route::get('settings/auto-pricing/pricing', PricingIndex::class)->name('settings.auto-pricing.pricing.index')->middleware('samad_only');;

    Route::get('settings/auto-pricing/airlines', PricingAirlineIndex::class)->name('settings.auto-pricing.airlines.index')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/airlines/create', App\Livewire\Admin\PricingAirline\Create::class)->name('settings.auto-pricing.airlines.create')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/airline/{id}/edit', PricingAirlineEdit::class)->name('settings.auto-pricing.airlines.edit')->middleware(['samad_only', 'features:autopricing']);

    Route::get('settings/auto-pricing/categories', App\Livewire\Admin\Settings\PricingCategories\Index::class)->name('settings.auto-pricing.categories.index')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/categories/create', App\Livewire\Admin\Settings\PricingCategories\Create::class)->name('settings.auto-pricing.categories.create')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/categories/{id}/edit', App\Livewire\Admin\Settings\PricingCategories\Edit::class)->name('settings.auto-pricing.categories.edit')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/categories/{id}/show', App\Livewire\Admin\Settings\PricingCategories\Show::class)->name('settings.auto-pricing.categories.show')->middleware(['samad_only', 'features:autopricing']);

    Route::get('settings/auto-pricing/classes', \App\Livewire\Admin\Settings\PricingClasses\Index::class)->name('settings.auto-pricing.classes.index')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/classes/create', \App\Livewire\Admin\Settings\PricingClasses\Create::class)->name('settings.auto-pricing.classes.create')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/classes/{id}/edit', \App\Livewire\Admin\Settings\PricingClasses\Edit::class)->name('settings.auto-pricing.classes.edit')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/classes/{id}/show', \App\Livewire\Admin\Settings\PricingClasses\Show::class)->name('settings.auto-pricing.classes.show')->middleware(['samad_only', 'features:autopricing']);

    Route::get('settings/auto-pricing/agencies', \App\Livewire\Admin\Settings\Agencies\Index::class)->name('settings.auto-pricing.agencies.index')->middleware(['samad_only', 'features:autopricing']);
    Route::get('settings/auto-pricing/{id}/agencies', \App\Livewire\Admin\Settings\Agencies\Edit::class)->name('settings.auto-pricing.agencies.edit')->middleware(['samad_only', 'features:autopricing']);

    Route::get('settings/auto-pricing/announcements', \App\Livewire\Admin\Settings\AutoPricing\Announcements\Index::class)->name('settings.auto-pricing.announcements.index')->middleware(['samad_only', 'features:autopricing']);

    // Permissions
    //    Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update', 'destroy']]);

    // Roles
    //    Route::resource('roles', RoleController::class, ['except' => ['store', 'update', 'destroy']]);

    // Users
    //    Route::resource('users', UserController::class, ['except' => ['store', 'update', 'destroy']]);

    // Audit Logs
    //    Route::resource('audit-logs', AuditLogController::class, ['except' => ['store', 'update', 'destroy', 'create', 'edit']]);

    // Airline
    //    Route::post('airlines/media', [AirlineController::class, 'storeMedia'])->name('airlines.storeMedia');
    //    Route::resource('airlines', AirlineController::class, ['except' => ['store', 'update', 'destroy', 'show']]);

    // Category

    //Route::resource('categories', CategoryController::class, ['except' => ['store', 'update', 'destroy']]);

    // Update
    //Route::resource('updates', UpdateController::class, ['except' => ['store', 'update', 'destroy']]);

    // Contract
    //Route::resource('contracts', ContractController::class, ['except' => ['store', 'update', 'destroy']]);

    // Flight
    //Route::resource('flights', FlightController::class, ['except' => ['store', 'update', 'destroy']]);

    // Location
    //Route::resource('locations', LocationController::class, ['except' => ['store', 'update', 'destroy']]);

    // Price
    //Route::resource('prices', PriceController::class, ['except' => ['store', 'update', 'destroy']]);

    // Booking
    //Route::resource('bookings', BookingController::class, ['except' => ['store', 'update', 'destroy']]);

    // Ticket
    //Route::resource('tickets', TicketController::class, ['except' => ['store', 'update', 'destroy']]);
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    if (file_exists(app_path('Http/Controllers/Auth/UserProfileController.php'))) {
        Route::get('/', [UserProfileController::class, 'show'])->name('show');
    }
});
