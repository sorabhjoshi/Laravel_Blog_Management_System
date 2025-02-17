<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\CountryList;
use App\Models\Admin\Designation;
use App\Models\Admin\Domains;
use App\Models\Admin\Language;
use App\Models\Admin\News;
use App\Models\Admin\Newscat;
use App\Models\Admin\Pages;
use App\Models\Admin\Status;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Newsarticle extends Controller
{
public function addnewsdata(Request $request)
    {
       
    
        $request->validate([
            'author_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'image' => 'required',
            'content' => 'required|string',
            'category' => 'required|string',
            'Domains' => 'required|string',
            'Languages' => 'required|string',
            'Country' => 'required|integer'
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'images/' . $imageName;
            $image->move(public_path('images'), $imageName);
        }
    
        $slug = Str::slug($request->input('title'));
        $existingSlugCount = News::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }
        $user = Auth::user();

        News::create([
            'authorname' => $request->input('author_name'),
            'title' => $request->input('title'),
            'image' => $imagePath,
            'description' => $request->input('content'),
            'category' => $request->input('category'),
            'slug' => $slug,
            'user_id' => $user->id, 
            'domain' => $request->input('Domains'),
            'language' => $request->input('Languages'),
            'country' => $request->input('Country'),
        ]);
    
        return redirect()->route('Newsarticle')->with('success', 'Blog updated successfully!');
}

public function editnews($id){
        $lang = Language::all();
        $domain = Domains::all();
        $userdata = News::find($id);
        $countries = CountryList::select('id','name')->get();
        $Catdata = Newscat::select('categorytitle','id')->get();
        return view('Blogbackend.Utils.Editnews', ['userdata' => $userdata,'Catdata'=>$Catdata,'domain'=>$domain,'lang'=>$lang,'countries'=>$countries]);
}

public function updatenews(Request $request)
{

    // $user = session('user');
    $request->validate([
        'id' => 'required',
        'author_name' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'image' => 'nullable|image',
        'content' => 'required|string',
        'category' => 'required',
        'Domains' => 'required',
        'Languages' => 'required',
        'Country' => 'required',
    ]);

    $userdata = News::find($request->input('id'));
    if (!$userdata) {
        return redirect()->back()->withErrors('Blog not found!');
    }

    $imagePath = $userdata->image; // Retain old image if not updated
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = 'images/' . $imageName;
        $image->move(public_path('images'), $imageName);
    }

    $slug = Str::slug($request->input('title'));
    $existingSlugCount = News::where('slug', $slug)->where('id', '!=', $userdata->id)->count();
    if ($existingSlugCount > 0) {
        $slug = $slug . '-' . time();
    }
    if(!empty($request->input('Country'))){
        $userdata->country= $request->input('Country');
    }
    $userdata->authorname = $request->input('author_name');
    $userdata->title = $request->input('title');
    $userdata->image = $imagePath;
    $userdata->description = $request->input('content');
    $userdata->category = $request->input('category');
    $userdata->slug = $slug;
    $userdata->domain = $request->input('Domains');
    $userdata->language = $request->input('Languages');
    // $userdata->userid = $user->id;

    $userdata->save(); 

    return redirect()->route('Newsarticle')->with('success', 'Blog updated successfully!');
}

public function deletenews($id){
    $userdata = News::find($id);
    if ($userdata->delete()) {
        return redirect()->route('Newsarticle')->with('success', 'User updated successfully!');
    } else {
        return redirect()->back()->with('error', 'Blog not found.');
    }
    
}
public function addnewscatdata(Request $request)
{

    $request->validate([
        'categorytitle' => 'required|string|max:255',
        'seotitle' => 'required|string|max:255',
        'metakeywords' => 'required',
        'metadescription' => 'required|string',
    ]);

    Newscat::create([
        'categorytitle' => $request->input('categorytitle'),
        'seotitle' => $request->input('seotitle'),
        'metakeywords' =>  $request->input('metakeywords'),
        'metadescription' => $request->input('metadescription')
    ]);

    return redirect()->route('Newscat');
}
public function editnewscat($id){
    $userdata = Newscat::find($id);
    return view('Blogbackend.Utils.Editnewscat', ['userdata' => $userdata]);
}
public function updatenewscat(Request $request)
{
    // $user = session('user');
    $request->validate([
        'id' => 'required',
        'categorytitle' => 'required|string|max:255',
        'seotitle' => 'required|string|max:255',
        'metakeywords' => 'required',
        'metadescription' => 'required|string',
    ]);

    $userdata = Newscat::find($request->input('id'));
    $userdata->categorytitle = $request->input('categorytitle');
    $userdata->seotitle = $request->input('seotitle');
    $userdata->metakeywords = $request->input('metakeywords');
    $userdata->metadescription = $request->input('metadescription');
    $userdata->save(); 

    return redirect()->route('Newscat');
}
public function deletenewscat($id){
    $userdata = Newscat::find($id);
    if ($userdata->delete()) {
        return redirect()->route('Newscat');
    } else {
        return redirect()->back()->with('error', 'Blog not found.');
    }
    
}
public function addpagedata(Request $request)
{

    $request->validate([
        'authorname' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required',
    ]);
    $slug = Str::slug($request->input('title'));
        $existingSlugCount = News::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }
    $user = Auth::user();

    Pages::create([
        'author' => $request->input('authorname'),
        'title' => $request->input('title'),
        'description' =>  $request->input('description'),
        'slug' => $slug,
        'userid' => $user->id,  //
    ]);

    return redirect()->route('Pages')->with('success', 'User updated successfully!');
}
public function editpages($id){
    $userdata = Pages::find($id);
    return view('Blogbackend.Utils.Editpages', ['userdata' => $userdata]);
}

public function deletepages($id){
        $pagedata=Pages::find($id);
        if ($pagedata->delete()) {
           return redirect()->route('Pages');
        }
}
public function updatepagedata(Request $request)
{
    // $user = session('user');
    $request->validate([
        'id' => 'required',
        'authorname' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required',
    ]);

    $slug = Str::slug($request->input('title'));
    $existingSlugCount = News::where('slug', $slug)->count();
    if ($existingSlugCount > 0) {
        $slug = $slug . '-' . time();
    }
    $userdata= Pages::find($request->input('id'));
    
        $userdata->author = $request->input('authorname');
        $userdata->title = $request->input('title');
        $userdata->description =  $request->input('description');
        $userdata->slug = $slug ;//
        if ($userdata->save() ) {
            return redirect()->route('Pages')->with('success', 'User updated successfully!');
        }else{
            return redirect()->back()->withErrors('error', 'Page update error!');
        }
}
public function addnews(){
    $lang = Language::all();
    $domain = Domains::all();
    $countries = CountryList::select('id','name')->get();
    $Catdata = Newscat::select('categorytitle','id')->get();
  return view('Blogbackend/Utils/AddNews', compact('Catdata','domain','lang','countries'));
}

public function shownews(){
    $designations = Designation::all(); 
    // $blogdata = news::with(['categories', 'domainrel', 'langrel', 'statuss','approval'])->paginate(2);

    $designationid = \App\Models\User::where('id', session('user_id'))->pluck('designation')->first();
    return view('Blogbackend.News', compact('designationid','designations'));
}
}
