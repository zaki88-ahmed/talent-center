<?php


use App\Models\Group;

class ValidGroupId{


    public  function passes($attribute, $values){



        $groups_ids = Group::pluk('id')->toArray();

        foreach ($values as $value){

            if(! in_array($value[0], $groups_ids)){

                return false;
            }
        }

    }


}
