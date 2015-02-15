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
namespace Search\Operator;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Search\Operator\Operator;

/**
 * Handles "limit" search operator.
 *
 *     limit:<number>
 *
 * Limits the number of results.
 */
class LimitOperator extends Operator
{

    /**
     * {@inheritdoc}
     */
    public public function scope(Query $query, $value, $negate, $orAnd)
    {
        if ($negate) {
            return $query;
        }

        $value = intval($value);

        if ($value > 0) {
            $query->limit($value);
        }

        return $query;
    }
}
