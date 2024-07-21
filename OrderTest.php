namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 測試有效的訂單數據。
     */
    public function testValidOrder()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 1500,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 1500,
            'currency' => 'TWD'
        ]);
    }

    /**
     * 測試name包含非英文字母。
     */
    public function testInvalidNameWithNonEnglishCharacters()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000002',
            'name' => 'Melody Holiday 123',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 1500,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJson(['errors' => ['name' => ['Name contains non-English characters']]]);
    }

    /**
     * 測試name每個單字首字母非大寫。
     */
    public function testInvalidNameNotCapitalized()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000003',
            'name' => 'melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 1500,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJson(['errors' => ['name' => ['Name Is not capitalized']]]);
    }

    /**
     * 測試訂單金額超過2000。
     */
    public function testPriceOver2000()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000004',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 2500,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['price']);
        $response->assertJson(['errors' => ['price' => ['Price Is over 2000']]]);
    }

    /**
     * 測試貨幣格式若非TWD或USD。
     */
    public function testInvalidCurrencyFormat()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000005',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 1500,
            'currency' => 'EUR'
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['currency']);
        $response->assertJson(['errors' => ['currency' => ['Currency format Is wrong']]]);
    }

    /**
     * 測試當貨幣為USD時，修改price和currency。
     */
    public function testCurrencyConversionFromUSDToTWD()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000006',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 50,
            'currency' => 'USD'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => 'A0000006',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => 1550, // 50 * 31
            'currency' => 'TWD'
        ]);
    }
}