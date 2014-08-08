<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * PHP version 5
 *
 * @category   Classe User
 * @package    User (Modelo)
 * @copyright  2014 Sou Digital
 */
class User extends BaseModel implements UserInterface, RemindableInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Variável padrão para as regras de validação 
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required|min:3|max:255',
        'username' => 'required',
        'email' => 'required|email',
        'confirma' => 'same:password'
    );
    //Variável padrão para as mensagens personalizadas
    public static $messages = array(
        'name.required' => 'O campo Nome &eacute; requerido',
        'username.required' => 'O campo Username &eacute; requerido',
        'email.required' => 'O campo E-mail &eacute; requerido',
        'email.email' => 'O campo E-mail n&atilde; &eacute; v&aacute;lido',
    );

    /**
     * @desc Faz a ligação (Muitos para Muitos => nav_item_user) com a tabela de NavItem
     * @return NavItem $navitem
     * */
    public function navitems() {
        return $this->belongsToMany('NavItem');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    public function getRememberToken() {
        return $this->remember_token;
    }

    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

    public static function getUsers()
    {
        return User::where('id', '<>', 1)->get();
    }

}
