<?php

use App\Events\ChatEvent;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\v1\AppointmentsController;
use App\Livewire\Banner\Create;
use App\Livewire\Banner\Edit;
use App\Livewire\Banner\Index as BannerIndex;
use App\Livewire\PartnerAds\Index as PartnerAdsIndex;
use App\Livewire\PartnerAds\Create as PartnerAdsCreate;
use App\Livewire\PartnerAds\Edit as PartnerAdsEdit;
use App\Livewire\Blog\Create as BlogCreate;
use App\Livewire\Blog\Edit as BlogEdit;
use App\Livewire\Blog\Index as BlogIndex;
use App\Livewire\Category\Index;
use App\Livewire\Cities\Index as CitiesIndex;
use App\Livewire\Complaint\Index as ComplaintIndex;
use App\Livewire\ContactFrom\Index as ContactFromIndex;
use App\Livewire\Filter\Index as FilterIndex;
use App\Livewire\Freelancer\Appointment as FreelancerAppointment;
use App\Livewire\Freelancer\AppointmentView as FreelancerAppointmentView;
use App\Livewire\Freelancer\Create as FreelancerCreate;
use App\Livewire\Freelancer\Edit as FreelancerEdit;
use App\Livewire\Freelancer\FreelancerView;
use App\Livewire\Freelancer\Index as FreelancerIndex;
use App\Livewire\Freelancer\JoinRequest as FreelancerJoinRequest;
use App\Livewire\Freelancer\JoinRequestView as FreelancerJoinRequestView;
use App\Livewire\Offer\Create as OfferCreate;
use App\Livewire\Offer\Edit as OfferEdit;
use App\Livewire\Offer\Index as OfferIndex;
use App\Livewire\Pages\Index as PagesIndex;
use App\Livewire\Referral\Index as ReferralIndex;
use App\Livewire\Report\AdsReport;
use App\Livewire\Report\AppointmentFreelancer;
use App\Livewire\Report\AppointmentSalon;
use App\Livewire\Report\AppointmentsReport;
use App\Livewire\Report\Category as ReportCategory;
use App\Livewire\Report\CustomersReport;
use App\Livewire\Report\DistrictWiseAppointments;
use App\Livewire\Report\DistrictWiseOrders;
use App\Livewire\Report\EarningsReport;
use App\Livewire\Report\ExecutivesReport;
use App\Livewire\Report\OrdersReport;
use App\Livewire\Report\Referrals;
use App\Livewire\Report\Service;
use App\Livewire\Report\TopProducts;
use App\Livewire\Report\TopServices;
use App\Livewire\Report\UpcomingAppointments;
use App\Livewire\Report\WithdrawalsReport;
use App\Livewire\Salon\Appointment;
use App\Livewire\Salon\AppointmentView;
use App\Livewire\Salon\Create as SalonCreate;
use App\Livewire\Salon\Edit as SalonEdit;
use App\Livewire\Salon\Index as SalonIndex;
use App\Livewire\Salon\JoinRequest;
use App\Livewire\Salon\JoinRequestView;
use App\Livewire\Salon\ProductOrderView;
use App\Livewire\Salon\SalonView;
use App\Livewire\Facilities\Index as FacilityIndex;
use App\Livewire\Facilities\Create as FacilityCreate;
use App\Livewire\Facilities\Edit as FacilityEdit;
use App\Livewire\Service\Create as ServiceCreate;
use App\Livewire\Service\Edit as ServiceEdit;
use App\Livewire\Service\Index as ServiceIndex;
use App\Livewire\Setting\Index as SettingIndex;
use App\Livewire\Shop\Category;
use App\Livewire\Shop\Order;
use App\Livewire\Shop\Product;
use App\Livewire\Shop\SubCategory;
use App\Livewire\User\Index as UserIndex;
use App\Livewire\Withdrawal\Index as Withdrawal;
use App\Livewire\Dealer\Index as DealerIndex;
use App\Livewire\Dealer\Create as DealerCreate;
use App\Livewire\Dealer\Edit as DealerEdit;
use App\Livewire\DeletionRequest\Index as DeletionRequestIndex;
use App\Livewire\Freelancer\ProductOrder as FreelancerProductOrder;
use App\Livewire\Freelancer\ProductOrderView as FreelancerProductOrderView;
use App\Livewire\Report\AppointmentsReminder;
use App\Livewire\Report\HolidaysReport;
use App\Livewire\Report\OffersReport;
use App\Livewire\Report\WalletReport;
use App\Livewire\Salon\ProductOrder;
use App\Livewire\User\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Authentication Routes
Auth::routes();
Route::get('/', [HomeController::class, 'login'])->name('login');
Route::post('/loginsubmit', [HomeController::class, 'loginsubmit'])->name('login.submit');
Route::get('/logout', [HomeController::class, 'logout'])->name('user.logout');

Route::get('/invoice/stream/{filename}', [AppointmentsController::class, 'streamInvoice'])->name('invoice.stream');

//Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    //dashboard
    Route::get('home', [HomeController::class, 'index'])->name('home');

    //master data
    Route::get('filters', FilterIndex::class)->name('filters');
    Route::get('categories', Index::class)->name('categories');
    Route::get('cities', CitiesIndex::class)->name('cities');
    Route::get('services', ServiceIndex::class)->name('services');
    Route::get('service/create', ServiceCreate::class)->name('service.create');
    Route::get('service/edit/{service}', ServiceEdit::class)->name('service.edit');

    //ads banner
    Route::get('banners', BannerIndex::class)->name('banners');
    Route::get('banner/create', Create::class)->name('banner.create');
    Route::get('banner/edit/{banner}', Edit::class)->name('banner.edit');

    //partner ads
    Route::get('partner-ads', PartnerAdsIndex::class)->name('partner-ads');
    Route::get('partner-ads/create', PartnerAdsCreate::class)->name('partner-ads.create');
    Route::get('partner-ads/edit/{banner}', PartnerAdsEdit::class)->name('partner-ads.edit');

    //Offers
    Route::get('offers', OfferIndex::class)->name('offers');
    Route::get('offer/create', OfferCreate::class)->name('offer.create');
    Route::get('offer/edit/{offer}', OfferEdit::class)->name('offer.edit');

    //user
    Route::get('users', UserIndex::class)->name('users');
    Route::get('users/{id}', View::class)->name('user-view');

    //dealers
    Route::get('dealers', DealerIndex::class)->name('dealers');
    Route::get('dealer/create', DealerCreate::class)->name('dealer.create');
    Route::get('dealer/edit/{dealer}', DealerEdit::class)->name('dealer.edit');

    //blog
    Route::get('blogs', BlogIndex::class)->name('blogs');
    Route::get('blog/create', BlogCreate::class)->name('blog.create');
    Route::get('blog/edit/{blog}', BlogEdit::class)->name('blog.edit');

    //referral
    Route::get('referral', ReferralIndex::class)->name('referral');

    //facilities
    Route::get('facilities', FacilityIndex::class)->name('facilities');
    Route::get('facilities/create', FacilityCreate::class)->name('facilities.create');
    Route::get('facilities/edit/{facilities}', FacilityEdit::class)->name('facilities.edit');

    //partner or salon
    Route::get('salons', SalonIndex::class)->name('salons');
    Route::get('salon/create', SalonCreate::class)->name('salon.create');
    Route::get('salon/edit/{user}', SalonEdit::class)->name('salon.edit');
    Route::get('salon/view/{user}', SalonView::class)->name('salon.view');
    Route::get('salon/appointments', Appointment::class)->name('salon.appointments');
    Route::get('salon/appointment/view/{appointment}', AppointmentView::class)->name('salon.appointment.view');
    Route::get('salon/orders', ProductOrder::class)->name('salon.orders');
    Route::get('salon/orders/view/{order}', ProductOrderView::class)->name('salon.order.view');
    Route::get('salon/requests', JoinRequest::class)->name('salon.requests');
    Route::get('salon/request/view/{request}', JoinRequestView::class)->name('salon.request.view');

    //Freelancers
    Route::get('freelancers', FreelancerIndex::class)->name('freelancers');
    Route::get('freelancer/create', FreelancerCreate::class)->name('freelancer.create');
    Route::get('freelancer/edit/{user}', FreelancerEdit::class)->name('freelancer.edit');
    Route::get('freelancer/view/{user}', FreelancerView::class)->name('freelancer.view');
    Route::get('freelancer/appointments', FreelancerAppointment::class)->name('freelancer.appointments');
    Route::get('freelancer/appointment/view/{appointment}', FreelancerAppointmentView::class)->name('freelancer.appointment.view');
    Route::get('freelancer/orders', FreelancerProductOrder::class)->name('freelancer.orders');
    Route::get('freelancer/orders/view/{order}', FreelancerProductOrderView::class)->name('freelancer.order.view');
    Route::get('freelancer/requests', FreelancerJoinRequest::class)->name('freelancer.requests');
    Route::get('freelancer/request/view/{request}', FreelancerJoinRequestView::class)->name('freelancer.request.view');

    //shops
    Route::get('shop/categories', Category::class)->name('shop.categories');
    Route::get('shop/sub-categories', SubCategory::class)->name('shop.subcategories');
    Route::get('shop/products', Product::class)->name('shop.products');
    Route::get('shop/orders', Order::class)->name('shop.orders');
    Route::get('shop/order-details/{order}', ProductOrderView::class)->name('shop.order-details');

    //App Pages
    Route::get('pages', PagesIndex::class)->name('pages');

    //Complaint
    Route::get('complaint', ComplaintIndex::class)->name('complaint');

    //Contact Form
    Route::get('contactform', ContactFromIndex::class)->name('contactform');

    //Reports
    Route::get('report/category', ReportCategory::class)->name('report.category');
    Route::get('report/service', Service::class)->name('report.service');
    Route::get('report/salon/appointment', AppointmentSalon::class)->name('report.appointment.salon');
    Route::get('report/freelancer/appointment', AppointmentFreelancer::class)->name('report.appointment.freelancer');
    Route::get('report/top/products', TopProducts::class)->name('report.top.products');
    Route::get('report/top/services', TopServices::class)->name('report.top.services');
    Route::get('report/customers', CustomersReport::class)->name('report.customers');
    Route::get('report/executives', ExecutivesReport::class)->name('report.executives');
    Route::get('report/offers', OffersReport::class)->name('report.offers');
    Route::get('report/appointments', AppointmentsReport::class)->name('report.appointments');
    Route::get('report/orders', OrdersReport::class)->name('report.orders');
    Route::get('report/wallet', WalletReport::class)->name('report.wallet');
    Route::get('report/withdrawals', WithdrawalsReport::class)->name('report.withdrawals');
    Route::get('report/appointments/upcoming', UpcomingAppointments::class)->name('report.appointments.upcoming');
    Route::get('report/ads', AdsReport::class)->name('report.ads');
    Route::get('report/appointments/city', DistrictWiseAppointments::class)->name('report.appointments.district');
    Route::get('report/orders/city', DistrictWiseOrders::class)->name('report.orders.district');
    Route::get('report/appointments/reminder', AppointmentsReminder::class)->name('report.appointments.reminder');
    Route::get('report/referrals', Referrals::class)->name('report.referrals');
    Route::get('report/holidays', HolidaysReport::class)->name('report.holidays');
    Route::get('report/earnings', EarningsReport::class)->name('report.earnings');
    
    //App Settings
    Route::get('settings', SettingIndex::class)->name('settings');

    //Withdrawal
    Route::get('withdrawals', Withdrawal::class)->name('withdrawals');

    //Deletion Requests
    Route::get('deletion-requests', DeletionRequestIndex::class)->name('deletion-requests');

    //Chat
    Route::get('chat', App\Livewire\Chat\Index::class)->name('chat');

});
