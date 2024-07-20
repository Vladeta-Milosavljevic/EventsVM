<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    private function createUser(bool $isAdmin = false)
    {
        $user = User::factory()->create(['is_admin' => $isAdmin,]);
        return $user;
    }

    private function createCategory($category = 'categoryForCategoryTest')
    {
        $category = Category::create(['name' => $category]);
        return $category;
    }


    public function test_category_user_not_admin_test(): void
    {
        $response = $this->get(route('category.index'));
        $response->assertRedirect(route('index'));
        $response = $this->actingAs($this->user1)->get(route('category.index'));
        $response->assertRedirect(route('index'));
    }

    public function test_category_user_is_admin_test(): void
    {
        $response = $this->actingAs($this->admin)->get(route('category.index'));
        $response->assertStatus(200);
    }

    public function test_store_category_test()
    {
        Storage::fake('categories');
        $category = ['name' => 'categoryTest555'];

        $response = $this->actingAs($this->admin)->post(route('category.store'), $category);
        $response->assertRedirect(route('category.index'));

        $this->assertDatabaseHas('categories', $category);
        $lastCategory = Category::orderBy('id', 'DESC')->first();
        $this->assertEquals($category['name'], $lastCategory->name);
    }

    public function test_store_category_has_validaton_errors_test()
    {
        Storage::fake('categories');
        $category = ['name' => 'a'];

        $response = $this->actingAs($this->admin)->post(route('category.store'), $category);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
        $response->assertInvalid(['name']);
    }

    public function test_update_category_test()
    {
        $categoryUpdate = ['name' => 'category11111'];
        $response = $this->actingAs($this->admin)->put(route('category.update', $this->category1->id), $categoryUpdate);
        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseHas('categories', $categoryUpdate);

        $updatedCategory = Category::where('id', $this->category1->id)->first();
        $this->assertEquals($categoryUpdate['name'], $updatedCategory->name);
    }


    public function test_update_category_has_validation_errors_test()
    {
        $categoryUpdate = ['name' => 'a'];
        $response = $this->actingAs($this->admin)->put(route('category.update', $this->category1->id), $categoryUpdate);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
        $response->assertInvalid(['name']);
    }

    public function test_delete_category_test()
    {
        $testCategory = $this->createCategory('categoryTest777');

        $response = $this->actingAs($this->admin)->delete(route('category.destroy', $testCategory->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));

        // assertDatabaseMissing zahteva niz
        $this->assertDatabaseMissing('categories', $testCategory->toArray());
    }
}
