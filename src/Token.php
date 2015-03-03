<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Search;

use Cake\Utility\Inflector;

/**
 * Represents a token within a search criteria.
 *
 */
class Token
{

    /**
     * Token information.
     *
     * @var array
     */
    protected $_data = [
        'string' => null,
        'previousToken' => null,
        'negated' => false,
        'where' => null,
        'isOperator' => false,
        'operatorName' => '',
        'operatorArguments' => '',
    ];

    /**
     * Constructor.
     *
     * @param string $token The string representing this token
     */
    public function __construct($token, $where = null)
    {
        $this->_data['string'] = str_starts_with($token, '-') ? str_replace_once('-', '', $token) : $token;
        $this->_data['negated'] = str_starts_with($token, '-');
        $this->_data['where'] = $where !== null ? strtolower($where) : null;
        $this->_data['isOperator'] = mb_strpos($token, ':') !== false;

        if ($this->_data['isOperator']) {
            $parts = explode(':', $token);
            $this->_data['operatorName'] = (string)Inflector::underscore(preg_replace('/\PL/u', '', $parts[0]));
            $this->_data['operatorArguments'] = !empty($parts[1]) ? $parts[1] : '';

            if ($this->_data['negated']) {
                $this->_data['operatorName'] = str_replace_once('-', '', $this->_data['operatorName']);
            }
        }
    }

    /**
     * Indicates the type of "where()" methods that should be used to scope when
     * using this token: "andWhere()", "orWhere" or just "where()".
     *
     * - `or`: Indicates that `Query::orWhere()` should be used
     * - `and`: Indicates that `Query::andWhere()` should be used
     * - `NULL`: Indicates that `Query::where()` should be used
     *
     * @return string Possible values are: `or`, `and` & null
     */
    public function where()
    {
        return $this->_data['where'];
    }

    /**
     * Indicates this token represents an operator.
     *
     * @return boolean True if it's an operator
     */
    public function isOperator()
    {
        return $this->_data['isOperator'];
    }

    /**
     * Gets operator's name.
     *
     * Should be used only when this token is an operator.
     *
     * @return string
     */
    public function operatorName()
    {
        return $this->_data['operatorName'];
    }

    /**
     * Alias for operatorName().
     *
     * @return string
     */
    public function name()
    {
        if ($this->isOperator()) {
            return $this->_data['operatorName'];
        }
        return $this->_data['string'];
    }

    /**
     * Alias for operatorArguments().
     *
     * @return string
     */
    public function value()
    {
        if ($this->isOperator()) {
            return $this->_data['operatorArguments'];
        }
        return $this->_data['string'];
    }

    /**
     * Gets operator's argument.
     *
     * Should be used only when this token is an operator.
     *
     * @return string
     */
    public function operatorArguments()
    {
        return $this->_data['operatorArguments'];
    }

    /**
     * Indicates this token was negated using "-".
     *
     * @return boolean True if it's negated
     */
    public function negated()
    {
        return $this->_data['negated'];
    }

    /**
     * Magic method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_data['string'];
    }
}
