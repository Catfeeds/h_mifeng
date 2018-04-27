# Name:test_database_backup.sh
# This is a ShellScript For Auto DB Backup and Delete old Backup
# 0 0 * * * /sh/mifengbeibei_mysql.sh
#备份地址
backupdir=/phpstudy/www/mifengbeibei.com/databak
#备份文件后缀时间
time=_`date +%Y%m%d%H%M%S`
#需要备份的数据库名称
db_name=mifengbeibei
#数据库服务器，一般为localhost
host=localhost
#mysql 用户名
db_user=root
#mysql 密码
db_pass=mifengbeibei_pasw
#mysqldump命令使用绝对路径
/phpstudy/mysql/bin/mysqldump -h$host -u$db_user -p$db_pass $db_name>$backupdir/$db_name$time.sql
#删除30天之前的备份文件
find $backupdir -name $db_name"*.sql" -type f -mtime +30 -exec rm -rf {} \; > /dev/null 2>&1