<?php

declare(strict_types=1);

namespace Psalm\Node\Expr;

use PhpParser\Node\Expr\StaticPropertyFetch;
use Psalm\Node\VirtualNode;

class VirtualStaticPropertyFetch extends StaticPropertyFetch implements VirtualNode
{

}
