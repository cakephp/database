<?php
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
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Database\Type;

use Cake\Database\Driver;
use InvalidArgumentException;
use PDO;

/**
 * Integer type converter.
 *
 * Use to convert integer data between PHP and the database types.
 */
class IntegerType extends BaseType implements BatchCastingInterface
{
    /**
     * Convert integer data into the database format.
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return int|null
     */
    public function toDatabase($value, Driver $driver)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_scalar($value)) {
            throw new InvalidArgumentException(sprintf(
                'Cannot convert value of type `%s` to integer',
                getTypeName($value)
            ));
        }

        return (int)$value;
    }

    /**
     * Convert integer values to PHP integers
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return int|null
     */
    public function toPHP($value, Driver $driver)
    {
        if ($value === null) {
            return $value;
        }

        return (int)$value;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function manyToPHP(array $values, array $fields, Driver $driver)
    {
        foreach ($fields as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $values[$field] = (int)$values[$field];
        }

        return $values;
    }

    /**
     * Get the correct PDO binding type for integer data.
     *
     * @param mixed $value The value being bound.
     * @param \Cake\Database\Driver $driver The driver.
     * @return int
     */
    public function toStatement($value, Driver $driver)
    {
        return PDO::PARAM_INT;
    }

    /**
     * Marshalls request data into PHP floats.
     *
     * @param mixed $value The value to convert.
     * @return int|null Converted value.
     */
    public function marshal($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value) || ctype_digit($value)) {
            return (int)$value;
        }
        if (is_array($value)) {
            return 1;
        }

        return null;
    }
}
