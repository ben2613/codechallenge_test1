# You can start this project with

```
git clone https://github.com/ben2613/codechallenge_test1
cd codechallenge_test1
docker-compose up -d
```

## Task 1
Single table
|node|
|---|
|id|
|parentId|
|isDirectory|
|name|

## Task 2
Run

`docker-compose exec app php runTask2.php`

and preview the data at (http://localhost:8081/?server=db&username=mariadb&db=mariadb&select=node)

Try modify `resources/files.xml` and run the command again.

## Task 3

See [Task3](Task3.md)

## Task 4

(http://localhost:8080)


### Reference:

https://github.com/powerwebdev/php-crud-framework/tree/master/dao
