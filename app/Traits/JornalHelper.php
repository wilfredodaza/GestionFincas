<?php

namespace App\Traits;

use Config\Database;

trait JornalHelper
{
    /**
     * Retorna el ID del recurso "jornal" según las fincas permitidas
     */
    protected function getJornalId(array $farmIds): ?int
    {
        if (empty($farmIds)) {
            return null;
        }

        $db = Database::connect();

        $row = $db->table('resources')
            ->select('id')
            ->whereIn('farm_id', $farmIds)
            ->where('LOWER(TRIM(name))', 'jornal')
            ->limit(1)
            ->get()
            ->getRow();

        return $row ? (int) $row->id : null;
    }

    /**
     * Retorna TRUE si el usuario tiene recurso Jornal
     */
    protected function hasJornal(array $farmIds): bool
    {
        return $this->getJornalId($farmIds) !== null;
    }
}
