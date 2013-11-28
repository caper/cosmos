<?php

    class Mikron_Baseobject {

        /**
        * @param Mikron_Criteria $criteria
        */
        public static function load($criteria) {
            db::query($criteria);
        }

    }

?>