<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MinMaxValidator
 * 
 * @category SymfonyBundle
 * @package  AppBundle\Validator\Constraints
 * @author   Jesús Flores <jesusfloressanjose@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */
class MinMaxValidator extends ConstraintValidator
{
    public function validate($data, Constraint $constraint){
        if ($data->getMin() >= $data->getMax()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('Alert')
                ->addViolation();
        }
    }

} 