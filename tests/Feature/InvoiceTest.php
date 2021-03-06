<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceTest extends TestCase
{
    use DatabaseTransactions,DatabaseMigrations;

    private $invoices;
    private $users;
    private $categories;
    private $house;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $content = $this->signUp();
        //$this->signIn();
        $this->assertObjectHasAttribute('access_token', $content);
        $this->token = $content->access_token;

        $this->seed(\DatabaseFakeSeeder::class);
        $this->users = \App\Domains\Users\User::all();
        $this->categories = \App\Domains\Categories\Category::all();
        $this->house = \App\Domains\Houses\House::all()->first();

    }

    public function testCreate()
    {
        $headers['Authorization'] = 'Bearer '. $this->token;
        $response = $this->json('POST','api/invoices',[
            'price' => '10.90',
            'description' => 'Martelo comprado na casa de construções',
            'user_id' => $this->users->first()->id,//userCreator
            'user_payment_id' => $this->users->first()->id,//userWhoPaid
            'category_id' => $this->categories->first()->id,
            'house_id' => $this->house->id,
            'parcels'  => 1
        ],$headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' =>[
                    'id',
                    'price',
                    'description',
                    'user_id',
                    'user_payment_id',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }


        public function testCreateFailed()
        {
            //TODO: create test to api token
            //$headers['Authorization'] = 'Bearer '. $this->token;
            //$this->json('POST','api/invoices',['name'=>'comida'])
             //   ->assertStatus(401)
             //   ->assertSee('Token not provided');

            $headers['Authorization'] = 'Bearer '. $this->token;
            $response = $this->json('POST','api/invoices',['price'=>'co'],$headers)
                ->assertStatus(422)
                ->assertSee('price format is invalid');
        }

        public function testGetAll()
        {
            $this->seed(\InvoiceFakeSeeder::class);
            $headers['Authorization'] = 'Bearer '. $this->token;
            $response = $this->json('GET','api/invoices',[],$headers)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*'=>[
                            'id',
                            'price',
                            'description',
                            'user_id',
                            'user_payment_id',
                            'category_id',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
        }

            public function testDelete()
            {
                $this->seed(\InvoiceFakeSeeder::class);
                $headers['Authorization'] = 'Bearer '. $this->token;
                $this->json('DELETE','api/invoices/1',[],$headers)
                    ->assertStatus(200)
                    ->assertJson(['message'=>'Successful']);
            }
            /*
            public function testUpdate()
            {
                $headers['Authorization'] = 'Bearer '. $this->token;
                $this->json('PATCH','api/invoices/3',['parcels'=>2],$headers)
                    ->assertStatus(200)
                    ->assertJson([
                        'data' =>[
                            'id'=>3,
                            'name'=>'Minha Rep'
                        ]
                    ]);
            }
            */

            public function testUpdateFailed()
            {
                $headers['Authorization'] = 'Bearer '. $this->token;
                $this->json('PATCH','api/invoices/3',['name'=>'ca'],$headers)
                    ->assertStatus(422)
                    ->assertSee('price field is required');
            }

}
