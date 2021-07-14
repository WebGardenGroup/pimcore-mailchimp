<?php

use Doctrine\DBAL\Connection;
use Pimcore\Db\ConnectionInterface;
use Pimcore\Db\PimcoreExtensionsTrait;

/**
 * Simple connection class for sqlite SettingsStore tests.
 */
class DbConnection extends Connection implements ConnectionInterface
{
    /*
     * Pimcore assumes MySQL, and does some SET statements in the connect function.
     */
    use PimcoreExtensionsTrait {
        PimcoreExtensionsTrait::connect as pimcoreConnect;
    }

    public function connect()
    {
        parent::connect();
    }

    /**
     * @param string $table
     *
     * @return int
     *
     * @throws \Exception
     */
    public function insertOrUpdate($table, array $data)
    {
        // extract and quote col names from the array keys
        $i = 0;
        $bind = [];
        $cols = [];
        $vals = [];
        foreach ($data as $col => $val) {
            $cols[] = $this->quoteIdentifier($col);
            $bind[':col'.$i] = $val;
            $vals[] = ':col'.$i;
            ++$i;
        }

        $sql = sprintf(
            'REPLACE INTO %s (%s) VALUES (%s);',
            $this->quoteIdentifier($table),
            implode(', ', $cols),
            implode(', ', $vals)
        );

        $bind = array_merge($bind, $bind);
        $result = $this->executeUpdate($sql, $bind);

        return $result;
    }
}
