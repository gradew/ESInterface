# ESInterface
A cURL PHP client library for ElasticSearch

## Examples

### Initializing the static class

```php
ESInterface::setURL('http://es-node:9200');
```

### Getting a single document based on its ID

```php
$res=json_decode(ESInterface::getSingle('esindex', 'estype', $entry_id), true);
```

### Example of a Filter/Query search

```php
$conds=array();
$conds[]=array("term" => array("userid"=> "$authorized_user_id"));
$conds[]=array("range" => array('enddate'=>array('gte'=>"$curdate")));
$result=ESInterface::searchQuery('esindex', 'estype', $conds, "startdate:asc");
$result=ESInterface::searchFilter('esindex', 'estype', $conds, "startdate:asc");
```

### Example for a mixed search (filter + query)

```php
$curdate=date('Y-m-d')." 00:00:00";
$conds=array();
$filters=array();
$conds[]=array("query_string"=> array("query"=> "$keywords"));
$conds[]=array("match"=> array("userid"=>2));
$conds[]=array("range" => array("enddate" => array("gte" => "$curdate")));
$filters[]=array("geo_distance" => array("distance" => "$distance", "location" => array("lat" => "$latitude", "lon" => "$longitude")));
$res=ESInterface::searchMixed('esindex', 'estype', $filters, $conds, "startdate:asc");
```

### Deleting a document

```php
ESInterface::deleteSingle('esindex', 'estype', $document_id);
```

### Indexing a document

```php
ESInterface::indexSingle('esindex', 'estype', $data['id'], $data);
```
