<?php

namespace App\Http\Controllers;
use DevDojo\Chatter\Models\Models;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
     public function index($slug = '')
     {
         $pagination_results = config('chatter.paginate.num_of_results');

         $discussions = Models::discussion()->with('user')->with('post')->with('postsCount')->with('category')->orderBy('created_at', 'DESC')->paginate($pagination_results);
         if (isset($slug)) {
             $category = Models::category()->where('slug', '=', $slug)->first();
             if (isset($category->id)) {
                 $discussions = Models::discussion()->with('user')->with('post')->with('postsCount')->with('category')->where('chatter_category_id', '=', $category->id)->orderBy('created_at', 'DESC')->paginate($pagination_results);
             }
         }

         $categories = Models::category()->all();
         $chatter_editor = config('chatter.editor');

         if ($chatter_editor == 'simplemde') {
             // Dynamically register markdown service provider
             \App::register('GrahamCampbell\Markdown\MarkdownServiceProvider');
         }

         return view('home', compact('discussions', 'categories', 'chatter_editor'));
     }
}
