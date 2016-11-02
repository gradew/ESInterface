# ESInterface
A requests Python client library for ElasticSearch

## Examples

### Initializing the static class

```python
es=ESInterface()
es.setURL('http://es-node:9200')
```

### Getting a single document based on its ID

```python
res=json.loads(es.getSingle('agendacenter', 'annonces', entry_id))
```

### Example of a Filter/Query search

```python
conds=[
       {'term': {'userid': 1} },
       {'range': {'enddate': {'gte': '2016-01-01 00:00:00'}}}
       ]
res=json.loads(es.searchFilter('esindex', 'estype', conds, 'startdate:asc'))
res=json.loads(es.searchQuery('esindex', 'estype', conds, 'startdate:asc'))

```

### Example for a mixed search (filter + query)

```python
conds=[
        {'query_string': {'query': keywords}},
        {'range': {'enddate': {'gte': '2016-01-01 00:00:00'}}}
        ]
filters=[
        {'geo_distance': {'distance': distance, 'location': {'lat': latitude, 'lon': longitude}}}
        ]
res=json.loads(es.searchMixed('esindex', 'estype', filters, conds, 'startdate:asc'))
```

### Deleting a document

```python
es.deleteSingle('esindex', 'estype', document_id);
```

### Indexing a document

```python
es.indexSingle('esindex', 'estype', id, data);
```
