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
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Database;

use Cake\Database\Expression\AggregateExpression;
use Cake\Database\Expression\FunctionExpression;
use InvalidArgumentException;

/**
 * Contains methods related to generating FunctionExpression objects
 * with most commonly used SQL functions.
 * This acts as a factory for FunctionExpression objects.
 */
class FunctionsBuilder
{
    /**
     * Returns a new instance of a FunctionExpression. This is used for generating
     * arbitrary function calls in the final SQL string.
     *
     * @param string $name the name of the SQL function to constructed
     * @param array $params list of params to be passed to the function
     * @param array $types list of types for each function param
     * @param string $return The return type of the function expression
     * @return \Cake\Database\Expression\FunctionExpression
     */
    protected function _build(
        string $name,
        array $params = [],
        array $types = [],
        string $return = 'string'
    ): FunctionExpression {
        return new FunctionExpression($name, $params, $types, $return);
    }

    /**
     * Helper function to build a function expression that only takes one literal
     * argument.
     *
     * @param string $name name of the function to build
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @param string $return The return type for the function
     * @return \Cake\Database\Expression\FunctionExpression
     */
    protected function _literalArgumentFunction(
        string $name,
        $expression,
        $types = [],
        $return = 'string'
    ): FunctionExpression {
        if (!is_string($expression)) {
            $expression = [$expression];
        } else {
            $expression = [$expression => 'literal'];
        }

        return $this->_build($name, $expression, $types, $return);
    }

    /**
     * Helper to build an aggregate function with a single literal argument.
     *
     * @param string $name name of the function to build
     * @param mixed $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @param string $return The return type for the function
     * @return \Cake\Database\Expression\AggregateExpression
     */
    protected function singleLiteralAggregate(string $name, $expression, array $types, string $return)
    {
        if (!is_string($expression)) {
            $expression = [$expression];
        } else {
            $expression = [$expression => 'literal'];
        }

        return $this->aggregate($name, $expression, $types, $return);
    }

    /**
     * Returns a FunctionExpression representing a call to SQL RAND function.
     *
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function rand(): FunctionExpression
    {
        return $this->_build('RAND', [], [], 'float');
    }

    /**
     * Returns a AggregateExpression representing a call to SQL SUM function.
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\AggregateExpression
     */
    public function sum($expression, $types = []): AggregateExpression
    {
        $returnType = 'float';
        if (current($types) === 'integer') {
            $returnType = 'integer';
        }

        return $this->singleLiteralAggregate('SUM', $expression, $types, $returnType);
    }

    /**
     * Returns a AggregateExpression representing a call to SQL AVG function.
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\AggregateExpression
     */
    public function avg($expression, $types = []): AggregateExpression
    {
        return $this->singleLiteralAggregate('AVG', $expression, $types, 'float');
    }

    /**
     * Returns a AggregateExpression representing a call to SQL MAX function.
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\AggregateExpression
     */
    public function max($expression, $types = []): AggregateExpression
    {
        return $this->singleLiteralAggregate('MAX', $expression, $types, current($types) ?: 'string');
    }

    /**
     * Returns a AggregateExpression representing a call to SQL MIN function.
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\AggregateExpression
     */
    public function min($expression, $types = []): AggregateExpression
    {
        return $this->singleLiteralAggregate('MIN', $expression, $types, current($types) ?: 'string');
    }

    /**
     * Returns a AggregateExpression representing a call to SQL COUNT function.
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\AggregateExpression
     */
    public function count($expression, $types = []): AggregateExpression
    {
        return $this->singleLiteralAggregate('COUNT', $expression, $types, 'integer');
    }

    /**
     * Returns a FunctionExpression representing a string concatenation
     *
     * @param array $args List of strings or expressions to concatenate
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function concat(array $args, array $types = []): FunctionExpression
    {
        return $this->_build('CONCAT', $args, $types, 'string');
    }

    /**
     * Returns a FunctionExpression representing a call to SQL COALESCE function.
     *
     * @param array $args List of expressions to evaluate as function parameters
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function coalesce(array $args, array $types = []): FunctionExpression
    {
        return $this->_build('COALESCE', $args, $types, current($types) ?: 'string');
    }

    /**
     * Returns a FunctionExpression representing the difference in days between
     * two dates.
     *
     * @param array $args List of expressions to obtain the difference in days.
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function dateDiff(array $args, array $types = []): FunctionExpression
    {
        return $this->_build('DATEDIFF', $args, $types, 'integer');
    }

    /**
     * Returns the specified date part from the SQL expression.
     *
     * @param string $part Part of the date to return.
     * @param string|\Cake\Database\ExpressionInterface $expression Expression to obtain the date part from.
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function datePart(string $part, $expression, array $types = []): FunctionExpression
    {
        return $this->extract($part, $expression, $types);
    }

    /**
     * Returns the specified date part from the SQL expression.
     *
     * @param string $part Part of the date to return.
     * @param string|\Cake\Database\ExpressionInterface $expression Expression to obtain the date part from.
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function extract(string $part, $expression, array $types = []): FunctionExpression
    {
        $expression = $this->_literalArgumentFunction('EXTRACT', $expression, $types, 'integer');
        $expression->setConjunction(' FROM')->add([$part => 'literal'], [], true);

        return $expression;
    }

    /**
     * Add the time unit to the date expression
     *
     * @param string|\Cake\Database\ExpressionInterface $expression Expression to obtain the date part from.
     * @param string|int $value Value to be added. Use negative to subtract.
     * @param string $unit Unit of the value e.g. hour or day.
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function dateAdd($expression, $value, string $unit, array $types = []): FunctionExpression
    {
        if (!is_numeric($value)) {
            $value = 0;
        }
        $interval = $value . ' ' . $unit;
        $expression = $this->_literalArgumentFunction('DATE_ADD', $expression, $types, 'datetime');
        $expression->setConjunction(', INTERVAL')->add([$interval => 'literal']);

        return $expression;
    }

    /**
     * Returns a FunctionExpression representing a call to SQL WEEKDAY function.
     * 1 - Sunday, 2 - Monday, 3 - Tuesday...
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function dayOfWeek($expression, $types = []): FunctionExpression
    {
        return $this->_literalArgumentFunction('DAYOFWEEK', $expression, $types, 'integer');
    }

    /**
     * Returns a FunctionExpression representing a call to SQL WEEKDAY function.
     * 1 - Sunday, 2 - Monday, 3 - Tuesday...
     *
     * @param string|\Cake\Database\ExpressionInterface $expression the function argument
     * @param array $types list of types to bind to the arguments
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function weekday($expression, $types = []): FunctionExpression
    {
        return $this->dayOfWeek($expression, $types);
    }

    /**
     * Returns a FunctionExpression representing a call that will return the current
     * date and time. By default it returns both date and time, but you can also
     * make it generate only the date or only the time.
     *
     * @param string $type (datetime|date|time)
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function now(string $type = 'datetime'): FunctionExpression
    {
        if ($type === 'datetime') {
            return $this->_build('NOW')->setReturnType('datetime');
        }
        if ($type === 'date') {
            return $this->_build('CURRENT_DATE')->setReturnType('date');
        }
        if ($type === 'time') {
            return $this->_build('CURRENT_TIME')->setReturnType('time');
        }

        throw new InvalidArgumentException('Invalid argument for FunctionsBuilder::now(): ' . $type);
    }

    /**
     * Helper method to create arbitrary SQL aggregate function calls.
     *
     * @param string $name The SQL aggregate function name
     * @param array $params Array of arguments to be passed to the function.
     *     Can be an associative array with the literal value or identifier:
     *     `['value' => 'literal']` or `['value' => 'identifier']
     * @param array $types Array of types that match the names used in `$params`:
     *     `['name' => 'type']`
     * @param string $return Return type of the entire expression. Defaults to float.
     * @return \Cake\Database\Expression\AggregateExpression
     */
    public function aggregate(string $name, array $params = [], array $types = [], string $return = 'float')
    {
        return new AggregateExpression($name, $params, $types, $return);
    }

    /**
     * Magic method dispatcher to create custom SQL function calls
     *
     * @param string $name the SQL function name to construct
     * @param array $args list with up to 3 arguments, first one being an array with
     * parameters for the SQL function, the second one a list of types to bind to those
     * params, and the third one the return type of the function
     * @return \Cake\Database\Expression\FunctionExpression
     */
    public function __call(string $name, array $args): FunctionExpression
    {
        return $this->_build($name, ...$args);
    }
}
