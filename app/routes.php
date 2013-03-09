<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Config::set('categories', array(
  'new'     => '글쓰기',
  'notice'  => '공지사항',
  'free'    => '자유게시판',
  'tuts'    => 'Laravel 강좌게시판',
  'tips'    => 'Laravel 팁게시판',
  'help'    => 'Laravel 질문게시판',
  'sites'   => 'Laravel 사이트 소개',
  'jobs'    => '구인구직'
));

// Home
Route::get('/', function(){
    
  $posts = Post::orderBy('id', 'desc')->take(15)->get();
  
  return View::make('home')->with(array(
    'posts' => $posts,
    'categories' => Config::get('categories')
  ));
  
});

Route::get('/help', function(){
  return 'help';
});

Route::get('/about', function($bbb){
  return 'yes';
});

Route::get('/docs', function(){
  return Redirect::to('docs/introduction');
});

Route::get('/docs/{page}', function($page){
  $path = '../app/views/docs/ko/' . $page . '.md';
  
  if(File::exists($path))
  {
    $file = File::get($path);
  }else{
    return 'clone the repository first!';
  }
  
  $markdown = new MarkdownExtraParser();
  return View::make('docs.index')->with(array(
    'content' => ($markdown->transformMarkdown($file)),
    'page' => $page
  ));
});




// Account
Route::get('/login', 'AccountController@getLogin');
Route::post('/login', 'AccountController@postLogin');
Route::get('/register', 'AccountController@getRegister');
Route::post('/register', 'AccountController@postRegister');
Route::get('/logout', 'AccountController@getLogout');
Route::controller('account', 'AccountController');


// Users
Route::get('/users/{userId}/posts', 'UserController@getPostsById');
Route::get('/users/{userId}/{username}/posts', 'UserController@getPostsById');
Route::get('/users/{userId}/{username?}', 'UserController@getById');
Route::controller('users', 'UserController');


// Posts
#Route::get('/posts/new', 'PostController@getCreate');
#Route::post('/posts/new', 'PostController@postCreate');
Route::get('/posts/{category}', 'PostController@getByCategory')->where('category', '[a-zA-Z]+');
Route::get('/posts/{category}/new', 'PostController@getCreate')->where('category', '[a-zA-Z]+');
Route::post('/posts/{category}/new', 'PostController@postCreate')->where('category', '[a-zA-Z]+');
Route::get('/posts/{postId}', 'PostController@getById')->where('category', '[0-9]+');
Route::get('/posts/{postId}/edit', 'PostController@getEdit');
Route::post('/posts/{postId}/edit', 'PostController@postEdit');
Route::get('/posts/{postId}/delete', 'PostController@getDelete');
Route::controller('posts', 'PostController');


// Tags
//Route::controller('tags', 'TagController');