<?php

    class Mikron_Dataobject {

        public $name = null;
        private $attributes = Array();
        public $SourceXML = null;

        function Values() {
            return $this->attributes;
        }

        /**
        * Схема данных
        */
        private static $XSD = '<?xml version="1.0" encoding="utf-8"?>
<xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" xmlns:xs="http://www.w3.org/2001/XMLSchema">
  <xs:element name="data">
    <xs:complexType>
      <xs:sequence>
        <xs:choice minOccurs="0" maxOccurs="unbounded">
          <xs:element maxOccurs="unbounded" name="attr">
            <xs:complexType>
              <xs:simpleContent>
                <xs:extension base="xs:string">
                  <xs:attribute name="name" type="xs:string" use="required" />
                  <xs:attribute name="value" type="xs:string" use="optional" />
                </xs:extension>
              </xs:simpleContent>
            </xs:complexType>
          </xs:element>
          <xs:element name="list">
            <xs:complexType>
              <xs:sequence>
                <xs:element ref="data" minOccurs="0" maxOccurs="unbounded" />
              </xs:sequence>
              <xs:attribute name="name" type="xs:string" use="required" />
            </xs:complexType>
          </xs:element>
        </xs:choice>
      </xs:sequence>
      <xs:attribute name="name" type="xs:string" use="required" />
    </xs:complexType>
  </xs:element>
</xs:schema>';

        public function __construct($name = null, $xml = null) {
            if(!is_null($xml)) {
                return $this->ReadXml($xml);
            }
            $this->name = $name;
            return $this;
        }
        
        /*public function __toString()
        {
            return DataObject::Serialize($this);
        }*/

        public function SetString($name, $value)        
        {
            $this->attributes[$name] = $value;
            return $this;
        }

        public function SetGuid($name, $value)        
        {
            $this->attributes[$name] = $value;
        }

        /**
        * Child-коллекция элементов типа DataObject
        * 
        * @param string $name Имя атрибута
        * @param Array(DataObject) $value Массив объектов типа DataObject
        */
        public function SetList($name, $value)        
        {
            if(!is_array($value))
            {
                $method = __METHOD__;
                throw new Exception('Произошла ошибка в '.$method.'().');
            }
            $this->attributes[$name] = $value;
            $list = &$this->attributes[$name];
            return $list;
        }

        public function SetOADateTime($name, $value)        
        {
            global $mikron;
            if(!is_numeric($value))
            {
                $value = strtotime($value);
            }
            $value = MikronDateTime::ToOADate($value);
            $this->attributes[$name] = $value;
        }

        public function SetOADateTimeMSSQL($name, $value)        
        {
            global $mikron;
            if(!is_numeric($value))
            {
                $value = strtotime($value);
            }
            $value = MikronDateTime::ToOADateMSSQL($value);
            $this->attributes[$name] = $value;
        }

        public function GetOADateTimeMSSQL($name)        
        {
            if(!array_key_exists($name, $this->attributes))
            {
                throw new Exception("Атрибут '{$name}' не содержится в коллекции.");
            }
            global $mikron;
            $value = str_replace(',', '.', $this->attributes[$name]);
            return MikronDateTime::FromOADateMSSQL($value);
        }

        public function SetDateTime($name, $value)        
        {
            global $mikron;
            if(is_numeric($value))
            {
                $value = date($mikron->Constants->DateTimeFormat, (int)$value);
            }
            else
            {
                $value = date($mikron->Constants->DateTimeFormat, strtotime($value));
            }
            $value = strtotime($value);
            $this->attributes[$name] = $value;
        }

        public function SetInt($name, $value)        
        {
            $this->attributes[$name] = (int)$value;
        }
        
        /**
        * Recursive function
        * 
        * @param DOMDocument $doc
        * @param DOMElement $data
        * @param Array of DataObject $attributes
        */
        private static function Preserialize(&$doc, &$data, &$attributes)
        {
            if(!is_array($attributes))
            {
                return;
            }
            foreach($attributes as $name => $value)
            {
                if(is_array($value))
                {
                    $attr = $doc->createElement('list');
                    $attr->setAttribute('name', $name);
                    $data->appendChild($attr);
                    foreach($value as $DataObjectAttribute)
                    {
                        $innerAttrItem = $doc->createElement('data');
                        $innerAttrItem->setAttribute('name', $DataObjectAttribute->name);
                        self::Preserialize($doc, $innerAttrItem, $DataObjectAttribute->attributes);
                        $attr->appendChild($innerAttrItem);
                    }                                      
                }
                else
                {
                    $attr = $doc->createElement('attr');
                    $attr->setAttribute('name', $name);
                    $text = $doc->createTextNode($value);
                    $attr->appendChild($text);
                    $data->appendChild($attr);
                }
            }
        }
        
        /**
        * Сериализация
        * 
        * @param DataObject $dataObject
        * @return string
        */
        public static function Serialize(DataObject $dataObject)
        {
            $doc = new DOMDocument();
            $doc->formatOutput = true;
            $data = $doc->createElement('data');
            $data->setAttribute('name', $dataObject->name);
            self::Preserialize($doc, $data, $dataObject->attributes);
            $doc->appendChild($data);
            return $doc->SaveXML();
        }
        
        public function __toString()
        {
            return DataObject::Serialize($this);
        }

        /**
        * Десериализация
        * 
        * @param mixed $xml Строка, содержащая XML
        * @return DataObject
        */
        public static function Deserialize($xml)
        {
            $dataObject = new DataObject();
            $dataObject->ReadXml($xml);
            return $dataObject;
        }

        private function parseNode($root, $dataObject)
        {
            $obj = $root->childNodes;
            for ($i = 0; $i < $obj->length; $i++)
            {
                $item = $obj->item($i);
                if($item instanceof DOMElement)
                {
                    switch($item->nodeName)
                    {
                        case 'attr':
                            $name = $item->getAttribute('name');
                            $value = $item->textContent;
                            $dataObject->SetString($name, $value);
                            break;
                        case 'list':
                            $name = $item->getAttribute('name');
                            $subDataObject = $item->childNodes;
                            $items = Array();
                            for ($j = 0; $j < $subDataObject->length; $j++)
                            {
                                $sub_item = $subDataObject->item($j);
                                if($sub_item instanceof DOMElement)
                                {
                                    switch($sub_item->nodeName)
                                    {
                                        case 'data':
                                            $value = new DataObject($sub_item->getAttribute('name'));
                                            $this->parseNode($sub_item, $value);
                                            $items[] = $value;
                                            break;
                                    }
                                }
                            }
                            $dataObject->SetList($name, $items);
                            break;
                    }
                }
            }
        }

        public function ReadXml($xml)
        {
            $dom = new DOMDocument();
            $dom->loadXML($xml);

            $validation_result = $dom->schemaValidateSource(DataObject::$XSD);
            if(!$validation_result)
            {
                throw new Exception('Произошла ошибка при валидации xml.', 1);
            }

            $this->SourceXML = $xml;
            $this->attributes = Array();
            $root = $dom->firstChild;

            $this->name = $root->getAttribute('name');
            $this->parseNode($root, $this);
        }
        
        public function HasAttribute($name)
        {
            return array_key_exists($name, $this->attributes);
        }

        function GetList($attr)
        {
            if(!array_key_exists($attr, $this->attributes))
            {
                throw new Exception("Атрибут '{$attr}' не содержится в коллекции.");
            }
            return $this->attributes[$attr];
        }

        public function GetGuid($attr)
        {
            if(!array_key_exists($attr, $this->attributes))
            {
                throw new Exception("Атрибут '{$attr}' не содержится в коллекции.");
            }
            return $this->attributes[$attr];
        }

        function GetString($attr)
        {
            if(!array_key_exists($attr, $this->attributes))
            {
                throw new Exception("Атрибут '{$attr}' не содержится в коллекции.");
            }
            return $this->attributes[$attr];
        }

        function GetDateTime($attr)
        {
            if(!array_key_exists($attr, $this->attributes))
            {
                throw new Exception("Атрибут '{$attr}' не содержится в коллекции.");
            }
            return $this->attributes[$attr];
        }

        function GetInt($attr)
        {
            if(!array_key_exists($attr, $this->attributes))
            {
                throw new Exception("Атрибут '{{$attr}}' не содержится в коллекции.");
            }
            return (int)$this->attributes[$attr];
        }

        function GetDouble($attr)
        {
            if(!array_key_exists($attr, $this->attributes))
            {
                throw new Exception("Атрибут '{$attr}' не содержится в коллекции.");
            }
            return doubleval(str_replace(',', '.', $this->attributes[$attr]));
        }

    }






















































































































































































































































