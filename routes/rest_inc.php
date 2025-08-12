<?php

Route::get('/', [$routeController, 'index'])->name('index')->middleware('can:index,App\\Models\\'.$routeModel);
Route::post('/create', [$routeController, 'store'])->name('store')->middleware('can:create,App\\Models\\' . $routeModel);
Route::get('/show/{id}', [$routeController, 'show'])->name('show')->middleware('can:show,App\\Models\\' . $routeModel);
Route::post('/edit/{id}', [$routeController, 'update'])->name('update')->middleware('can:edit,App\\Models\\' . $routeModel);
Route::post('/update/{id}', [$routeController, 'update'])->name('update')->middleware('can:edit,App\\Models\\' . $routeModel);
Route::put('/update/{id}', [$routeController, 'update'])->name('update')->middleware('can:edit,App\\Models\\' . $routeModel);
Route::patch('/update/{id}', [$routeController, 'update'])->name('update')->middleware('can:edit,App\\Models\\' . $routeModel);
Route::delete('/delete/{id}', [$routeController, 'destroy'])->name('destroy')->middleware('can:destroy,App\\Models\\' . $routeModel);