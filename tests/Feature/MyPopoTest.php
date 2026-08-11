<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyPopoTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_creates_default_pointer_vehicle(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertDatabaseHas('vehicles', [
            'name' => 'Mi Pointer 2005',
            'brand' => 'Volkswagen',
            'model' => 'Pointer',
            'year' => 2005,
            'tank_capacity' => 51.00,
        ]);
    }

    public function test_add_fuel_load_updates_current_liters_capped_at_capacity(): void
    {
        $vehicle = Vehicle::create([
            'name' => 'Mi Pointer 2005',
            'brand' => 'Volkswagen',
            'model' => 'Pointer',
            'year' => 2005,
            'tank_capacity' => 51.00,
            'current_liters' => 20.00,
            'avg_consumption' => 12.50,
            'initial_avg_consumption' => 12.50,
        ]);

        $response = $this->post('/gasolina', [
            'amount_paid' => 480.00,
            'price_per_liter' => 24.00, // 20 Litros
            'is_full_tank' => 0,
            'date' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/');
        $vehicle->refresh();
        $this->assertEquals(40.00, $vehicle->current_liters);
    }

    public function test_full_tank_fuel_load_recalibrates_avg_consumption(): void
    {
        $vehicle = Vehicle::create([
            'name' => 'Mi Pointer 2005',
            'brand' => 'Volkswagen',
            'model' => 'Pointer',
            'year' => 2005,
            'tank_capacity' => 51.00,
            'current_liters' => 10.00,
            'avg_consumption' => 12.50,
            'initial_avg_consumption' => 12.50,
        ]);

        // Registrar viaje previo de 200 km
        $this->post('/recorrido/manual', [
            'distance_km' => 200.00,
            'title' => 'Viaje de prueba',
        ]);

        // Cargar 20 L llenando tanque por completo (200 km / 20 L = 10 km/L recalibrado)
        $response = $this->post('/gasolina', [
            'amount_paid' => 500.00,
            'price_per_liter' => 25.00, // 20 Litros necesarios para llenar
            'is_full_tank' => 1,
            'date' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/');
        $vehicle->refresh();

        $this->assertEquals(51.00, $vehicle->current_liters); // Lleno total
        $this->assertEquals(10.00, $vehicle->avg_consumption);
    }

    public function test_start_and_finish_live_trip_updates_tank(): void
    {
        $startResponse = $this->postJson('/recorrido/iniciar', ['lat' => 19.43, 'lng' => -99.13]);
        $startResponse->assertStatus(200);
        $startResponse->assertJson(['success' => true]);
        $tripId = $startResponse->json('trip.id');

        $finishResponse = $this->postJson("/recorrido/finalizar/{$tripId}", [
            'distance_km' => 25.0,
            'liters_consumed' => 2.0,
        ]);

        $finishResponse->assertStatus(200);
        $finishResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('trips', [
            'id' => $tripId,
            'status' => 'completed',
            'distance_km' => 25.0,
            'liters_consumed' => 2.0,
        ]);
    }

    public function test_finish_live_trip_without_id_in_url_finds_active_trip(): void
    {
        $startResponse = $this->postJson('/recorrido/iniciar', ['lat' => 19.43, 'lng' => -99.13]);
        $tripId = $startResponse->json('trip.id');

        $finishResponse = $this->postJson('/recorrido/finalizar', [
            'distance_km' => 10.0,
            'liters_consumed' => 1.0,
        ]);

        $finishResponse->assertStatus(200);
        $finishResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('trips', [
            'id' => $tripId,
            'status' => 'completed',
            'distance_km' => 10.0,
        ]);
    }

    public function test_delete_trip_refunds_fuel_to_tank(): void
    {
        $vehicle = Vehicle::create([
            'name' => 'Mi Pointer 2005',
            'brand' => 'Volkswagen',
            'model' => 'Pointer',
            'year' => 2005,
            'tank_capacity' => 51.00,
            'current_liters' => 30.00,
            'avg_consumption' => 10.00,
            'initial_avg_consumption' => 12.50,
        ]);

        // Iniciar y finalizar viaje de 50 km (consume 5 L, tanque pasa a 25 L)
        $startResponse = $this->postJson('/recorrido/iniciar');
        $tripId = $startResponse->json('trip.id');
        $this->postJson("/recorrido/finalizar/{$tripId}", [
            'distance_km' => 50.0,
            'liters_consumed' => 5.0,
        ]);

        $vehicle->refresh();
        $this->assertEquals(25.00, $vehicle->current_liters);

        // Cancelar / eliminar el viaje
        $deleteResponse = $this->delete("/recorrido/{$tripId}");
        $deleteResponse->assertRedirect('/historial');

        $vehicle->refresh();
        $this->assertEquals(30.00, $vehicle->current_liters); // Gasolina devuelta al tanque
        $this->assertDatabaseMissing('trips', ['id' => $tripId]);
    }

    public function test_history_and_stats_views_render_successfully(): void
    {
        $this->get('/historial')->assertStatus(200);
        $this->get('/estadisticas')->assertStatus(200);
        $this->get('/vehiculo')->assertStatus(200);
    }
}
