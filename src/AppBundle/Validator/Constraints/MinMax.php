<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class MinMax
 * 
 * @category SymfonyBundle
 * @package  AppBundle\Validator\Constraints
 * @author   Jesús Flores <jesusfloressanjose@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License

 */
class MinMax extends Constraint
{
    public $message = 'Invalid min/max values.';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
} 