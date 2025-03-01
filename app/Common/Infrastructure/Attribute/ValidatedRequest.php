<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ValidatedRequest
{
}