<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    private User $user1;
    private User $user2;
    private User $admin;
    private Category $category1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user1 = $this->createUser();
        $this->user2 = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
        $this->category1 = $this->createCategory();
    }

    private function createTestData($eventsNumber = 1)
    {
        $data = [
            'users' =>  User::factory(3)->create(),
            'categories' => Category::factory(4)->create(),
            'events' => Event::factory($eventsNumber)->create()
        ];
        return $data;
    }

    private function createUser(bool $isAdmin = false)
    {
        $user = User::factory()->create(['is_admin' => $isAdmin,]);
        return $user;
    }

    private function createCategory($category = 'categoryForEventTest')
    {
        $category = Category::create(['name' => $category]);
        return $category;
    }

    public function test_create_update_delete_event(): void
    {
        $user = User::create([
            'name' => 'Victor',
            'email' => 'victor@test.com',
            'is_admin' => 'true',
            'password' => bcrypt('victor123')
        ]);
        $category = Category::create([
            'name' => 'category111'
        ]);
        # create event test
        $event = Event::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Test Event',
            'tags' => 'test tags to get',
            'description' => 'test description test description test description',
            'price' => 50.33,
            'image' => 'https://via.placeholder.com/1920x1080.png/00ff11?text=event+qui',
            'addImages' => ['https://via.placeholder.com/1920x1080.png/0088dd?text=event+maiores', 'https://via.placeholder.com/1920x1080.png/005566?text=event+saepe', 'https://via.placeholder.com/1920x1080.png/0088cc?text=event+perferendis'],
        ]);

        $response = $this->get(route('event.show', $event->id));

        $response->assertStatus(200);
        $response->assertSee('description');
        $response->assertSee('test');

        # update event test
        $event['name'] = 'Test2 event2';
        $event['description'] = 'test2 description2 test2 descriptio2n test2 description2';
        $event->save();

        $response = $this->get(route('event.show', $event->id));
        $response->assertStatus(200);
        $response->assertSee('description2');
        $response->assertSee('test2');

        #delete event test
        $eventId = $event->id;
        $event->delete();

        $response = $this->get(route('event.show', $eventId));
        $response->assertStatus(404);
    }

    public function test_create_update_delete_event_successful()
    {

        Storage::fake('events');

        $image1 = UploadedFile::fake()->image('event1.jpg', 1920, 1080)->size(200);
        $image2 = UploadedFile::fake()->image('event2.jpg', 1920, 1080)->size(200);
        $event = [
            'category_id' => $this->category1->id,
            'name' => 'Test Event',
            'tags' => 'test tags to get',
            'description' => 'test description test description test description',
            'price' => 50.33,
            'image' => $image1,
            'addImages' => [$image2, $image2, $image2],
        ];

        // wrong user
        $response = $this->post(route('event.store'), $event);
        $response->assertRedirect(route('login'));

        // store the event
        $response = $this->actingAs($this->user1)->post(route('event.store'), $event);
        $response->assertStatus(302);
        $response->assertRedirect(route('myEvents', $this->user1->id));

        $lastEvent = Event::orderBy('id', 'DESC')->first();
        $this->assertEquals($event['name'], $lastEvent->name);
        $this->assertEquals($event['tags'], $lastEvent->tags);
        $this->assertEquals($event['description'], $lastEvent->description);
        $this->assertEquals($event['price'], $lastEvent->price);

        // Update the event
        $eventUpdate = $event;
        $eventUpdate['name'] = 'New Name';
        $eventUpdate['tags'] = 'New Tags Yes';
        $response = $this->actingAs($this->user1)->put(route('event.update', $lastEvent->id), $eventUpdate);
        $response->assertStatus(302);
        $response->assertRedirect(route('myEvents', $this->user1->id));
        $updatedEvent = Event::where('id', $lastEvent->id)->first();
        $this->assertEquals($eventUpdate['name'], $updatedEvent->name);
        $this->assertEquals($eventUpdate['tags'], $updatedEvent->tags);


        // delete the event
        $response = $this->actingAs($this->user1)->delete(route('event.destroy', $lastEvent->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('myEvents', $this->user1->id));
        $this->assertDatabaseMissing('categories', $lastEvent->toArray());
    }

    public function test_store_event_has_validaton_errors_test()
    {
        Storage::fake('events');

        $event = [
            'category_id' => $this->category1->id,
            'name' => 'Te',
            'tags' => 't',
            'description' => 'test',
            'price' => 5000000.33,
            'image' => '',
            'addImages' => [],
        ];

        $response = $this->actingAs($this->user1)->post(route('event.store'), $event);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'tags', 'description', 'price', 'image', 'addImages']);
        $response->assertInvalid(['name', 'tags', 'description', 'price', 'image', 'addImages']);
    }

    public function test_update_event_has_validaton_errors_test()
    {

        Storage::fake('events');

        $image1 = UploadedFile::fake()->image('event1.jpg', 1920, 1080)->size(200);
        $image2 = UploadedFile::fake()->image('event2.jpg', 1920, 1080)->size(200);
        $event = [
            'category_id' => $this->category1->id,
            'name' => 'Test Event',
            'tags' => 'test tags to get',
            'description' => 'test description test description test description',
            'price' => 50.33,
            'image' => $image1,
            'addImages' => [$image2, $image2, $image2],
        ];

        // store the event
        $response = $this->actingAs($this->user1)->post(route('event.store'), $event);
        $Event = Event::orderBy('id', 'DESC')->first();
        $image3 = UploadedFile::fake()->image('event1.jpg', 1920, 1080)->size(1500);
        $image4 = UploadedFile::fake()->image('event1.jpg', 1920, 1080)->size(1500);

        $eventUpdate = [
            'category_id' => $this->category1->id,
            'name' => 'Te',
            'tags' => 't',
            'description' => 'test',
            'price' => 5000000.33,
            'image' => $image3,
            'addImages' => [$image4, $image4, $image4],
        ];

        $response = $this->actingAs($this->user1)->put(route('event.update', $Event->id), $eventUpdate);
        $response->assertStatus(302);
        // assertSessionHasErrors does not see errors for the addImages field even though errors are present
        $response->assertSessionHasErrors(['name', 'tags', 'description', 'price', 'image']);
        // assertInvalid does not see errors for the addImages field even though errors are present
        $response->assertInvalid(['name', 'tags', 'description', 'price', 'image']);
    }


    public function test_pagination_test(): void
    {
        $testData = $this->createTestData(25);
        $testEvent = $testData['events'][15];
        $testEvent['name'] = 'TestTestTestTestTestTest';
        $testEvent->save();
        $response = $this->get(route('index', '?page=2'));
        $response->assertStatus(200);
        $response->assertSee($testEvent->name);
        $response->assertSee($testEvent->price);
        $response->assertSee($testEvent->tags);
    }

    public function test_event_user_not_loged_in_test(): void
    {
        $response = $this->post(route('event.store'));
        $response->assertRedirect(route('login'));

        $testData = $this->createTestData();
        $response = $this->put(route('event.update', $testData['events'][0]->id));
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('event.destroy', $testData['events'][0]->id));
        $response->assertRedirect(route('login'));
    }

    public function test_delete_event_wrong_user_loged_in_test(): void
    {
        Category::factory(4)->create();
        $event = Event::factory()->create(['user_id' => $this->user1->id]);
        $response = $this->actingAs($this->user2)->delete(route('event.destroy', $event->id));
        $response->assertRedirect(route('event.show', $event->id));
    }

    public function test_delete_event_correct_user_loged_in_test(): void
    {
        Category::factory(4)->create();
        $event = Event::factory()->create(['user_id' => $this->user1->id]);
        $response = $this->actingAs($this->user1)->delete(route('event.destroy', $event->id));
        $response->assertRedirect(route('myEvents', $this->user1->id));
    }

    public function test_my_events_page_auth(): void
    {
        $response = $this->get(route('myEvents', $this->user1->id));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($this->user1)->get(route('myEvents', $this->user1->id));
        $response->assertStatus(200);
    }
}
