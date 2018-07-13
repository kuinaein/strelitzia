<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountTitle extends Model {
  protected $fillable = ['type', 'name'];
}
