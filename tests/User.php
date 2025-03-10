<?php

namespace vsrklabs\Permission\Test;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Jenssegers\Mongodb\Eloquent\Model;
use Maklad\Permission\Traits\HasRoles;

/**
 * Class User
 * @package Maklad\Permission\Test
 */
class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasRoles, Authorizable, Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    public $timestamps = false;

    protected $collection = 'users';
    protected $connection = 'mongodb';
}
