<?php
use Illuminate\Support\Collection;

$employees = collect([
    ['name' => 'John', 'city' => 'Dallas'],
    ['name' => 'Jane', 'city' => 'Austin'],
    ['name' => 'Jake', 'city' => 'Dallas'],
    ['name' => 'Jill', 'city' => 'Dallas'],
]);

$offices = collect([
    ['office' => 'Dallas HQ', 'city' => 'Dallas'],
    ['office' => 'Dallas South', 'city' => 'Dallas'],
    ['office' => 'Austin Branch', 'city' => 'Austin'],
]);

$output = $offices->groupBy('city')->map(function ($cityOffices, $city) use ($employees) {
    $cityEmployees = $employees->where('city', $city)->pluck('name');
    return $cityOffices->mapWithKeys(function ($office) use ($cityEmployees) {
        return [$office['office'] => $cityEmployees];
    });
});
