<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        // dd($request->search);
        $tagData = $request->tag;
        $categoryData = $request->category;
        $searchData = $request->search;
        $events = Event::query()
            ->when($searchData, function ($query, $searchData) {
                $query->where('name', 'like', "%{$searchData}%")
                    ->orWhere('tags', 'like', "%{$searchData}%")
                    ->orWhere('description', 'like', "%{$searchData}%");
            })
            ->when($tagData, function ($query, $tagData) {
                $query->where('tags', 'like', "%{$tagData}%");
            })
            ->when($categoryData, function ($query, $categoryData) {
                $query->whereHas('category', fn ($query) => $query->where('name', 'like', $categoryData));
            })
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString()
            ->through(
                fn ($events) => [
                    'id' => $events->id,
                    'category' => $events->category->name,
                    'name' => $events->name,
                    'tags' => $events->tags,
                    'image' => str_contains($events->image, "https") ? $events->image : Storage::url($events->image),
                ]
            );
        $categories = Category::all()->map(fn ($category) => ['id' => $category->id, 'name' => $category->name]);

        return Inertia::render(
            'Events/Index',
            [
                'events' => $events,
                'categories' => $categories,
                'filters' => $request->only(['search'])
            ]
        );
    }

    public function create()
    {
        return Inertia::render('Events/EventCreate');
    }

    public function store(StoreEventRequest $request)
    {
        $validatedEvent = $request->validated();
        $imageName = date('YmdHi') . '-' . $validatedEvent['image']->getClientOriginalName();
        $validatedEvent['image'] = $validatedEvent['image']->storeAs('public/images', $imageName);
        $validatedEvent['user_id'] = auth()->id();
        $validatedEvent['category_id'] = Category::where('name', $validatedEvent['category'])->first()->id;

        Event::create($validatedEvent);
        return redirect(route('myEvents', ['user_id' => $request->user()->id]));
    }

    public function show(Event $event)
    {
        $eventData = [
            'id' => $event->id,
            'category' => $event->category->name,
            'name' => $event->name,
            'tags' => $event->tags,
            'description' => $event->description,
            'image' => str_contains($event->image, "https") ? $event->image : Storage::url($event->image),
        ];
        $categories = Category::all()->map(fn ($category) => ['id' => $category->id, 'name' => $category->name]);
        return Inertia::render('Events/EventShow', [
            'event' => $eventData,
            'category' => $event->category->name,
            'categories' => $categories,
            'image' => str_contains($event->image, "https") ? $event->image : Storage::url($event->image),
            'can' => [
                'edit' => Auth::user() ? Auth::user()->can('update', $event) : false,
                'delete' => Auth::user() ? Auth::user()->can('delete', $event) : false,
            ]
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        if ($request->user()->cannot('update', $event)) {
            return redirect(route('event.show', $event->id));
        }

        $validatedEvent = $request->validated();
        $validatedEvent['category_id'] = Category::where('name', $validatedEvent['category'])->first()->id;
        if ($request->image) {
            $image = $request->validate(['image' => ['mimes:jpeg,jpg,png', 'max:5048']]);
            Storage::delete($event->image);
            $imageName = date('YmdHi') . '-' . $image['image']->getClientOriginalName();
            $validatedEvent['image'] = $image['image']->storeAs('public/images', $imageName);
        }
        $event->update($validatedEvent);
        return redirect(route('event.show', $event->id));
    }

    public function myEvents(Request $request)
    {
        $user_id = $request->user_id;
        $myEvents = Event::where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString()
            ->through(
                fn ($events) => [
                    'id' => $events->id,
                    'category' => $events->category->name,
                    'name' => $events->name,
                    'tags' => $events->tags,
                    'image' => str_contains($events->image, "https") ? $events->image : Storage::url($events->image),
                ]
            );
        $categories = Category::all()->map(fn ($category) => ['id' => $category->id, 'name' => $category->name]);

        return Inertia::render(
            'Events/MyEvents',
            [
                'myEvents' => $myEvents,
                'categories' => $categories,
            ]
        );
    }

    public function destroy(Request $request, Event $event)
    {

        if ($request->user()->cannot('delete', $event)) {
            return redirect(route('event.show', $event->id));
        }
        Storage::delete($event->image);
        $event->delete();
        return redirect(route('myEvents', ['user_id' => $request->user()->id]));
    }
}
