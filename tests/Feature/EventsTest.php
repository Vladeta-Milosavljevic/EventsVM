<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    private function createTestData($eventsNumber = 1)
    {
        $data = [
            'users' =>  User::factory(3)->create(),
            'categories' => Category::factory(4)->create(),
            'events' => Event::factory($eventsNumber)->create()
        ];
        return $data;
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
            'name' => 'testCategory'
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
        $users = User::factory(3)->create();
        Category::factory(4)->create();
        $event = Event::factory()->create(['user_id' => $users[0]->id]);
        $response = $this->actingAs($users[1])->delete(route('event.destroy', $event->id));
        $response->assertRedirect(route('event.show', $event->id));
    }

    public function test_my_events_page_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->get(route('myEvents', $user->id));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($user)->get(route('myEvents', $user->id));
        $response->assertStatus(200);
    }

    public function test_category_user_not_admin_test(): void
    {
        $response = $this->get(route('category.index'));
        $response->assertRedirect(route('index'));
    }

    public function test_category_user_is_admin_test(): void
    {

        $user = User::factory()->create(['is_admin' => 'true',]);
        $response = $this->actingAs($user)->get(route('category.index'));
        $response->assertStatus(200);
    }
}
