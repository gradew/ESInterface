<?php
class ESInterface {
        static $url='';
        static function setURL($str)
        {
                $lastoff=strlen($str)-1;
                if($str[$lastoff]=='/'){
                        rtrim($str, "/");
                }
                self::$url=$str;
        }
        static function getSingle($index, $type, $id)
        {
                return ESInterface::curlOps(self::$url."/$index/$type/".strval($id), 'GET');
        }
        static function getRange($index, $offset=0, $size=10)
        {
                return ESInterface::curlOps(self::$url."/$index/_search?from=".strval($offset)."&size=".strval($size), 'GET');
        }
        static function searchFilter($index, $type, $filters, $sort="", $offset=0, $size=10)
        {
                //$data=array("query"=> array("filtered"=> array("filter"=> array("bool"=> array("must"=> $filters)))));
                $data=array("query"=> array("bool"=> array("filter"=> $filters)));
                if($sort!=""){
                        $sort="sort=$sort&";
                }
                return ESInterface::curlOps(self::$url."/$index/$type/_search?".$sort."from=".strval($offset)."&size=".strval($size), 'GET', $data);
        }
        static function searchQuery($index, $type, $queries, $sort="", $offset=0, $size=10)
        {
                $data=array("query"=> array("bool"=> array("must"=> $queries)));
                if($sort!=""){
                        $sort="sort=$sort&";
                }
                return ESInterface::curlOps(self::$url."/$index/$type/_search?".$sort."from=".strval($offset)."&size=".strval($size), 'GET', $data);
        }
        static function searchMixed($index, $type, $filters, $queries, $sort="", $from=0, $size=10)
        {
                $data=array("query"=> array("filtered"=> array("filter"=> array("bool"=> array("must"=> $filters)),"query"=> array("bool"=> array("must"=> $queries)))));
                if($sort!=""){
                        $sort="sort=$sort&";
                }
                return ESInterface::curlOps(self::$url."/$index/$type/_search?".$sort."from=".strval($from)."&size=".strval($size), 'POST', $data);
        }
        static function curlOps($url, $op, $data=NULL)
        {
                $curl=curl_init($url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $op);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                if($data!=NULL) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                $response=curl_exec($curl);
                return $response;
        }

        static function deleteIndex($index)
        {
                ESInterface::curlOps(self::$url."/$index", 'DELETE');
        }

        static function createIndex($index)
        {
                ESInterface::curlOps(self::$url."/$index", 'PUT');
        }

        static function setMapping($index, $type, $field, $declArray)
        {
                $rURL=self::$url."/$index/$type/_mapping";
                return self::curlOps($rURL, 'PUT', array($type => array('properties' => array($field => $declArray))));
        }
        static function indexSingle($index, $type, $id, $data)
        {
                $rURL=self::$url."/$index/$type/$id";
                return ESInterface::curlOps($rURL, 'PUT', $data);
        }
        static function deleteSingle($index, $type, $id)
        {
                $rURL=self::$url."/$index/$type/$id";
                return ESInterface::curlOps($rURL, 'DELETE');
        }
};
?>
