So the question is to parse tree data structure / hierarchical data in a database? plus distinguishing folders and files.

## Requirements

1. Store a tree structure in database
1. Application Logic <-> DB
1. Parser to parse a text file
    - data format in XML? JSON?
1. Simple search webinterface

## Table

```
Nodes
Id - int
ParentId - int
# (-1 for C:, D:? others are under the drives)
IsDirectory - bool
Name - string
```


## Notes

Performing recursive deleting may have issues but seems out of scope here
