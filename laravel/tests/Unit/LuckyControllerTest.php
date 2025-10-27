<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LuckyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LuckyControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var User */
    protected User $user;

    /** @var Mockery\MockInterface|LuckyService */
    protected $luckyServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->luckyServiceMock = Mockery::mock(LuckyService::class);
        $this->instance(LuckyService::class, $this->luckyServiceMock);

        $this->user = User::factory()->create([
            'active' => true,
            'expires_at' => now()->addDay(),
        ]);

        $this->luckyServiceMock->shouldReceive('findUserAndValidateLink')
            ->byDefault()
            ->with($this->user->token)
            ->andReturn($this->user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function show_method_displays_view_for_valid_token(): void
    {
        $response = $this->get(route('lucky.page', ['token' => $this->user->token]));

        $response->assertOk();
        $response->assertViewIs('lucky');
        $response->assertViewHas('user', $this->user);

        $this->luckyServiceMock->shouldHaveReceived('findUserAndValidateLink')->once();
    }

    /** @test */
    public function show_method_aborts_403_for_invalid_link(): void
    {
        $this->luckyServiceMock->shouldReceive('findUserAndValidateLink')
            ->once()
            ->with($this->user->token)
            ->andThrow(new HttpException(403, 'Link expired or deactivated'));

        $response = $this->get(route('lucky.page', ['token' => $this->user->token]));

        $response->assertStatus(403);
        $response->assertSee('Link expired or deactivated');
    }

    /** @test */
    public function show_method_returns_404_for_non_existent_token(): void
    {
        $nonExistentToken = 'non-existent-token';

        $this->luckyServiceMock->shouldReceive('findUserAndValidateLink')
            ->once()
            ->with($nonExistentToken)
            ->andThrow(new ModelNotFoundException());

        $response = $this->get(route('lucky.page', ['token' => $nonExistentToken]));
        $response->assertNotFound();
    }
}
