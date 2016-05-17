# 新建一个mysql的容器
docker file的地址https://github.com/DaoCloud/mysql
## 新建mysql容器的方式

    docker run --name laravel-mysql -v `pwd`/datadir:/var/lib/mysql -p 33060:3306 -e MYSQL_ROOT_PASSWORD=root -d mysql
    
        docker run --name laravel-mysql -p 33060:3306 -e MYSQL_ROOT_PASSWORD=root -d mysql