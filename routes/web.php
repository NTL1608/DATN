<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
});

Route::get('errors-403', function () {
    return view('errors.403');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('/login', 'LoginController@login')->name('admin.login');
        Route::post('/login', 'LoginController@postLogin');
        Route::get('/register', 'RegisterController@getRegister')->name('admin.register');
        Route::post('/register', 'RegisterController@postRegister');
        Route::get('/logout', 'LoginController@logout')->name('admin.logout');
        Route::get('/forgot/password', 'ForgotPasswordController@forgotPassword')->name('admin.forgot.password');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/home', 'HomeController@index')->name('admin.home')->middleware('permission:truy-cap-he-thong|toan-quyen-quan-ly');

        Route::post('/load/data', 'LocationController@loadData')->name('ajax.post.load.location');

        Route::group(['prefix' => 'group-permission'], function () {
            Route::get('/', 'GroupPermissionController@index')->name('group.permission.index');
            Route::get('/create', 'GroupPermissionController@create')->name('group.permission.create');
            Route::post('/create', 'GroupPermissionController@store');

            Route::get('/update/{id}', 'GroupPermissionController@edit')->name('group.permission.update');
            Route::post('/update/{id}', 'GroupPermissionController@update');

            Route::get('/delete/{id}', 'GroupPermissionController@destroy')->name('group.permission.delete');
        });

        Route::group(['prefix' => 'permission'], function () {
            Route::get('/', 'PermissionController@index')->name('permission.index');
            Route::get('/create', 'PermissionController@create')->name('permission.create');
            Route::post('/create', 'PermissionController@store');

            Route::get('/update/{id}', 'PermissionController@edit')->name('permission.update');
            Route::post('/update/{id}', 'PermissionController@update');

            Route::get('/delete/{id}', 'PermissionController@delete')->name('permission.delete');
        });

        Route::group(['prefix' => 'role'], function () {
            Route::get('/', 'RoleController@index')->name('role.index')->middleware('permission:danh-sach-vai-tro|toan-quyen-quan-ly');
            Route::get('/create', 'RoleController@create')->name('role.create')->middleware('permission:them-moi-vai-tro|toan-quyen-quan-ly');
            Route::post('/create', 'RoleController@store');

            Route::get('/update/{id}', 'RoleController@edit')->name('role.update')->middleware('permission:chinh-sua-vai-tro|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'RoleController@update');

            Route::get('/delete/{id}', 'RoleController@delete')->name('role.delete')->middleware('permission:xoa-vai-tro|toan-quyen-quan-ly');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'UserController@index')->name('user.index')->middleware('permission:danh-sach-bac-si|toan-quyen-quan-ly');
            Route::get('/create', 'UserController@create')->name('user.create')->middleware('permission:them-moi-bac-si|toan-quyen-quan-ly');
            Route::post('/create', 'UserController@store');

            Route::get('/update/{id}', 'UserController@edit')->name('user.update')->middleware('permission:chinh-sua-bac-si|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'UserController@update');

            Route::get('/delete/{id}', 'UserController@delete')->name('user.delete')->middleware('permission:xoa-bac-si|toan-quyen-quan-ly');

            Route::get('/show', 'UserController@show')->name('user.show');
        });

        Route::group(['prefix' => 'patient'], function () {
            Route::get('/', 'PatientController@index')->name('patient.index')->middleware('permission:danh-sach-benh-nhan|toan-quyen-quan-ly');
            Route::get('/create', 'PatientController@create')->name('patient.create')->middleware('permission:them-moi-benh-nhan|toan-quyen-quan-ly');
            Route::post('/create', 'PatientController@store');

            Route::get('/update/{id}', 'PatientController@edit')->name('patient.update')->middleware('permission:chinh-sua-benh-nhan|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'PatientController@update');



            Route::get('/delete/{id}', 'PatientController@delete')->name('patient.delete')->middleware('permission:xoa-benh-nhan|toan-quyen-quan-ly');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', 'ProfileController@index')->name('profile.index');
            Route::post('/update/{id}', 'ProfileController@update')->name('profile.update');
            Route::get('/change/password', 'ProfileController@changePassword')->name('profile.change.password');
            Route::post('post/change/password', 'ProfileController@postChangePassword')->name('profile.post.change.password');
        });

        Route::group(['prefix' => 'slide'], function () {
            Route::get('/', 'SlideController@index')->name('slide.index')->middleware('permission:truy-cap-he-thong|toan-quyen-quan-ly');
            Route::get('/create', 'SlideController@create')->name('slide.create')->middleware('permission:truy-cap-he-thong|toan-quyen-quan-ly');
            Route::post('/create', 'SlideController@store');

            Route::get('/update/{id}', 'SlideController@edit')->name('slide.update')->middleware('permission:truy-cap-he-thong|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'SlideController@update');

            Route::get('/delete/{id}', 'SlideController@delete')->name('slide.delete')->middleware('permission:truy-cap-he-thong|toan-quyen-quan-ly');
        });

        Route::group(['prefix' => 'clinic'], function () {
            Route::get('/', 'ClinicController@index')->name('clinic.index')->middleware('permission:danh-sach-phong-kham|toan-quyen-quan-ly');
            Route::get('/create', 'ClinicController@create')->name('clinic.create')->middleware('permission:them-moi-phong-kham|toan-quyen-quan-ly');
            Route::post('/create', 'ClinicController@store');

            Route::get('/show/{id}', 'ClinicController@show')->name('clinic.show');

            Route::get('/update/{id}', 'ClinicController@edit')->name('clinic.update')->middleware('permission:chinh-sua-phong-kham|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'ClinicController@update');

            Route::get('/delete/{id}', 'ClinicController@delete')->name('clinic.delete')->middleware('permission:xoa-phong-kham|toan-quyen-quan-ly');

            Route::post('/ajax/load/specialty', 'ClinicController@loadSpecialty')->name('ajax.load.specialty');
        });

        Route::group(['prefix' => 'specialty'], function () {
            Route::get('/', 'SpecialtyController@index')->name('specialty.index')->middleware('permission:danh-sach-dich-vu|toan-quyen-quan-ly');
            Route::get('/create', 'SpecialtyController@create')->name('specialty.create')->middleware('permission:them-moi-dich-vu|toan-quyen-quan-ly');
            Route::post('/create', 'SpecialtyController@store');

            Route::get('/update/{id}', 'SpecialtyController@edit')->name('specialty.update')->middleware('permission:chinh-sua-dich-vu|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'SpecialtyController@update');

            Route::get('/delete/{id}', 'SpecialtyController@delete')->name('specialty.delete')->middleware('permission:xoa-dich-vu|toan-quyen-quan-ly');
        });

        Route::group(['prefix' => 'schedule'], function () {
            Route::get('/', 'ScheduleController@index')->name('schedule.index')->middleware('permission:danh-sach-lich-lam-viec|toan-quyen-quan-ly');
            Route::get('/create', 'ScheduleController@create')->name('schedule.create')->middleware('permission:dang-ky-lich-lam-viec|toan-quyen-quan-ly');
            Route::post('/create', 'ScheduleController@store');

            Route::get('/update/{id}', 'ScheduleController@edit')->name('schedule.update')->middleware('permission:chinh-sua-lich-lam-viec|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'ScheduleController@update');

            Route::get('/delete/{id}', 'ScheduleController@delete')->name('schedule.delete')->middleware('permission:xoa-lich-lam-viec|toan-quyen-quan-ly');

            Route::post('/load/list/times', 'ScheduleController@loadListTimes')->name('load.list.times');
        });
        Route::group(['prefix' => 'booking'], function () {
            Route::get('/', 'BookingController@index')->name('booking.index')->middleware('permission:danh-sach-lich-kham|toan-quyen-quan-ly');
            Route::get('/update/{id}', 'BookingController@edit')->name('booking.update')->middleware('permission:tra-ket-qua-kham|toan-quyen-quan-ly');
            Route::post('/result/booking/{id}', 'BookingController@resultBooking')->name('result.booking');
            Route::get('/delete/{id}', 'BookingController@delete')->name('booking.delete')->middleware('permission:xoa-lich-kham|toan-quyen-quan-ly');
            Route::get('/update/status/{id}', 'BookingController@update')->name('booking.update.status')->middleware('permission:cap-nhat-trang-thai|toan-quyen-quan-ly');

            Route::get('/medical/exam/form/{id}', 'BookingController@show')->name('booking.medical.exam.form');

            Route::get('/result/print/{id}', 'BookingController@resultPrint')->name('booking.result.print');

            Route::get('report/statistics', 'BookingReportStatisticController@statistics')->name('booking.report.statistics');
            Route::post('report/clinic', 'BookingReportStatisticController@reportClinic')->name('booking.report.clinic');
            Route::post('report/service', 'BookingReportStatisticController@reportService')->name('booking.report.service');
            Route::post('report/doctor', 'BookingReportStatisticController@reportDoctor')->name('booking.report.doctor');
        });

        Route::group(['prefix' => 'contact'], function () {
            Route::get('/', 'ContactController@index')->name('contact.index')->middleware('permission:danh-sach-lien-he|toan-quyen-quan-ly');
            Route::get('/delete/{id}', 'ContactController@delete')->name('contact.delete')->middleware('permission:xoa-lien-he|toan-quyen-quan-ly');
        });

        Route::group(['prefix' => 'article'], function () {
            Route::get('/', 'ArticleController@index')->name('article.index')->middleware('permission:danh-sach-bai-viet|toan-quyen-quan-ly');
            Route::get('/create', 'ArticleController@create')->name('article.create')->middleware('permission:them-moi-bai-viet|toan-quyen-quan-ly');
            Route::post('/create', 'ArticleController@store');

            Route::get('/update/{id}', 'ArticleController@edit')->name('article.update')->middleware('permission:chinh-sua-bai-viet|toan-quyen-quan-ly');
            Route::post('/update/{id}', 'ArticleController@update');

            Route::get('/delete/{id}', 'ArticleController@delete')->name('article.delete')->middleware('permission:xoa-bai-viet|toan-quyen-quan-ly');
        });

        Route::group(['prefix' => 'rating'], function () {
            Route::get('/', 'RatingController@index')->name('rating.index')->middleware('permission:danh-sach-danh-gia|toan-quyen-quan-ly');
            Route::get('/delete/{id}', 'RatingController@delete')->name('rating.delete')->middleware('permission:xoa-danh-gia|toan-quyen-quan-ly');
        });
    });
});

Route::group(['namespace' => 'Page', 'middleware' => 'locale'], function () {
    Route::get('change-language/{language}', 'ChangeLanguageController@changeLanguage')->name('user.change-language');

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('/dang-nhap.html', 'LoginController@login')->name('page.user.login');
        Route::post('/account/login', 'LoginController@postLogin')->name('account.login');
        Route::get('/dang-ky.html', 'RegisterController@register')->name('page.user.register');
        Route::post('/register/account', 'RegisterController@postRegister')->name('account.register');
        Route::get('/logout', 'LoginController@logout')->name('page.user.logout');
        Route::get('/forgot/password', 'ForgotPasswordController@forgotPassword')->name('page.user.forgot.password');
    });

    Route::group(['middleware' => ['users']], function () {
        Route::get('thong-tin-tai-khoan.html', 'AccountController@infoAccount')->name('info.account');
        Route::post('/update/info/account/{id}', 'AccountController@updateInfoAccount')->name('update.info.account');
        Route::get('danh-sach-dat-lich.html', 'AccountController@bookings')->name('users.bookings');
        Route::get('thay-doi-mat-khau.html', 'AccountController@changePassword')->name('change.password');
        Route::post('change/password', 'AccountController@postChangePassword')->name('post.change.password');
        Route::get('cancel/booking/{id}', 'AccountController@cancelBooking')->name('cancel.booking');
    });

    Route::post('page/load/data', 'LocationController@loadData')->name('page.ajax.post.load.location');

    Route::get('/', 'HomeController@index')->name('user.home.index');
    Route::get('/gioi-thieu.html', 'HomeController@about')->name('page.about');
    Route::get('/lien-he.html', 'HomeController@contact')->name('page.contact');

    Route::get('/dich-vu.html', 'SpecialtyController@index')->name('page.specialty.index');
    Route::get('/dich-vu/{id}/{slug}.html', 'SpecialtyController@detail')->name('specialty.detail');

    Route::get('/khoa-kham-benh.html', 'ClinicController@index')->name('page.clinic.index');
    Route::get('/khoa-kham-benh/{id}/{slug}.html', 'ClinicController@detail')->name('clinic.detail');
    Route::post('/ajax/load/specialty', 'ClinicController@loadSpecialty')->name('page.ajax.load.specialty');

    Route::get('/tim-kiem.html', 'DoctorController@search')->name('page.search');
    Route::get('/bac-si/{id}/{slug}.html', 'DoctorController@doctorInfo')->name('doctor.detail');

    Route::get('/dat-lich-kham.html/{id}', 'BookingController@booking')->name('booking.appointment');
    Route::post('booking/appointment/{id}', 'BookingController@bookingAppointment')->name('post.booking.appointment');
    Route::get('/{id}/phieu-dang-ky-kham.html', 'BookingController@printMedicalExamForm')->name('booking.print.medical.exam.form');

    Route::post('send/contact', 'ContactController@sendContact')->name('send.contact');

    Route::get('/xac-nhan-dat-lich.html/{id}', 'BookingController@confirm')->name('booking.confirm');

    Route::get('/tin-tuc.html', 'ArticleController@index')->name('page.article.index');

    Route::get('/tin-tuc/{id}/{slug}.html', 'ArticleController@detail')->name('article.detail');

    Route::post('user/rating/{id}', 'RatingController@rating')->name('user.rating');
});

// Chat routes
Route::get('/chat', function () {
    return view('chat');
});

Route::get('/chat/test', function () {
    return view('chat_test');
});
