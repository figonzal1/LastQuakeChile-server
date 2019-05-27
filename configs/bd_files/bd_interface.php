<?php

interface BdAdapter{
    public function addQuake($quake);
    public function findQuake($imagen);
    public function updateQuake($quake);
}
?>