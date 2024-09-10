<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Enums\{ProductState, ProductStoreAction};

class ProductTest extends WebTestCase
{
    public function testFirstLoad(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'DLM Solutions Test By Gabor Csatlos');
    }
    
    public function testProductCreateLoad(): void
    {
        $client = static::createClient();
        $client->request('GET', '/create');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'DLM Solutions Test By Gabor Csatlos');
    }
    
    public function testProductCreatePost(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/create');
        
        $buttonCrawlerNode = $crawler->selectButton('Save');
        
        $form = $buttonCrawlerNode->form();
        
        $client->submit($form, [
            'product_create[name]' => 'Test case Item',
            'product_create[productNumber]' => 'RAND'.rand(20,99),
            'product_create[state]' => ProductState::Test->value,
            'product_create[actualStockAmount]' => 8
        ]);
        
        $this->assertResponseRedirects('/');
    }
    
    public function testProductEditPost(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/edit/1');
        
        $buttonCrawlerNode = $crawler->selectButton('Save');
        
        $form = $buttonCrawlerNode->form();
        
        $client->submit($form, [
            'product_edit[name]' => 'Test case Item',
            'product_edit[description]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat malesuada velit eget volutpat. Proin faucibus quam ac quam finibus euismod. Cras ac tincidunt orci. Etiam tincidunt tellus vel scelerisque commodo. Phasellus dignissim molestie ipsum. Pellentesque ac turpis at justo tempor condimentum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Phasellus eu mattis nisl, eget laoreet ex. Pellentesque maximus a ex ac egestas. Aliquam felis enim, convallis in sodales non, pellentesque vitae ante.',
            'product_edit[state]' => ProductState::Test->value,
        ]);
        $this->assertResponseRedirects('/');
    }
    
    public function testProductStoreActivityCreatePost(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product-store-activity/1/create');
        
        $buttonCrawlerNode = $crawler->selectButton('Save');
        
        $form = $buttonCrawlerNode->form();
        
        $client->submit($form, [
            'product_store_activity_create[action]' => ProductStoreAction::Add->value,
            'product_store_activity_create[amount]' => 8
        ]);
        
        $this->assertResponseRedirects('/');
    }
}
