<?php

    class Prodom_Api_Services {

        private static $services = array();

        /**
         * Стандартный механизм получения и кеширования прокси
         * 
         * @param string $hostName
         * @param string $serviceName
         * @param string $siteApiKey
         * 
         * @return Prodom_Api_Client
         */
        protected static function getProxy($hostName, $serviceName, $siteApiKey) {
            if (!array_key_exists($serviceName, self::$services)) {
                self::$services[$serviceName] = new Prodom_Api_Client($hostName, $serviceName);
                self::$services[$serviceName]->setApiKey($siteApiKey);
            }
            return self::$services[$serviceName];
        }

    }
