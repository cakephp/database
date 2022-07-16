<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         5.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Database;

enum DriverFeatureEnum: string
{
    /**
     * Common Table Expressions (with clause) support.
     */
    case CTE = 'cte';

    /**
     * Disabling constraints without being in transaction support.
     */
    case DISABLE_CONSTRAINT_WITHOUT_TRANSACTION = 'disble-constarint-without-transaction';

    /**
     * Native JSON data type support.
     */
    case JSON = 'json';

    /**
     * PDO::quote() support.
     */
    case PDO_QUOTE = 'pdo-quote';

    /**
     * Transaction savepoint support.
     */
    case SAVEPOINT = 'savepoint';

    /**
     * Truncate with foreign keys attached support.
     */
    case TRUNCATE_WITH_CONSTRAINTS = 'truncate-with-constraints';

    /**
     * Window function support (all or partial clauses).
     */
    case WINDOW = 'window';
}
