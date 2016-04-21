<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 13:14
 * To change this template use File | Settings | File Templates.
 */


class XMLParser implements IParser
{

    public function getName()
    {
        return "xml";
    }

    private function write_xml( XMLWriter $xml, $data ) {
        foreach( $data as $key => $value ) {
            if( is_array( $value )) {
                $xml->startElement( $key );
                $this->write_xml( $xml, $value );
                $xml->endElement( );
                continue;
            }
            $xml->writeElement( $key, $value );
        }
    }


    public function parse(array $data = null, $root = null)
    {
        if (empty($data)) return '<?xml version="1.0" encoding="UTF-8"?><root/>';
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startDocument( '1.0', 'utf-8' );
        $xml->startElement( 'root') ;

        $this->write_xml($xml, $data);

        $xml->endElement();
        return preg_replace("/\r|\n/", "", trim($xml->outputMemory(true)));
    }
}