#!/usr/bin/env python

import json, requests

class ESInterface:
        """ElasticSearch client"""
        url=""
        ver=5
        headers = {'content-type': 'application/json', 'Accept-Charset': 'UTF-8'}

        def __init__(self):
                pass

        def setURL(self, url):
                if url.endswith('/'):
                        url=url[:-1]
                self.url=url

        def setVersion(self, ver):
                self.ver=ver

        def curlOps(self, url, op, data=None):
                if data is None:
                        r=requests.request(op, url, headers=self.headers)
                else:
                        r=requests.request(op, url, data=json.dumps(data), headers=self.headers)
                return r.text

        def getSingle(self, _index, _type, _id):
                return self.curlOps(self.url+"/"+_index+"/"+_type+"/"+str(_id), 'GET')

        def searchFilter(self, _index, _type, _filters, _sort='', _offset=0, _size=10):
                if self.ver<5:
                        data={'query':{'filtered':{'filter':{'bool':{'must': _filters}}}}}
                else:
                        data={'query':{'bool':{'filter': _filters}}}
                if _sort!='':
                        _sort="sort="+_sort+"&"
                return self.curlOps(self.url+"/"+_index+"/"+_type+"/_search?"+_sort+"from="+str(_offset)+"&size="+str(_size), 'GET', data)

        def searchQuery(self, _index, _type, _queries, _sort='', _offset=0, _size=10):
                data={'query':{'bool':{'must': _queries}}}
                if _sort!='':
                        _sort="sort="+_sort+"&"
                return self.curlOps(self.url+"/"+_index+"/"+_type+"/_search?"+_sort+"from="+str(_offset)+"&size="+str(_size), 'GET', data)

        def searchMixed(self, _index, _type, _filters, _queries, _sort='', _offset=0, _size=10):
                if self.ver<5:
                        data={'query':{'filtered':{'filter':{'bool':{'must':_filters}}, 'query': {'bool': {'must': _queries}}}}}
                else:
                        data={'query':{'bool':{'must': _queries, 'filter': _filters}}}
                if _sort!='':
                        _sort="sort="+_sort+"&"
                return self.curlOps(self.url+"/"+_index+"/"+_type+"/_search?"+_sort+"from="+str(_offset)+"&size="+str(_size), 'POST', data)

        def deleteIndex(self, _index):
                self.curlOps(self.url+"/"+_index, 'DELETE')

        def createIndex(self, _index):
                self.curlOps(self.url+"/"+_index, 'PUT')

        def setMapping(self, _index, _type, _field, _declArray):
                rURL=self.url+"/"+_index+"/"+_type+"/_mapping";
                return self.curlOps(rURL, 'PUT', {_type: {'properties':{_field: _declArray}}})

        def indexSingle(self, _type, _id, _data):
                rURL=self.url+"/"+_index+"/"+_type+"/"+str(_id)
                return self.curlOps(rURL, 'PUT', _data)

        def deleteSingle(self, _index, _type, _id):
                rURL=self.url+"/"+_index+"/"+_type+"/"+str(_id)
                return self.curlOps(uURL, 'DELETE')
