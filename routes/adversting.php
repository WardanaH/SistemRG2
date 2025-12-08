<?php

use Illuminate\Support\Facades\Route;

Route::view('/profile-restu-guru', 'adversting.pages.home')->name('home');
Route::view('/about', 'adversting.pages.about')->name('about');
Route::view('/services', 'adversting.pages.services')->name('services');
Route::view('/contact', 'adversting.pages.contact')->name('contact');
