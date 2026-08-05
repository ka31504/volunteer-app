<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->project = Project::factory()->create([
            'target_amount'    => 10_000_000,
            'current_amount' => 0,
        ]);
    }

    // -------------------------------------------------------
    // INDEX
    // -------------------------------------------------------

    public function test_guest_cannot_view_donations(): void
    {
        $this->get(route('donations.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_donations_list(): void
    {
        Donation::factory()->count(3)->create(['project_id' => $this->project->id]);

        $this->actingAs($this->user)
            ->get(route('donations.index'))
            ->assertOk()
            ->assertViewIs('donations.index')
            ->assertViewHas('donations');
    }

    // -------------------------------------------------------
    // CREATE / STORE — tiền mặt
    // -------------------------------------------------------

    public function test_can_create_money_donation(): void
    {
        $payload = [
            'project_id'     => $this->project->id,
            'donor_name'     => 'Nguyễn Văn A',
            'donor_phone'    => '0912345678',
            'type'           => 'money',
            'amount'         => 500_000,
            'payment_method' => 'transfer',
            'donated_at'     => '2026-05-20',
            'note'           => 'Ủng hộ dự án',
        ];

        $this->actingAs($this->user)
            ->post(route('donations.store'), $payload)
            ->assertRedirect(route('donations.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Nguyễn Văn A',
            'amount'     => 500_000,
            'type'       => 'money',
        ]);
    }

    public function test_money_donation_syncs_project_current_amount(): void
    {
        $this->actingAs($this->user)
            ->post(route('donations.store'), [
                'project_id'     => $this->project->id,
                'donor_name'     => 'Trần Thị B',
                'type'           => 'money',
                'amount'         => 2_000_000,
                'payment_method' => 'cash',
                'donated_at'     => '2026-05-20',
            ]);

        $this->assertEquals(2_000_000, $this->project->fresh()->current_amount);
    }

    // -------------------------------------------------------
    // CREATE / STORE — hiện vật
    // -------------------------------------------------------

    public function test_can_create_goods_donation(): void
    {
        $payload = [
            'project_id'        => $this->project->id,
            'donor_name'        => 'Lê Văn C',
            'type'              => 'goods',
            'goods_description' => 'Gạo',
            'goods_quantity'    => 50,
            'payment_method'    => 'other',
            'donated_at'        => '2026-05-20',
        ];

        $this->actingAs($this->user)
            ->post(route('donations.store'), $payload)
            ->assertRedirect(route('donations.index'));

        $this->assertDatabaseHas('donations', [
            'type'              => 'goods',
            'goods_description' => 'Gạo',
            'goods_quantity'    => 50,
        ]);
    }

    public function test_goods_donation_does_not_change_project_amount(): void
    {
        $this->actingAs($this->user)
            ->post(route('donations.store'), [
                'project_id'        => $this->project->id,
                'donor_name'        => 'Lê Văn C',
                'type'              => 'goods',
                'goods_description' => 'Mì tôm',
                'goods_quantity'    => 100,
                'payment_method'    => 'other',
                'donated_at'        => '2026-05-20',
            ]);

        // current_amount không đổi vì là hiện vật
        $this->assertEquals(0, $this->project->fresh()->current_amount);
    }

    // -------------------------------------------------------
    // VALIDATION
    // -------------------------------------------------------

    public function test_money_donation_requires_amount(): void
    {
        $this->actingAs($this->user)
            ->post(route('donations.store'), [
                'project_id'     => $this->project->id,
                'donor_name'     => 'Test',
                'type'           => 'money',
                // thiếu amount
                'payment_method' => 'cash',
                'donated_at'     => '2026-05-20',
            ])
            ->assertSessionHasErrors('amount');
    }

    public function test_goods_donation_requires_description_and_quantity(): void
    {
        $this->actingAs($this->user)
            ->post(route('donations.store'), [
                'project_id'     => $this->project->id,
                'donor_name'     => 'Test',
                'type'           => 'goods',
                // thiếu goods_description và goods_quantity
                'payment_method' => 'other',
                'donated_at'     => '2026-05-20',
            ])
            ->assertSessionHasErrors(['goods_description', 'goods_quantity']);
    }

    public function test_amount_must_be_at_least_1000(): void
    {
        $this->actingAs($this->user)
            ->post(route('donations.store'), [
                'project_id'     => $this->project->id,
                'donor_name'     => 'Test',
                'type'           => 'money',
                'amount'         => 500, // < 1000
                'payment_method' => 'cash',
                'donated_at'     => '2026-05-20',
            ])
            ->assertSessionHasErrors('amount');
    }

    // -------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------

    public function test_can_update_donation(): void
    {
        $donation = Donation::factory()->create([
            'project_id' => $this->project->id,
            'type'       => 'money',
            'amount'     => 500_000,
        ]);

        $this->actingAs($this->user)
            ->put(route('donations.update', $donation), [
                'project_id'     => $this->project->id,
                'donor_name'     => 'Tên mới',
                'type'           => 'money',
                'amount'         => 1_000_000,
                'payment_method' => 'transfer',
                'donated_at'     => '2026-05-20',
            ])
            ->assertRedirect(route('donations.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('donations', [
            'id'         => $donation->id,
            'donor_name' => 'Tên mới',
            'amount'     => 1_000_000,
        ]);

        // current_amount được sync lại
        $this->assertEquals(1_000_000, $this->project->fresh()->current_amount);
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------

    public function test_can_delete_donation(): void
    {
        $donation = Donation::factory()->create([
            'project_id' => $this->project->id,
            'type'       => 'money',
            'amount'     => 300_000,
        ]);

        $this->actingAs($this->user)
            ->delete(route('donations.destroy', $donation))
            ->assertRedirect(route('donations.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('donations', ['id' => $donation->id]);

        // current_amount về 0 sau khi xoá
        $this->assertEquals(0, $this->project->fresh()->current_amount);
    }
}