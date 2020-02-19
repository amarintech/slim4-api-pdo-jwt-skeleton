<?php
namespace Model
{
    class Api extends Meta
    {
        public function toto()
        {
            return 'toto';
        }
        public function UserAuth($email,$password){
            $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'email' => $email,
                'password' => $password
            ));
            $count = $query->rowCount();
            if ($count > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        public function test(){
            $sql = "SELECT * from users";
            $query = $this
            ->db
            ->prepare($sql);
        $query->execute();
        return $query->fetchAll();
        }
        public function verify_email($email)
        {
            //categorie_1
            $sql = "SELECT * FROM users WHERE email = :email AND valide = '1' LIMIT 1";
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'email' => $email
            ));
            $count = $query->rowCount();
            if ($count > 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        
    }
}
