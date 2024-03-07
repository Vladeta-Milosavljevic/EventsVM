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

        return Inertia::render(
            'Events/Index',
            [
                'events' => $events,
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
        $images = [];
        foreach ($validatedEvent['addImages'] as $addImage) {
            $addImageName = date('YmdHi') . '-' . $addImage->getClientOriginalName();
            $imagePath = $addImage->storeAs('public/images', $addImageName);
            array_push($images, $imagePath);
        }
        $validatedEvent['addImages'] = $images;
        $validatedEvent['user_id'] = auth()->id();

        Event::create($validatedEvent);
        return redirect(route('myEvents', ['user_id' => $request->user()->id]));
    }

    public function show(Event $event)
    {

        $imagesList=array_merge([$event['image']],$event['addImages']);
        $images=[];
        foreach ($imagesList as $image) {
            array_push($images, str_contains($image, "https") ? $image : Storage::url($image));
        }

        $eventData = [
            'id' => $event->id,
            'category_id'=>$event->category_id,
            'category' => $event->category->name,
            'name' => $event->name,
            'tags' => $event->tags,
            'description' => $event->description,
            'images' => $images,
        ];
        return Inertia::render('Events/EventShow', [
            'event' => $eventData,
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
        if ($validatedEvent['image'] != null) {
            Storage::delete($event->image);
            $imageName = date('YmdHi') . '-' . $validatedEvent['image']->getClientOriginalName();
            $validatedEvent['image'] = $validatedEvent['image']->storeAs('public/images', $imageName);
        } else {
            // if image is not updated null will be saved in the database
            $validatedEvent['image'] = $event->image;
        }
        // if image is added without addImages in form, the addImages element does not exist in $validatedEvent
        $addImages = array_key_exists("addImages", $validatedEvent) ? $validatedEvent['addImages'] : [];
        if (count($addImages) > 0) {
            foreach ($event->addImages as $addImage) {
                Storage::delete($addImage);
            }
            $images = [];
            foreach ($addImages as $addImage) {
                $addImageName = date('YmdHi') . '-' . $addImage->getClientOriginalName();
                $imagePath = $addImage->storeAs('public/images', $addImageName);
                array_push($images, $imagePath);
            }
            $validatedEvent['addImages'] = $images;
        } else {
            $validatedEvent['addImages'] = $event->addImages;
        }
        $event->update($validatedEvent);
        return redirect(route('event.show', $event->id));
    }

    public function myEvents(Request $request)
    {
        $user_id = $request->user_id;
        $myEvents = fn() => Event::where('user_id', $user_id)
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

        return Inertia::render('Events/MyEvents', ['myEvents' => $myEvents, 'user_id' => $user_id]);
    }

    public function destroy(Request $request, Event $event)
    {

        if ($request->user()->cannot('delete', $event)) {
            return redirect(route('event.show', $event->id));
        }
        Storage::delete($event->image);
        foreach ($event->addImages as $addImage) {
            Storage::delete($addImage);
        }
        $event->delete();
        return redirect(route('myEvents', ['user_id' => $request->user()->id]));
    }
}
