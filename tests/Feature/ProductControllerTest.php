<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Derīgi dati produkta izveidei / atjaunināšanai.
     *
     * @return array<string, mixed>
     */
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Testa piens 1 l',
            'quantity' => 15,
            'description' => 'Testa apraksts',
            'expiration_date' => '2030-01-31',
            'status' => 'active',
        ], $overrides);
    }

    // ---------- index ----------

    public function test_index_lists_products(): void
    {
        $first = Product::factory()->create(['name' => 'Pirmais produkts']);
        $second = Product::factory()->create(['name' => 'Otrais produkts']);

        $response = $this->get(route('products.index'));

        $response->assertOk()
            ->assertViewIs('products.index')
            ->assertSee('Pirmais produkts')
            ->assertSee('Otrais produkts')
            ->assertViewHas('products', fn ($products) => $products->count() === 2);
    }

    public function test_index_shows_message_when_there_are_no_products(): void
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Produktu vēl nav');
    }

    public function test_index_is_paginated_by_ten(): void
    {
        Product::factory()->count(12)->create();

        $this->get(route('products.index'))
            ->assertOk()
            ->assertViewHas('products', fn ($products) => $products->count() === 10 && $products->total() === 12);
    }

    // ---------- create ----------

    public function test_create_page_is_displayed(): void
    {
        $this->get(route('products.create'))
            ->assertOk()
            ->assertViewIs('products.create')
            ->assertSee('name="name"', false)
            ->assertSee('name="quantity"', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="expiration_date"', false)
            ->assertSee('name="status"', false);
    }

    // ---------- store ----------

    public function test_store_creates_a_product_and_redirects_to_it(): void
    {
        $response = $this->post(route('products.store'), $this->validData());

        $product = Product::firstWhere('name', 'Testa piens 1 l');

        $this->assertNotNull($product);
        $response->assertRedirect(route('products.show', $product))
            ->assertSessionHas('success');

        $this->assertSame(15, $product->quantity);
        $this->assertSame('active', $product->status);
        $this->assertSame('2030-01-31', $product->expiration_date->toDateString());
    }

    public function test_store_accepts_missing_description_and_expiration_date(): void
    {
        $this->post(route('products.store'), $this->validData([
            'description' => null,
            'expiration_date' => null,
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('products', ['name' => 'Testa piens 1 l']);
        $this->assertNull(Product::first()->expiration_date);
    }

    public function test_store_requires_name_quantity_and_status(): void
    {
        $this->post(route('products.store'), [])
            ->assertSessionHasErrors(['name', 'quantity', 'status']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_store_rejects_invalid_values(): void
    {
        $this->post(route('products.store'), $this->validData([
            'quantity' => -1,
            'expiration_date' => 'nav-datums',
            'status' => 'nezinams',
        ]))->assertSessionHasErrors(['quantity', 'expiration_date', 'status']);

        $this->post(route('products.store'), $this->validData([
            'name' => str_repeat('a', 256),
            'quantity' => 'abc',
        ]))->assertSessionHasErrors(['name', 'quantity']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_store_keeps_old_input_and_returns_to_the_form_on_error(): void
    {
        $this->from(route('products.create'))
            ->post(route('products.store'), $this->validData(['quantity' => -5]))
            ->assertRedirect(route('products.create'))
            ->assertSessionHasInput('name', 'Testa piens 1 l');
    }

    // ---------- show ----------

    public function test_show_displays_the_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Skatāmais produkts',
            'description' => 'Unikāls apraksts',
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertViewHas('product', fn ($shown) => $shown->is($product))
            ->assertSee('Skatāmais produkts')
            ->assertSee('Unikāls apraksts');
    }

    public function test_show_returns_404_for_missing_product(): void
    {
        $this->get(route('products.show', 999))->assertNotFound();
    }

    // ---------- edit ----------

    public function test_edit_page_is_prefilled(): void
    {
        $product = Product::factory()->create(['name' => 'Rediģējamais produkts']);

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertViewIs('products.edit')
            ->assertViewHas('product', fn ($shown) => $shown->is($product))
            ->assertSee('Rediģējamais produkts');
    }

    public function test_edit_returns_404_for_missing_product(): void
    {
        $this->get(route('products.edit', 999))->assertNotFound();
    }

    // ---------- update ----------

    public function test_update_changes_the_product_and_redirects(): void
    {
        $product = Product::factory()->create(['name' => 'Vecais nosaukums']);

        $this->put(route('products.update', $product), $this->validData([
            'name' => 'Jaunais nosaukums',
            'quantity' => 99,
            'status' => 'inactive',
        ]))
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHas('success');

        $product->refresh();

        $this->assertSame('Jaunais nosaukums', $product->name);
        $this->assertSame(99, $product->quantity);
        $this->assertSame('inactive', $product->status);
    }

    public function test_update_rejects_invalid_data_and_keeps_the_product_unchanged(): void
    {
        $product = Product::factory()->create(['name' => 'Nemainīgs']);

        $this->put(route('products.update', $product), $this->validData([
            'name' => '',
            'status' => 'nezinams',
        ]))->assertSessionHasErrors(['name', 'status']);

        $this->assertSame('Nemainīgs', $product->fresh()->name);
    }

    public function test_update_returns_404_for_missing_product(): void
    {
        $this->put(route('products.update', 999), $this->validData())->assertNotFound();
    }

    // ---------- destroy ----------

    public function test_destroy_deletes_the_product_and_redirects_to_index(): void
    {
        $product = Product::factory()->create();

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($product);
    }

    public function test_destroy_returns_404_for_missing_product(): void
    {
        $this->delete(route('products.destroy', 999))->assertNotFound();
    }

    // ---------- maršruti ----------

    public function test_home_redirects_to_products_index(): void
    {
        $this->get(route('home'))->assertRedirect(route('products.index'));
    }
}
