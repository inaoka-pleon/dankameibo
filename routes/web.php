<?php

use App\Http\Controllers\AkihiganDocumentController;
use App\Http\Controllers\AkihiganHeaderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AreakaikiListController;
use App\Http\Controllers\ArealistController;
use App\Http\Controllers\AtenaDetailController;
use App\Http\Controllers\AtenaHeaderController;
use App\Http\Controllers\DankadivisionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\MortuarytabletController;
use App\Http\Controllers\DankaController;
use App\Http\Controllers\DankalistController;
use App\Http\Controllers\EraController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\GeneralMastersController;
use App\Http\Controllers\GeneralPostcardController;
use App\Http\Controllers\GozikaikaihiListController;
use App\Http\Controllers\GozikailistsController;
use App\Http\Controllers\HanamatsurilistController;
use App\Http\Controllers\HaruhiganDocumentController;
use App\Http\Controllers\HaruhiganHeaderController;
use App\Http\Controllers\HatsubonDocumentController;
use App\Http\Controllers\HatsubonlistController;
use App\Http\Controllers\HatsubonlistDocumentController;
use App\Http\Controllers\HondoulistController;
use App\Http\Controllers\JikakuController;
use App\Http\Controllers\KaihiListController;
use App\Http\Controllers\KaikiController;
use App\Http\Controllers\KaikireiboListController;
use App\Http\Controllers\KaimyouCheckController;
use App\Http\Controllers\KakochoController;
use App\Http\Controllers\KakuiereiboListController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberlistController;
use App\Http\Controllers\NendoreiboListController;
use App\Http\Controllers\NenkaiDocumentController;
use App\Http\Controllers\NenkailistController;
use App\Http\Controllers\NenkiDocumentController;
use App\Http\Controllers\NenkilistController;
use App\Http\Controllers\PaymentSlipController;
use App\Http\Controllers\PostcardController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\SubtitleController;
use App\Http\Controllers\TaiyaListController;
use App\Http\Controllers\TanagyouDocumentController;
use App\Http\Controllers\TanagyouHeaderController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TempleController;
use App\Http\Controllers\TemplelistController;
use App\Http\Controllers\TemplelistprintController;
use App\Http\Controllers\TempleMasterController;
use App\Http\Controllers\TempleofficeController;
use App\Http\Controllers\TitleController;
use App\Models\AtenaHeader;
use App\Models\HaruhiganHeader;
use App\Models\HatsubonlistDocument;

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



Route::resource('/areas', AreaController::class)
     ->names([
        'index'     => 'area.index',
        'show'      => 'area.show',
        'create'    => 'area.create',
        'edit'      => 'area.edit',
        'update'    => 'area.update',
        'destroy'   => 'area.destroy',
        'store'     => 'area.store'
     ]);

Route::resource('/dankadivisions', DankadivisionController::class)
     ->names([
        'index'     => 'dankadivision.index',
        'show'      => 'dankadivision.show',
        'create'    => 'dankadivision.create',
        'edit'      => 'dankadivision.edit',
        'update'    => 'dankadivision.update',
        'destroy'   => 'dankadivision.destroy',
        'store'     => 'dankadivision.store'
     ]);

Route::resource('/positions', PositionController::class)
     ->names([
        'index'     => 'position.index',
        'show'      => 'position.show',
        'create'    => 'position.create',
        'edit'      => 'position.edit',
        'update'    => 'position.update',
        'destroy'   => 'position.destroy',
        'store'     => 'position.store'
     ]);

Route::resource('/occupations', OccupationController::class)
     ->names([
        'index'     => 'occupation.index',
        'show'      => 'occupation.show',
        'create'    => 'occupation.create',
        'edit'      => 'occupation.edit',
        'update'    => 'occupation.update',
        'destroy'   => 'occupation.destroy',
        'store'     => 'occupation.store'
     ]);


Route::resource('/mortuarytablets', MortuarytabletController::class)
     ->names([
        'index'     => 'mortuarytablet.index',
        'show'      => 'mortuarytablet.show',
        'create'    => 'mortuarytablet.create',
        'edit'      => 'mortuarytablet.edit',
        'update'    => 'mortuarytablet.update',
        'destroy'   => 'mortuarytablet.destroy',
        'store'     => 'mortuarytablet.store'
     ]);

Route::resource('/templeoffices', TempleofficeController::class)
     ->names([
         'index'     => 'templeoffice.index',
         'show'      => 'templeoffice.show',
         'create'    => 'templeoffice.create',
         'edit'      => 'templeoffice.edit',
         'update'    => 'templeoffice.update',
         'destroy'   => 'templeoffice.destroy',
         'store'     => 'templeoffice.store'
     ]);

Route::resource('/titles', TitleController::class)
   ->names([
      'index'     => 'title.index',
      'show'      => 'title.show',
      'create'    => 'title.create',
      'edit'      => 'title.edit',
      'update'    => 'title.update',
      'destroy'   => 'title.destroy',
      'store'     => 'title.store'
   ]);     

Route::resource('/subtitles', SubtitleController::class)
   ->names([
      'index'     => 'subtitle.index',
      'show'      => 'subtitle.show',
      'create'    => 'subtitle.create',
      'edit'      => 'subtitle.edit',
      'update'    => 'subtitle.update',
      'destroy'   => 'subtitle.destroy',
      'store'     => 'subtitle.store'
   ]);  

Route::resource('/teachers', TeacherController::class)
   ->names([
      'index'     => 'teacher.index',
      'show'      => 'teacher.show',
      'create'    => 'teacher.create',
      'edit'      => 'teacher.edit',
      'update'    => 'teacher.update',
      'destroy'   => 'teacher.destroy',
      'store'     => 'teacher.store'
   ]);  

Route::resource('/jikakus', JikakuController::class)
   ->names([
      'index'     => 'jikaku.index',
      'show'      => 'jikaku.show',
      'create'    => 'jikaku.create',
      'edit'      => 'jikaku.edit',
      'update'    => 'jikaku.update',
      'destroy'   => 'jikaku.destroy',
      'store'     => 'jikaku.store'
   ]); 

Route::resource('/qualifications', QualificationController::class)
   ->names([
      'index'     => 'qualification.index',
      'show'      => 'qualification.show',
      'create'    => 'qualification.create',
      'edit'      => 'qualification.edit',
      'update'    => 'qualification.update',
      'destroy'   => 'qualification.destroy',
      'store'     => 'qualification.store'
   ]);

Route::resource('/kaikis', KaikiController::class)
   ->names([
       'index'     => 'kaiki.index',
       'show'      => 'kaiki.show',
       'create'    => 'kaiki.create',
       'edit'      => 'kaiki.edit',
       'update'    => 'kaiki.update',
       'destroy'   => 'kaiki.destroy',
       'store'     => 'kaiki.store'
   ]);

Route::resource('/eras', EraController::class)
   ->names([
       'index'     => 'era.index',
       'show'      => 'era.show',
       'create'    => 'era.create',
       'edit'      => 'era.edit',
       'update'    => 'era.update',
       'destroy'   => 'era.destroy',
       'store'     => 'era.store'
   ]);

Route::get('dankas',                                  [DankaController::class, 'index'                      ])->name('danka.index');
Route::get('dankas/create',                           [DankaController::class, 'create'                     ])->name('danka.create');
Route::post('dankas',                                 [DankaController::class, 'store'                      ])->name('danka.store');
Route::get('dankas/{id}',                             [DankaController::class, 'show'                       ])->name('danka.show');
Route::get('dankas/{id}/edit',                        [DankaController::class, 'edit'                       ])->name('danka.edit');
Route::patch('dankas/{id}',                           [DankaController::class, 'update'                     ])->name('danka.update');
Route::delete('dankas/{id}',                          [DankaController::class, 'destroy'                    ])->name('danka.destroy');
Route::get('dankas/{id}/sesyu',                       [DankaController::class, 'chiefmourner_change'        ])->name('danka.chiefmourner.change');

Route::get('followers',                               [FollowerController::class, 'index'                   ])->name('follower.index');
Route::post('followers',                              [FollowerController::class, 'store'                   ])->name('follower.store');
Route::get('followers/{id}',                          [FollowerController::class, 'show'                    ])->name('follower.show');
Route::get('followers/{danka_id}/create',             [FollowerController::class, 'create'                  ])->name('follower.create');
Route::get('followers/{id}/edit',                     [FollowerController::class, 'edit'                    ])->name('follower.edit');
Route::patch('followers/{id}',                        [FollowerController::class, 'update'                  ])->name('follower.update');
Route::delete('followers/{danka_id}/{id}',            [FollowerController::class, 'destroy'                 ])->name('follower.destroy');

Route::get('kakochos',                                [KakochoController::class, 'index'                    ])->name('kakocho.index');
Route::post('kakochos',                               [KakochoController::class, 'store'                    ])->name('kakocho.store');
Route::get('kakochos/{id}',                           [KakochoController::class, 'show'                     ])->name('kakocho.show');
Route::get('kakochos/{danka_id}/create',              [KakochoController::class, 'create'                   ])->name('kakocho.create');
Route::get('kakochos/{id}/edit',                      [KakochoController::class, 'edit'                     ])->name('kakocho.edit');
Route::patch('kakochos/{id}',                         [KakochoController::class, 'update'                   ])->name('kakocho.update');
Route::delete('kakochos/{danka_id}/{id}',             [KakochoController::class, 'destroy'                  ])->name('kakocho.destroy');

Route::get('nenkilists/{id}',                         [NenkilistController::class, 'index'                  ])->name('nenkilist.index');
Route::patch('nenkilists/{id}',                       [NenkilistController::class, 'update'                 ])->name('nenkilist.update');
Route::get('nenkilists/{id}/print',                   [NenkilistController::class, 'print'                  ])->name('nenkilist.print');

Route::get('taiyalists/{id}',                         [TaiyaListController::class, 'index'                  ])->name('taiyalist.index');
Route::get('taiyalists/{id}/print',                   [TaiyaListController::class, 'print'                  ])->name('taiyalist.print');

Route::get('nenkidocuments/create',                   [NenkiDocumentController::class, 'create'             ])->name('nenkidocument.create');
Route::post('nenkidocuments',                         [NenkiDocumentController::class, 'store'              ])->name('nenkidocument.store');
Route::get('nenkidocuments/{id}/edit',                [NenkiDocumentController::class, 'edit'               ])->name('nenkidocument.edit');
Route::patch('nenkidocuments/{id}',                   [NenkiDocumentController::class, 'update'             ])->name('nenkidocument.update');
Route::get('nenkidocuments/create_or_edit',           [NenkiDocumentController::class, 'createOrEdit'       ])->name('nenkidocument.createOrEdit');

Route::get('generalmasters',                          [GeneralMastersController::class, 'index'             ])->name('generalmaster.index');
Route::get('generalmasters/create',                   [GeneralMastersController::class, 'create'            ])->name('generalmaster.create');
Route::post('generalmasters',                         [GeneralMastersController::class, 'store'             ])->name('generalmaster.store');
Route::get('generalmasters/{id}',                     [GeneralMastersController::class, 'show'              ])->name('generalmaster.show');
Route::get('generalmasters/{id}/edit',                [GeneralMastersController::class, 'edit'              ])->name('generalmaster.edit');
Route::patch('generalmasters/{id}',                   [GeneralMastersController::class, 'update'            ])->name('generalmaster.update');
Route::delete('generalmasters/{id}',                  [GeneralMastersController::class, 'destroy'           ])->name('generalmaster.destroy');

Route::get('templemasters',                           [TempleMasterController::class, 'index'               ])->name('templemaster.index');
Route::get('templemasters/create',                    [TempleMasterController::class, 'create'              ])->name('templemaster.create');
Route::post('templemasters',                          [TempleMasterController::class, 'store'               ])->name('templemaster.store');
Route::get('templemasters/{id}/edit',                 [TempleMasterController::class, 'edit'                ])->name('templemaster.edit');
Route::patch('templemasters/{id}',                    [TempleMasterController::class, 'update'              ])->name('templemaster.update');
Route::get('templemasters/create_or_edit',            [TempleMasterController::class, 'createOrEdit'        ])->name('templemaster.createOrEdit');

Route::get('temples',                                 [TempleController::class, 'index'                     ])->name('temple.index');
Route::get('temples/create',                          [TempleController::class, 'create'                    ])->name('temple.create');
Route::post('temples',                                [TempleController::class, 'store'                     ])->name('temple.store');
Route::get('temples/{id}',                            [TempleController::class, 'show'                      ])->name('temple.show');
Route::get('temples/{id}/edit',                       [TempleController::class, 'edit'                      ])->name('temple.edit');
Route::patch('temples/{id}',                          [TempleController::class, 'update'                    ])->name('temple.update');
Route::delete('temples/{id}',                         [TempleController::class, 'destroy'                   ])->name('temple.destroy');
Route::get('temples/{id}/jushoku',                    [TempleController::class, 'chiefpriest_change'        ])->name('temple.chiefpriest.change');

Route::get('members',                                 [MemberController::class, 'index'                     ])->name('member.index');
Route::get('members/{temple_id}/create',              [MemberController::class, 'create'                    ])->name('member.create');
Route::post('members',                                [MemberController::class, 'store'                     ])->name('member.store');
Route::get('members/{id}',                            [MemberController::class, 'show'                      ])->name('member.show');
Route::get('members/{id}/edit',                       [MemberController::class, 'edit'                      ])->name('member.edit');
Route::patch('members/{id}',                          [MemberController::class, 'update'                    ])->name('member.update');
Route::delete('members/{temple_id}/{id}',             [MemberController::class, 'destroy'                   ])->name('member.destroy');

Route::get('arealists',                               [ArealistController::class, 'index'                   ])->name('arealist.index');
Route::get('arealist/print',                          [ArealistController::class, 'print'                   ])->name('arealist.print');
Route::get('arealist/postcard_print',                 [ArealistController::class, 'postcard_print'          ])->name('arealist.postcard_print');
Route::get('arealist/envelope4_print',                [ArealistController::class, 'envelope4_print'         ])->name('arealist.envelope4_print');
Route::get('arealist/envelope3_print',                [ArealistController::class, 'envelope3_print'         ])->name('arealist.envelope3_print');
Route::get('arealist/square3_print',                  [ArealistController::class, 'square3_print'           ])->name('arealist.square3_print');
Route::get('arealist/square2_print',                  [ArealistController::class, 'square2_print'           ])->name('arealist.square2_print');
Route::get('arealist/label_print',                    [ArealistController::class, 'label_print'             ])->name('arealist.label_print');

Route::get('tanagyouheaders',                         [TanagyouHeaderController::class, 'index'             ])->name('tanagyouheader.index');
Route::get('tanagyouheaders/create',                  [TanagyouHeaderController::class, 'create'            ])->name('tanagyouheader.create');
Route::post('tanagyouheaders',                        [TanagyouHeaderController::class, 'store'             ])->name('tanagyouheader.store');
Route::get('tanagyouheaders/{id}/edit',               [TanagyouHeaderController::class, 'edit'              ])->name('tanagyouheader.edit');
Route::patch('tanagyouheaders/{id}',                  [TanagyouHeaderController::class, 'update'            ])->name('tanagyouheader.update');
Route::get('tanagyouheaders/{id}/print',              [TanagyouHeaderController::class, 'print'             ])->name('tanagyouheader.print');
Route::get('tanagyouheaders/{id}/postcard_print',     [TanagyouHeaderController::class, 'postcard_print'    ])->name('tanagyouheader.postcard_print');
Route::get('tanagyouheaders/{id}/envelope4_print',    [TanagyouHeaderController::class, 'envelope4_print'   ])->name('tanagyouheader.envelope4_print');
Route::get('tanagyouheaders/{id}/envelope3_print',    [TanagyouHeaderController::class, 'envelope3_print'   ])->name('tanagyouheader.envelope3_print');
Route::get('tanagyouheaders/{id}/square3_print',      [TanagyouHeaderController::class, 'square3_print'     ])->name('tanagyouheader.square3_print');
Route::get('tanagyouheaders/{id}/square2_print',      [TanagyouHeaderController::class, 'square2_print'     ])->name('tanagyouheader.square2_print');
Route::get('tanagyouheaders/{id}/label_print',        [TanagyouHeaderController::class, 'label_print'       ])->name('tanagyouheader.label_print');
Route::get('tanagyouheaders/{id}/back_print',         [TanagyouHeaderController::class, 'back_print'        ])->name('tanagyouheader.back_print');
Route::get('tanagyouheaders/{id}/danka_search',       [TanagyouHeaderController::class, 'danka_search'      ])->name('tanagyouheader.danka_search');
Route::post('tanagyouheaders/{id}/add_danka_data',    [TanagyouHeaderController::class, 'add_danka_data'    ])->name('tanagyouheader.add_danka_data');

Route::get('tanagyoudocuments/create',                [TanagyouDocumentController::class, 'create'          ])->name('tanagyoudocument.create');
Route::post('tanagyoudocuments',                      [TanagyouDocumentController::class, 'store'           ])->name('tanagyoudocument.store');
Route::get('tanagyoudocuments/{id}/edit',             [TanagyouDocumentController::class, 'edit'            ])->name('tanagyoudocument.edit');
Route::patch('tanagyoudocuments/{id}',                [TanagyouDocumentController::class, 'update'          ])->name('tanagyoudocument.update');
Route::get('tanagyoudocuments/create_or_edit',        [TanagyouDocumentController::class, 'createOrEdit'    ])->name('tanagyoudocument.createOrEdit');
Route::get('tanagyoudocuments/{id}/print',            [TanagyouDocumentController::class, 'print'           ])->name('tanagyoudocument.print');

Route::get('hatsubondocuments/create',                [HatsubonDocumentController::class, 'create'          ])->name('hatsubondocument.create');
Route::post('hatsubondocuments',                      [HatsubonDocumentController::class, 'store'           ])->name('hatsubondocument.store');
Route::get('hatsubondocuments/{id}/edit',             [HatsubonDocumentController::class, 'edit'            ])->name('hatsubondocument.edit');
Route::patch('hatsubondocuments/{id}',                [HatsubonDocumentController::class, 'update'          ])->name('hatsubondocument.update');
Route::get('hatsubondocuments/create_or_edit',        [HatsubonDocumentController::class, 'createOrEdit'    ])->name('hatsubondocument.createOrEdit');
Route::get('hatsubondocuments/{id}/print',            [HatsubonDocumentController::class, 'print'           ])->name('hatsubondocument.print');

Route::get('haruhiganheaders',                        [HaruhiganHeaderController::class, 'index'            ])->name('haruhiganheader.index');
Route::get('haruhiganheaders/create',                 [HaruhiganHeaderController::class, 'create'           ])->name('haruhiganheader.create');
Route::post('haruhiganheaders',                       [HaruhiganHeaderController::class, 'store'            ])->name('haruhiganheader.store');
Route::get('haruhiganheaders/{id}/edit',              [HaruhiganHeaderController::class, 'edit'             ])->name('haruhiganheader.edit');
Route::patch('haruhiganheaders/{id}',                 [HaruhiganHeaderController::class, 'update'           ])->name('haruhiganheader.update');
Route::get('haruhiganheaders/{id}/print',             [HaruhiganHeaderController::class, 'print'            ])->name('haruhiganheader.print');
Route::get('haruhiganheaders/{id}/postcard_print',    [HaruhiganHeaderController::class, 'postcard_print'   ])->name('haruhiganheader.postcard_print');
Route::get('haruhiganheaders/{id}/envelope4_print',   [HaruhiganHeaderController::class, 'envelope4_print'  ])->name('haruhiganheader.envelope4_print');
Route::get('haruhiganheaders/{id}/envelope3_print',   [HaruhiganHeaderController::class, 'envelope3_print'  ])->name('haruhiganheader.envelope3_print');
Route::get('haruhiganheaders/{id}/square3_print',     [HaruhiganHeaderController::class, 'square3_print'    ])->name('haruhiganheader.square3_print');
Route::get('haruhiganheaders/{id}/square2_print',     [HaruhiganHeaderController::class, 'square2_print'    ])->name('haruhiganheader.square2_print');
Route::get('haruhiganheaders/{id}/label_print',       [HaruhiganHeaderController::class, 'label_print'      ])->name('haruhiganheader.label_print');
Route::get('haruhiganheaders/{id}/back_print',        [HaruhiganHeaderController::class, 'back_print'       ])->name('haruhiganheader.back_print');
Route::get('haruhiganheaders/{id}/danka_search',      [HaruhiganHeaderController::class, 'danka_search'     ])->name('haruhiganheader.danka_search');
Route::post('haruhiganheaders/{id}/add_danka_data',   [HaruhiganHeaderController::class, 'add_danka_data'   ])->name('haruhiganheader.add_danka_data');

Route::get('haruhigandocuments/create',               [HaruhiganDocumentController::class, 'create'         ])->name('haruhigandocument.create');
Route::post('haruhigandocuments',                     [HaruhiganDocumentController::class, 'store'          ])->name('haruhigandocument.store');
Route::get('haruhigandocuments/{id}/edit',            [HaruhiganDocumentController::class, 'edit'           ])->name('haruhigandocument.edit');
Route::patch('haruhigandocuments/{id}',               [HaruhiganDocumentController::class, 'update'         ])->name('haruhigandocument.update');
Route::get('haruhigandocuments/create_or_edit',       [HaruhiganDocumentController::class, 'createOrEdit'   ])->name('haruhigandocument.createOrEdit');
Route::get('haruhigandocuments/{id}/print',           [HaruhiganDocumentController::class, 'print'          ])->name('haruhigandocument.print');

Route::get('akihiganheaders',                         [AkihiganHeaderController::class, 'index'             ])->name('akihiganheader.index');
Route::get('akihiganheaders/create',                  [AkihiganHeaderController::class, 'create'            ])->name('akihiganheader.create');
Route::post('akihiganheaders',                        [AkihiganHeaderController::class, 'store'             ])->name('akihiganheader.store');
Route::get('akihiganheaders/{id}/edit',               [AkihiganHeaderController::class, 'edit'              ])->name('akihiganheader.edit');
Route::patch('akihiganheaders/{id}',                  [AkihiganHeaderController::class, 'update'            ])->name('akihiganheader.update');
Route::get('akihiganheaders/{id}/print',              [AkihiganHeaderController::class, 'print'             ])->name('akihiganheader.print');
Route::get('akihiganheaders/{id}/postcard_print',     [AkihiganHeaderController::class, 'postcard_print'    ])->name('akihiganheader.postcard_print');
Route::get('akihiganheaders/{id}/envelope4_print',    [AkihiganHeaderController::class, 'envelope4_print'   ])->name('akihiganheader.envelope4_print');
Route::get('akihiganheaders/{id}/envelope3_print',    [AkihiganHeaderController::class, 'envelope3_print'   ])->name('akihiganheader.envelope3_print');
Route::get('akihiganheaders/{id}/square3_print',      [AkihiganHeaderController::class, 'square3_print'     ])->name('akihiganheader.square3_print');
Route::get('akihiganheaders/{id}/square2_print',      [AkihiganHeaderController::class, 'square2_print'     ])->name('akihiganheader.square2_print');
Route::get('akihiganheaders/{id}/label_print',        [AkihiganHeaderController::class, 'label_print'       ])->name('akihiganheader.label_print');
Route::get('akihiganheaders/{id}/back_print',         [AkihiganHeaderController::class, 'back_print'        ])->name('akihiganheader.back_print');
Route::get('akihiganheaders/{id}/danka_search',       [AkihiganHeaderController::class, 'danka_search'      ])->name('akihiganheader.danka_search');
Route::post('akihiganheaders/{id}/add_danka_data',    [AkihiganHeaderController::class, 'add_danka_data'    ])->name('akihiganheader.add_danka_data');

Route::get('akihigandocuments/create',                [AkihiganDocumentController::class, 'create'          ])->name('akihigandocument.create');
Route::post('akihigandocuments',                      [AkihiganDocumentController::class, 'store'           ])->name('akihigandocument.store');
Route::get('akihigandocuments/{id}/edit',             [AkihiganDocumentController::class, 'edit'            ])->name('akihigandocument.edit');
Route::patch('akihigandocuments/{id}',                [AkihiganDocumentController::class, 'update'          ])->name('akihigandocument.update');
Route::get('akihigandocuments/create_or_edit',        [AkihiganDocumentController::class, 'createOrEdit'    ])->name('akihigandocument.createOrEdit');
Route::get('akihigandocuments/{id}/print',            [AkihiganDocumentController::class, 'print'           ])->name('akihigandocument.print');

Route::get('hanamatsurilists',                        [HanamatsurilistController::class, 'index'            ])->name('hanamatsurilist.index');
Route::get('hanamatsurilist/print',                   [HanamatsurilistController::class, 'print'            ])->name('hanamatsurilist.print');
Route::get('hanamatsurilist/postcard_print',          [HanamatsurilistController::class, 'postcard_print'   ])->name('hanamatsurilist.postcard_print');
Route::get('hanamatsurilist/envelope4_print',         [HanamatsurilistController::class, 'envelope4_print'  ])->name('hanamatsurilist.envelope4_print');
Route::get('hanamatsurilist/envelope3_print',         [HanamatsurilistController::class, 'envelope3_print'  ])->name('hanamatsurilist.envelope3_print');
Route::get('hanamatsurilist/square3_print',           [HanamatsurilistController::class, 'square3_print'    ])->name('hanamatsurilist.square3_print');
Route::get('hanamatsurilist/square2_print',           [HanamatsurilistController::class, 'square2_print'    ])->name('hanamatsurilist.square2_print');
Route::get('hanamatsurilist/label_print',             [HanamatsurilistController::class, 'label_print'      ])->name('hanamatsurilist.label_print');

Route::get('gozikailists',                            [GozikailistsController::class, 'index'               ])->name('gozikailist.index');
Route::get('gozikailist/print',                       [GozikailistsController::class, 'print'               ])->name('gozikailist.print');
Route::get('gozikailist/postcard_print',              [GozikailistsController::class, 'postcard_print'      ])->name('gozikailist.postcard_print');
Route::get('gozikailist/envelope4_print',             [GozikailistsController::class, 'envelope4_print'     ])->name('gozikailist.envelope4_print');
Route::get('gozikailist/envelope3_print',             [GozikailistsController::class, 'envelope3_print'     ])->name('gozikailist.envelope3_print');
Route::get('gozikailist/square3_print',               [GozikailistsController::class, 'square3_print'       ])->name('gozikailist.square3_print');
Route::get('gozikailist/square2_print',               [GozikailistsController::class, 'square2_print'       ])->name('gozikailist.square2_print');
Route::get('gozikailist/label_print',                 [GozikailistsController::class, 'label_print'         ])->name('gozikailist.label_print');

Route::get('gozikaikaihilists',                       [GozikaikaihiListController::class, 'index'           ])->name('gozikaikaihilist.index');
Route::get('gozikaikaihilists/{danka_id}/edit/{id?}', [GozikaikaihiListController::class, 'edit'            ])->name('gozikaikaihilist.edit');
Route::patch('gozikaikaihilists/{danka_id}/{id?}',    [GozikaikaihiListController::class, 'update'          ])->name('gozikaikaihilist.update');
Route::get('gozikaikaihilists/print',                 [GozikaikaihiListController::class, 'print'           ])->name('gozikaikaihilist.print');

Route::get('kaihilists',                              [KaihiListController::class, 'index'                  ])->name('kaihilist.index');
Route::get('kaihilists/print',                        [KaihiListController::class, 'print'                  ])->name('kaihilist.print');

Route::get('areakaikilists',                          [AreakaikiListController::class, 'index'              ])->name('areakaikilist.index');
Route::get('areakaikilist/print',                     [AreakaikiListController::class, 'print'              ])->name('areakaikilist.print');

Route::get('kaikireibolists',                         [KaikireiboListController::class, 'index'             ])->name('kaikireibolist.index');
Route::get('kaikireibolist/print',                    [KaikireiboListController::class, 'print'             ])->name('kaikireibolist.print');

Route::get('kakuiereibolists',                        [KakuiereiboListController::class, 'index'            ])->name('kakuiereibolist.index');
Route::get('kakuiereibolist/print',                   [KakuiereiboListController::class, 'print'            ])->name('kakuiereibolist.print');

Route::get('nendoreibolists',                         [NendoreiboListController::class, 'index'             ])->name('nendoreibolist.index');
Route::get('nendoreibolist/print',                    [NendoreiboListController::class, 'print'             ])->name('nendoreibolist.print');

Route::get('hondoulists',                             [HondoulistController::class, 'index'                 ])->name('hondoulist.index');
Route::get('hondoulist/print',                        [HondoulistController::class, 'print'                 ])->name('hondoulist.print');
Route::get('hondoulist/amulet_print',                 [HondoulistController::class, 'amulet_print'          ])->name('hondoulist.amulet.print');

Route::get('hatsubonlists',                           [HatsubonlistController::class, 'index'               ])->name('hatsubonlist.index');
Route::get('hatsubonlist/print',                      [HatsubonlistController::class, 'print'               ])->name('hatsubonlist.print');
Route::get('hatsubonlist/yomikomicho_print',          [HatsubonlistController::class, 'yomikomicho_print'   ])->name('hatsubonlist.yomikomicho.print');
Route::get('hatsubonlist/postcard_print',             [HatsubonlistController::class, 'postcard_print'      ])->name('hatsubonlist.postcard_print');
Route::get('hatsubonlist/envelope4_print',            [HatsubonlistController::class, 'envelope4_print'     ])->name('hatsubonlist.envelope4_print');
Route::get('hatsubonlist/envelope3_print',            [HatsubonlistController::class, 'envelope3_print'     ])->name('hatsubonlist.envelope3_print');
Route::get('hatsubonlist/square3_print',              [HatsubonlistController::class, 'square3_print'       ])->name('hatsubonlist.square3_print');
Route::get('hatsubonlist/square2_print',              [HatsubonlistController::class, 'square2_print'       ])->name('hatsubonlist.square2_print');
Route::get('hatsubonlist/label_print',                [HatsubonlistController::class, 'label_print'         ])->name('hatsubonlist.label_print');
Route::get('hatsubonlist/back_print',                 [HatsubonlistController::class, 'back_print'          ])->name('hatsubonlist.back_print');

Route::get('hatsubonlistdocuments/create',            [HatsubonlistDocumentController::class, 'create'      ])->name('hatsubonlistdocument.create');
Route::post('hatsubonlistdocuments',                  [HatsubonlistDocumentController::class, 'store'       ])->name('hatsubonlistdocument.store');
Route::get('hatsubonlistdocuments/{id}/edit',         [HatsubonlistDocumentController::class, 'edit'        ])->name('hatsubonlistdocument.edit');
Route::patch('hatsubonlistdocuments/{id}',            [HatsubonlistDocumentController::class, 'update'      ])->name('hatsubonlistdocument.update');
Route::get('hatsubonlistdocuments/create_or_edit',    [HatsubonlistDocumentController::class, 'createOrEdit'])->name('hatsubonlistdocument.createOrEdit');
Route::get('hatsubonlistdocuments/{id}/print',        [HatsubonlistDocumentController::class, 'print'       ])->name('hatsubonlistdocument.print');

Route::get('nenkailists',                             [NenkailistController::class, 'index'                 ])->name('nenkailist.index');
Route::get('nenkailist/print',                        [NenkailistController::class, 'print'                 ])->name('nenkailist.print');

Route::get('nenkaidocuments/create',                  [NenkaiDocumentController::class, 'create'            ])->name('nenkaidocument.create');
Route::post('nenkaidocuments',                        [NenkaiDocumentController::class, 'store'             ])->name('nenkaidocument.store');
Route::get('nenkaidocuments/{id}/edit',               [NenkaiDocumentController::class, 'edit'              ])->name('nenkaidocument.edit');
Route::patch('nenkaidocuments/{id}',                  [NenkaiDocumentController::class, 'update'            ])->name('nenkaidocument.update');
Route::get('nenkaidocuments/create_or_edit',          [NenkaiDocumentController::class, 'createOrEdit'      ])->name('nenkaidocument.createOrEdit');

Route::get('paymentslips/create',                     [PaymentSlipController::class, 'create'               ])->name('paymentslip.create');
Route::post('paymentslips',                           [PaymentSlipController::class, 'store'                ])->name('paymentslip.store');
Route::get('paymentslips/{id}/edit',                  [PaymentSlipController::class, 'edit'                 ])->name('paymentslip.edit');
Route::patch('paymentslips/{id}',                     [PaymentSlipController::class, 'update'               ])->name('paymentslip.update');
Route::get('paymentslips/create_or_edit',             [PaymentSlipController::class, 'createOrEdit'         ])->name('paymentslip.createOrEdit');
Route::post('paymentlips/update_copies',              [PaymentSlipController::class, 'updateCopies'         ])->name('paymentslip.updateCopies');
Route::get('paymentslips/print',                      [PaymentSlipController::class, 'print'                ])->name('paymentslip.print');

Route::get('dankalists',                              [DankalistController::class, 'index'                  ])->name('dankalist.index');

Route::get('postcards',                               [PostcardController::class, 'index'                   ])->name('postcard.index');

Route::get('atenaheaders',                            [AtenaHeaderController::class, 'index'                ])->name('atenaheader.index');
Route::get('atenaheaders/create',                     [AtenaHeaderController::class, 'create'               ])->name('atenaheader.create');
Route::post('atenaheaders',                           [AtenaHeaderController::class, 'store'                ])->name('atenaheader.store');
Route::get('atenaheaders/{id}',                       [AtenaHeaderController::class, 'show'                 ])->name('atenaheader.show');
Route::get('atenaheaders/{id}/edit',                  [AtenaHeaderController::class, 'edit'                 ])->name('atenaheader.edit');
Route::patch('atenaheaders/{id}',                     [AtenaHeaderController::class, 'update'               ])->name('atenaheader.update');
Route::get('atenaheaders/{id}/postcard_print',        [AtenaHeaderController::class, 'postcard_print'       ])->name('atenaheader.postcard_print');
Route::get('atenaheaders/{id}/envelope4_print',       [AtenaHeaderController::class, 'envelope4_print'      ])->name('atenaheader.envelope4_print');
Route::get('atenaheaders/{id}/envelope3_print',       [AtenaHeaderController::class, 'envelope3_print'      ])->name('atenaheader.envelope3_print');
Route::get('atenaheaders/{id}/square3_print',         [AtenaHeaderController::class, 'square3_print'        ])->name('atenaheader.square3_print');
Route::get('atenaheaders/{id}/square2_print',         [AtenaHeaderController::class, 'square2_print'        ])->name('atenaheader.square2_print');
Route::get('atenaheaders/{id}/label_print',           [AtenaHeaderController::class, 'label_print'          ])->name('atenaheader.label_print');

Route::get('atenadetails/{atena_header_id}/create',   [AtenaDetailController::class, 'create'               ])->name('atenadetail.create');
Route::post('atenadetails',                           [AtenaDetailController::class, 'store'                ])->name('atenadetail.store');
Route::get('atenadetails/{id}/edit',                  [AtenaDetailController::class, 'edit'                 ])->name('atenadetail.edit');
Route::patch('atenadetails/{id}',                     [AtenaDetailController::class, 'update'               ])->name('atenadetail.update');
Route::delete('atenadetails/{atena_header_id}/{id}',  [AtenaDetailController::class, 'destroy'              ])->name('atenadetail.destroy');
Route::get('atenadetails/{id}/danka_search',          [AtenaDetailController::class, 'danka_search'         ])->name('atenadetail.danka_search');
Route::post('atenadetail/{id}/add_danka_data',        [AtenaDetailController::class, 'add_danka_data'       ])->name('atenadetail.add_danka_data');

Route::get('generalpostcards',                        [GeneralPostcardController::class, 'index'            ])->name('generalpostcard.index');
Route::get('generalpostcards/create',                 [GeneralPostcardController::class, 'create'           ])->name('generalpostcard.create');
Route::post('generalpostcards',                       [GeneralPostcardController::class, 'store'            ])->name('generalpostcard.store');
Route::get('generalpostcards/{id}/edit',              [GeneralPostcardController::class, 'edit'             ])->name('generalpostcard.edit');
Route::patch('generalpostcards/{id}',                 [GeneralPostcardController::class, 'update'           ])->name('generalpostcard.update');
Route::get('generalpostcards/{id}/print',             [GeneralPostcardController::class, 'print'            ])->name('generalpostcard.print');

Route::get('kaimyouchecks',                           [KaimyouCheckController::class, 'index'               ])->name('kaimyoucheck.index');

Route::get('templelists',                             [TemplelistController::class, 'index'                 ])->name('templelist.index');
Route::get('templelist/print',                        [TemplelistController::class, 'print'                 ])->name('templelist.print');

Route::get('memberlists',                             [MemberlistController::class, 'index'                 ])->name('memberlist.index');
Route::get('memberlist/print',                        [MemberlistController::class, 'print'                 ])->name('memberlist.print');

Route::get('templelistprints',                        [TemplelistprintController::class, 'index'            ])->name('templelistprint.index');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
