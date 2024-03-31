<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Por padrão, o seed do banco de dados salva as imagens
     * diretamente no Storage, sem utilizar um storage fake.
     * Método é necessário para limpar o storage após cada teste
     */
    public function tearDown(): void
    {
        $files = Storage::files();
        foreach ($files as $file) {
            Storage::delete($file);
        }
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     */
    public function test_get_all_products(): void
    {
        $this->seed();
        $response = $this->get("/api/products");
        $response->assertOk();
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->hasAll([
                    "links",
                    "links.0",
                    "links.1",
                    "links.2",
                    "links.3",
                    "data",
                    "data.0",
                    "data.1",
                    "data.2",
                    "data.3",
                    "data.4",
                    "data.5",
                    "data.6",
                    "data.7",
                    "data.8",
                    "data.9",
                ])
                ->where("current_page", 1)
                ->where("total", 20)
                ->where("to", 10)
                ->where("last_page", 2)
                ->etc()
        );
    }

    public function test_get_all_products_in_second_page(): void
    {
        $this->seed();
        $response = $this->get("/api/products");
        $response->assertOk();
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where("current_page", 1)
                ->where("total", 20)
                ->where("from", 1)
                ->where("to", 10)
                ->where("last_page", 2)
                ->etc()
        );

        $response = $this->get("/api/products/?page=2");
        $response->assertOk();
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where("current_page", 2)
                ->where("to", 20)
                ->where("from", 11)
                ->where("data", fn(Collection $data) => $data->count() === 10)
                ->where("next_page_url", null)
                ->etc()
        );
    }

    public function test_insert_a_product_and_search_for_it(): void
    {
        $this->seed();
        $response = $this->post("/api/products", [
            "quantity" => 10,
            "price" => 2.59,
            "name" => "Produto de Teste",
            "image" => UploadedFile::fake()->image("test.png"),
        ]);
        $response->assertCreated();
        $response->assertHeader("location");

        $location = $response->headers->get("location");
        $splitted_location = explode("/", $location);

        $this->get("/api/products/?s=Teste")->assertJson(
            fn(AssertableJson $json) => $json
                ->where("data", fn(Collection $data) => $data->count() === 1)
                ->where("data.0.public_id", end($splitted_location))
                ->where("data.0.name", "Produto de Teste")
                ->where("data.0.price", 2.59)
                ->where("data.0.quantity", 10)
                ->where("from", 1)
                ->where("to", 1)
                ->where("total", 1)
                ->etc()
        );

        $this->get($location)->assertJson(
            fn(AssertableJson $json) => $json
                ->where("public_id", end($splitted_location))
                ->where("name", "Produto de Teste")
                ->where("price", 2.59)
                ->where("quantity", 10)
                ->where(
                    "picture",
                    fn(string $picture) => str_ends_with($picture, ".png")
                )
                ->etc()
        );
    }

    function test_try_get_a_product_that_dont_exists(): void
    {
        $response = $this->get("/api/products/25");

        $response->assertNotFound();
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where("type", "/problems/nao-encontrado")
                ->where("title", "Não encontrado")
                ->where(
                    "detail",
                    "Entidade ou URL não encontrada ou não existe"
                )
                ->where("status", 404)
                ->where("instance", "http://localhost/api/products/25")
        );
    }

    public function test_update_a_product(): void
    {
        $this->seed();
        $response = $this->get("/api/products");

        $to_modify = $response->json("data.0");
        $to_modify["name"] = "Modificado";
        $to_modify["price"] = 2.25;
        $to_modify["quantity"] = 112;

        $response = $this->put(
            "/api/products/" . $to_modify["public_id"],
            $to_modify
        );
        $response->assertOk();

        $this->get("/api/products/" . $to_modify["public_id"])->assertJson(
            fn(AssertableJson $json) => $json
                ->where("name", "Modificado")
                ->where("price", 2.25)
                ->where("quantity", 112)
                ->etc()
        );
    }

    public function test_delete_a_product(): void
    {
        $this->seed();

        $to_delete = $this->get("/api/products")->json("data.0");

        $this->delete(
            "/api/products/" . $to_delete["public_id"]
        )->assertNoContent();

        $this->get(
            "/api/products/" . $to_delete["public_id"]
        )->assertNotFound();
    }
}
