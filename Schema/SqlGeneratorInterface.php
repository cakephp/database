<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.5.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Database\Schema;

use Cake\Datasource\ConnectionInterface;

/**
 * An interface used by TableSchema objects.
 */
interface SqlGeneratorInterface
{
    /**
     * Generate the SQL to create the Table.
     *
     * Uses the connection to access the schema dialect
     * to generate platform specific SQL.
     *
     * @param \Cake\Datasource\ConnectionInterface $connection The connection to generate SQL for.
     * @return array List of SQL statements to create the table and the
     *    required indexes.
     */
    public function createSql(ConnectionInterface $connection): array;

    /**
     * Generate the SQL to drop a table.
     *
     * Uses the connection to access the schema dialect to generate platform
     * specific SQL.
     *
     * @param \Cake\Datasource\ConnectionInterface $connection The connection to generate SQL for.
     * @return array SQL to drop a table.
     */
    public function dropSql(ConnectionInterface $connection): array;

    /**
     * Generate the SQL statements to truncate a table
     *
     * @param \Cake\Datasource\ConnectionInterface $connection The connection to generate SQL for.
     * @return array SQL to truncate a table.
     */
    public function truncateSql(ConnectionInterface $connection): array;

    /**
     * Generate the SQL statements to add the constraints to the table
     *
     * @param \Cake\Datasource\ConnectionInterface $connection The connection to generate SQL for.
     * @return array SQL to add the constraints.
     */
    public function addConstraintSql(ConnectionInterface $connection): array;

    /**
     * Generate the SQL statements to drop the constraints to the table
     *
     * @param \Cake\Datasource\ConnectionInterface $connection The connection to generate SQL for.
     * @return array SQL to drop a table.
     */
    public function dropConstraintSql(ConnectionInterface $connection): array;
}
