<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Agency;
use App\Models\EntityAuditTrail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

class CompanyDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $company;
    protected $agency;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user
        $this->user = User::factory()->create();
        
        // Create a company
        $this->company = Company::factory()->create([
            'raison_sociale' => 'Test Company',
            'type' => 'holding',
            'active' => true
        ]);
        
        // Create an agency
        $this->agency = Agency::factory()->create([
            'nom' => 'Test Agency',
            'company_id' => $this->company->id,
            'statut' => 'active',
            'responsable_id' => $this->user->id
        ]);
        
        // Create some audit trails
        EntityAuditTrail::factory()->count(5)->create([
            'entity_id' => $this->company->id,
            'entity_type' => 'company',
            'user_id' => $this->user->id,
            'action' => 'created'
        ]);
        
        EntityAuditTrail::factory()->count(3)->create([
            'entity_id' => $this->agency->id,
            'entity_type' => 'agency',
            'user_id' => $this->user->id,
            'action' => 'updated'
        ]);
    }

    /** @test */
    public function it_can_display_company_dashboard()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('companies.dashboard.company', $this->company->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('companies.dashboard.company');
        $response->assertViewHas('company');
        $response->assertViewHas('recentActivities');
        $response->assertViewHas('alerts');
    }

    /** @test */
    public function it_can_display_agency_dashboard()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('companies.dashboard.agency', $this->agency->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('companies.dashboard.agency');
        $response->assertViewHas('agency');
        $response->assertViewHas('recentActivities');
        $response->assertViewHas('alerts');
    }

    /** @test */
    public function it_shows_recent_activities_on_company_dashboard()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('companies.dashboard.company', $this->company->id));
        
        $response->assertStatus(200);
        $this->assertInstanceOf(Collection::class, $response->viewData('recentActivities'));
    }

    /** @test */
    public function it_shows_recent_activities_on_agency_dashboard()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('companies.dashboard.agency', $this->agency->id));
        
        $response->assertStatus(200);
        $this->assertInstanceOf(Collection::class, $response->viewData('recentActivities'));
    }

    /** @test */
    public function it_shows_alerts_on_dashboards()
    {
        // Test company dashboard alerts
        $response = $this->actingAs($this->user)
                         ->get(route('companies.dashboard.company', $this->company->id));
        
        $response->assertStatus(200);
        $this->assertIsArray($response->viewData('alerts'));
        
        // Test agency dashboard alerts
        $response = $this->actingAs($this->user)
                         ->get(route('companies.dashboard.agency', $this->agency->id));
        
        $response->assertStatus(200);
        $this->assertIsArray($response->viewData('alerts'));
    }
}