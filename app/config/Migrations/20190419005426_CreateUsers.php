<?php
use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');

        $table->addColumn('username', 'string', [
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('first_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('last_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('password', 'string', [
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);
        $table->addColumn('api_key', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('api_key_plain', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('token_expires', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('is_superuser', 'boolean', [
            'default' => false,
            'null' => false
        ]);
        $table->addColumn('role', 'string', [
            'default' => 'user',
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('active', 'boolean', [
            'default' => true,
            'null' => false
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false
        ]);
        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created_by', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('modified_by', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->addIndex(['username', 'email', 'token']);

        $table->create();
    }
}
