<?php

namespace App\Repository\Interface;
use App\Model\Lamaran;

interface RLamaran {
    public function insert(Lamaran $lamaran): Lamaran;
    public function delete(int $lamaranId): Lamaran;
    public function save(Lamaran $lamaran): Lamaran;

}