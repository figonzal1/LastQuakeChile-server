<?php


interface QuakeInterface
{
    public function addQuake(object $quake): bool;
    public function updateQuake(object $quake): bool;

    public function findQuake(object $quake): array;
    public function findQuakesOfMonth(string $prev_mont): array;
}
