<?php

interface BdAdapter
{
    public function addQuake($quake);
    public function findQuake($quake);
    public function updateQuake($quake);
}
