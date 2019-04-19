<?php
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'username' => 'johndoe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'password' => '$2y$10$zaBa2EEsWrt/GBzPbhex0e7Pwz1xbIyjHrWhlTMrkONA8imQaZECe',
                'email' => 'john@example.com',
                'api_key' => '$2y$10$C0sxJlrIHL8sCOhmXCRurOYmQVOixe1PpUCL2Q3FpNF7HnoJ8rruK',
                'api_key_plain' => 'c0ef776570173e91d03b80396997ef58d074d2dec341e9c70561d5e3fdb7b575',
                'token' => '',
                'token_expires' => NULL,
                'is_superuser' => '0',
                'role' => 'admin',
                'active' => '1',
                'created' => '2019-04-19 02:35:50',
                'modified' => '2019-04-19 02:35:50',
                'deleted' => NULL,
                'created_by' => NULL,
                'modified_by' => NULL,
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
